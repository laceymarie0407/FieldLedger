<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

require_once '../Config/database.php';

// Get active jobs
$jobs_sql = "SELECT job_id, job_number, job_name
             FROM jobs
             WHERE status = 'Active'
             ORDER BY job_name";

$jobs_result = $conn->query($jobs_sql);

if (!$jobs_result) {
    die("Error loading jobs: " . $conn->error);
}

// Select foreman
$foremen = $conn->query("
    SELECT employee_id,first_name,last_name
    FROM employees
    WHERE job_title='Foreman' AND active=1
    ORDER BY last_name,first_name");
$stmt = $conn->prepare("SELECT employee_id FROM users WHERE user_id=?");
$stmt->bind_param("s", $_SESSION['user_id']);
$stmt->execute();
$logged_in_employee = $stmt->get_result()->fetch_assoc();
$logged_in_employee_id = $logged_in_employee['employee_id'] ?? null;

// Get employees for crew dropdown
$employees_sql = "SELECT employee_id, first_name, last_name
                  FROM employees
                  ORDER BY last_name, first_name";

$employees_result = $conn->query($employees_sql);

if (!$employees_result) {
    die("Error loading employees: " . $conn->error);
}

$employees = [];

while ($employee = $employees_result->fetch_assoc()) {
    $employees[] = $employee;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Report | FieldLedger</title>
    <link rel="stylesheet" href="../Assets/styles.css">
</head>

<body>

<?php require_once '../Assets/header.php'; ?>

<main>

    <h2>Daily Report</h2>
    <p>Enter today's job activity and field information.</p>

    <!-- REPORT INFORMATION -->
    <div class="panel">

        <h3>Report Information</h3>

        <div class="daily-report-info">

            <div class="form-group">
                <label for="job_id">Job</label>

                <select id="job_id" name="job_id" required>
                    <option value="">Select a Job</option>

                    <?php while ($job = $jobs_result->fetch_assoc()): ?>
                        <option value="<?php echo $job['job_id']; ?>">
                            <?php
                            echo htmlspecialchars(
                                $job['job_number'] . ' - ' . $job['job_name']
                            );
                            ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>


            <div class="form-group">
                <label for="foreman_id">Foreman</label>
                <select name="foreman_id" id="foreman_id" required>
                    <option value="">Select Foreman</option>
                    <?php while ($foreman = $foremen->fetch_assoc()): ?>
                        <option value="<?php echo $foreman['employee_id']; ?>" <?php echo $foreman['employee_id'] == $logged_in_employee_id ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($foreman['first_name'].' '.$foreman['last_name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select></div>

            <div class="form-group">
                <label for="report_date">Report Date</label>

                <input
                    type="date"
                    id="report_date"
                    name="report_date"
                    value="<?php echo date('Y-m-d'); ?>"
                    required
                >
            </div>


            <div class="form-group">
                <label for="weather">Weather</label>

                <select id="weather" name="weather">
                    <option value="">Select Weather</option>
                    <option value="Clear">Clear</option>
                    <option value="Cloudy">Cloudy</option>
                    <option value="Rain">Rain</option>
                    <option value="Snow">Snow</option>
                    <option value="Other">Other</option>
                </select>
            </div>


            <div class="form-group full-width">
                <label for="site_conditions">Weather / Site Conditions</label>

                <textarea
                    id="site_conditions"
                    name="site_conditions"
                    placeholder="Enter weather impacts, site conditions, access issues, delays, or other conditions affecting today's work..."
                ></textarea>
            </div>

        </div>
    </div>


    <!-- TODAY'S TASKS -->
    <div class="panel">

        <div class="section-heading">
            <div>
                <h3>Today's Tasks</h3>
                <p class="section-help">
                    Select a task from the job estimate to enter today's field activity.
                </p>
            </div>
        </div>

        <div class="daily-task-grid" id="dailyTaskGrid">
            <div class="task-empty-state">
                Select a job above to view its estimated tasks.
            </div>
        </div>

    </div>


    <!-- TASK ENTRY -->
    <div id="taskEntryPanel" class="task-entry-panel">

        <div class="task-entry-header">

            <div>
                <small>Today's Activity</small>
                <h3 id="selectedTaskName"></h3>
            </div>

            <button
                type="button"
                id="closeTask"
                class="close-task-button"
            >
                Close
            </button>

        </div>

        <input
            type="hidden"
            id="selectedTaskId"
            name="task_id"
        >


        <!-- CREW / LABOR -->
        <div class="task-section">

            <h4>Crew / Labor</h4>

            <div id="laborRows">

                <div class="labor-row">

                    <div class="form-group employee-field">
                        <label>Employee</label>

                        <select name="employee_id[]">
                            <option value="">Select Employee</option>

                            <?php foreach ($employees as $employee): ?>
                                <option value="<?php echo $employee['employee_id']; ?>">
                                    <?php
                                    echo htmlspecialchars(
                                        $employee['first_name'] . ' ' . $employee['last_name']
                                    );
                                    ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>


                    <div class="form-group hours-field">
                        <label>Regular Hrs</label>

                        <input
                            type="number"
                            name="regular_hours[]"
                            class="labor-hours"
                            min="0"
                            step="0.25"
                            placeholder="0"
                        >
                    </div>


                    <div class="form-group hours-field">
                        <label>OT Hrs</label>

                        <input
                            type="number"
                            name="overtime_hours[]"
                            class="labor-hours"
                            min="0"
                            step="0.25"
                            placeholder="0"
                        >
                    </div>

                </div>

            </div>

            <button
                type="button"
                id="addEmployee"
                class="secondary-button"
            >
                + Add Employee
            </button>

            <div class="labor-total">
                Total Labor Hours:
                <strong id="totalLaborHours">0.00</strong>
            </div>

        </div>


        <!-- EQUIPMENT -->
        <div class="task-section">

            <h4>Equipment</h4>

            <div id="equipmentRows">

                <div class="field-row equipment-row">

                    <div class="form-group">
                        <label>Equipment</label>

                        <select name="equipment_used[]">
                            <option value="">Select Equipment</option>
                            <option>Cat 953 Track Loader</option>
                            <option>Cat 225 Track Excavator</option>
                            <option>Skid Steer</option>
                            <option>Pickup Truck</option>
                        </select>
                    </div>


                    <div class="form-group">
                        <label>Equipment Hours</label>

                        <input
                            type="number"
                            name="equipment_hours[]"
                            min="0"
                            step="0.25"
                            placeholder="0"
                        >
                    </div>

                </div>

            </div>

            <button
                type="button"
                id="addEquipment"
                class="secondary-button"
            >
                + Add Equipment
            </button>

        </div>


        <!-- MATERIALS -->
        <div class="task-section">

            <h4>Materials</h4>

            <div id="materialRows">

                <div class="field-row three-column material-row">

                    <div class="form-group">
                        <label>Material</label>

                        <select name="materials_used[]">
                            <option value="">Select Material</option>
                            <option>Stone</option>
                            <option>Topsoil</option>
                            <option>Pipe</option>
                            <option>Erosion Control Supplies</option>
                        </select>
                    </div>


                    <div class="form-group">
                        <label>Quantity</label>

                        <input
                            type="number"
                            name="material_quantity[]"
                            min="0"
                            step="0.01"
                            placeholder="0"
                        >
                    </div>


                    <div class="form-group">
                        <label>Unit</label>

                        <select name="material_unit[]">
                            <option value="">Select Unit</option>
                            <option>Tons</option>
                            <option>Cubic Yards</option>
                            <option>Linear Feet</option>
                            <option>Each</option>
                            <option>Lump Sum</option>
                        </select>
                    </div>

                </div>

            </div>

            <button
                type="button"
                id="addMaterial"
                class="secondary-button"
            >
                + Add Material
            </button>

        </div>


        <!-- PRODUCTION -->
        <div class="task-section">

            <h4>Production</h4>

            <div class="field-row">

                <div class="form-group">
                    <label>Quantity Completed</label>

                    <input
                        type="number"
                        id="productionQty"
                        name="production_qty"
                        min="0"
                        step="0.01"
                        placeholder="0"
                    >
                </div>


                <div class="form-group">
                    <label>Unit</label>

                    <select
                        id="productionUnit"
                        name="production_unit"
                    >
                        <option value="">Select Unit</option>
                        <option>Tons</option>
                        <option>Cubic Yards</option>
                        <option>Linear Feet</option>
                        <option>Each</option>
                        <option>Lump Sum</option>
                    </select>
                </div>

            </div>

        </div>


        <!-- NOTES -->
        <div class="task-section">

            <h4>Task Details / Notes</h4>

            <textarea
                id="taskNotes"
                name="task_notes"
                placeholder="Enter work performed, progress, challenges, delays, issues, or other details about this task..."
            ></textarea>

        </div>


        <div class="task-save-area">
            <button
                type="button"
                id="saveTask"
                class="save-task-button"
            >
                Save Task
            </button>
        </div>

    </div>


    <div class="report-actions">
        <button
            type="button"
            class="submit-report-button"
        >
            Submit Daily Report
        </button>
    </div>

</main>


<script>

// Main page elements
const jobSelect = document.getElementById('job_id');
const taskGrid = document.getElementById('dailyTaskGrid');
const taskPanel = document.getElementById('taskEntryPanel');
const taskName = document.getElementById('selectedTaskName');
const taskId = document.getElementById('selectedTaskId');
const totalLabor = document.getElementById('totalLaborHours');

let activeTaskCard = null;


// Load tasks when a job is selected
jobSelect.addEventListener('change', function () {

    let jobId = this.value;

    taskPanel.classList.remove('open');
    taskGrid.innerHTML = '';

    if (jobId == '') {
        taskGrid.innerHTML =
            '<div class="task-empty-state">Select a job above to view its estimated tasks.</div>';
        return;
    }

    taskGrid.innerHTML =
        '<div class="task-empty-state">Loading tasks...</div>';

    fetch('get_job_tasks.php?job_id=' + jobId)
        .then(response => response.json())
        .then(tasks => {

            taskGrid.innerHTML = '';

            if (tasks.length == 0) {
                taskGrid.innerHTML =
                    '<div class="task-empty-state">No estimated tasks found for this job.</div>';
                return;
            }

            tasks.forEach(task => {

                let card = document.createElement('button');

                card.type = 'button';
                card.className = 'daily-task-card';
                card.dataset.taskId = task.task_id;
                card.dataset.task = task.task_name;

                card.innerHTML =
                    '<span class="task-status">Not Entered</span>' +
                    '<span class="task-card-name"></span>' +
                    '<span class="task-card-summary">Enter Details</span>';

                card.querySelector('.task-card-name').textContent =
                    task.task_name;

                taskGrid.appendChild(card);
            });
        })
        .catch(error => {
            console.log(error);

            taskGrid.innerHTML =
                '<div class="task-empty-state">Unable to load tasks.</div>';
        });
});


// Open a task card
taskGrid.addEventListener('click', function (event) {

    let card = event.target.closest('.daily-task-card');

    if (!card) {
        return;
    }

    activeTaskCard = card;

    taskName.textContent = card.dataset.task;
    taskId.value = card.dataset.taskId;

    taskPanel.classList.add('open');

    taskPanel.scrollIntoView({
        behavior: 'smooth',
        block: 'start'
    });
});


// Close task
document.getElementById('closeTask').addEventListener('click', function () {
    taskPanel.classList.remove('open');
});


// Calculate total labor hours
function calculateLaborHours() {

    let total = 0;

    document.querySelectorAll('.labor-hours').forEach(function (input) {
        total += parseFloat(input.value) || 0;
    });

    totalLabor.textContent = total.toFixed(2);
}

document.addEventListener('input', function (event) {

    if (event.target.classList.contains('labor-hours')) {
        calculateLaborHours();
    }

});


// Add another employee
document.getElementById('addEmployee').addEventListener('click', function () {

    let laborRows = document.getElementById('laborRows');
    let firstRow = laborRows.querySelector('.labor-row');
    let newRow = firstRow.cloneNode(true);

    newRow.querySelector('select').selectedIndex = 0;

    newRow.querySelectorAll('input').forEach(function (input) {
        input.value = '';
    });

    laborRows.appendChild(newRow);
});


// Add another equipment row
document.getElementById('addEquipment').addEventListener('click', function () {

    let rows = document.getElementById('equipmentRows');
    let newRow = rows.querySelector('.equipment-row').cloneNode(true);

    newRow.querySelector('select').selectedIndex = 0;

    newRow.querySelectorAll('input').forEach(function (input) {
        input.value = '';
    });

    rows.appendChild(newRow);
});


// Add another material row
document.getElementById('addMaterial').addEventListener('click', function () {

    let rows = document.getElementById('materialRows');
    let newRow = rows.querySelector('.material-row').cloneNode(true);

    newRow.querySelectorAll('select').forEach(function (select) {
        select.selectedIndex = 0;
    });

    newRow.querySelectorAll('input').forEach(function (input) {
        input.value = '';
    });

    rows.appendChild(newRow);
});


// Save task on the page
document.getElementById('saveTask').addEventListener('click', function () {

    if (!activeTaskCard) {
        return;
    }

    let laborHours = totalLabor.textContent;
    let production = document.getElementById('productionQty').value;
    let unit = document.getElementById('productionUnit').value;

    activeTaskCard.classList.add('saved');

    activeTaskCard.querySelector('.task-status').textContent =
        '✓ Saved';

    let summary = laborHours + ' labor hrs';

    if (production != '') {
        summary += ' • ' + production;

        if (unit != '') {
            summary += ' ' + unit;
        }
    }

    activeTaskCard.querySelector('.task-card-summary').textContent =
        summary;

    taskPanel.classList.remove('open');
});

</script>

<?php require_once '../Assets/footer.php'; ?>

</body>
</html>
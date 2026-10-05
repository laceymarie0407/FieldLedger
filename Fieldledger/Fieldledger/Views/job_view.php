<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();}
require_once '../Config/database.php';
if (!isset($_GET['job_id'])) {
    die("No job selected.");}

$job_id = (int) $_GET['job_id'];

// GET JOB
$stmt = $conn->prepare("SELECT * FROM jobs WHERE job_id = ?");
$stmt->bind_param("i", $job_id);
$stmt->execute();
$job = $stmt->get_result()->fetch_assoc();
if (!$job) {die("Job not found.");}

// GET ESTIMATE
$stmt = $conn->prepare("SELECT * FROM estimates WHERE job_id = ?");
$stmt->bind_param("i", $job_id);
$stmt->execute();
$estimate = $stmt->get_result()->fetch_assoc();
$estimate_actions_mode = 'process';
include '../Assets/estimate_actions.php';

// CREATE ESTIMATE
if (isset($_POST['create_estimate']) && !$estimate) {
    $start = $_POST['start_date'];
    $end = $_POST['estimated_end_date'];
    $stmt = $conn->prepare("
        INSERT INTO estimates (job_id, start_date, estimated_end_date)
        VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $job_id, $start, $end);
    $stmt->execute();
    header("Location: job_view.php?job_id=$job_id");
    exit();
}

// ADD TASK
if (isset($_POST['add_task']) && $estimate) {
    $stmt = $conn->prepare("
        INSERT INTO job_tasks (
            estimate_id, task_name, description,
            estimated_duration_days, estimated_labor_hours,
            estimated_labor_cost, estimated_equipment_hours,
            estimated_equipment_cost, estimated_material_cost,
            estimated_production_qty, production_unit
        )
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param(
        "issddddddds",
        $estimate['estimate_id'],
        $_POST['task_name'],
        $_POST['task_description'],
        $_POST['estimated_duration_days'],
        $_POST['estimated_labor_hours'],
        $_POST['estimated_labor_cost'],
        $_POST['estimated_equipment_hours'],
        $_POST['estimated_equipment_cost'],
        $_POST['estimated_material_cost'],
        $_POST['estimated_production_qty'],
        $_POST['production_unit']
    );
    $stmt->execute();
    header("Location: job_view.php?job_id=$job_id");
    exit();
}
// MARK REPORT REVIEWED
if (isset($_POST['close_report'])) {

    $report_id = (int) $_POST['report_id'];

    $user_id = $_SESSION['user_id'];

    // Get the name of the logged-in user

    $user_stmt = $conn->prepare("

        SELECT e.first_name, e.last_name

        FROM users u

        JOIN employees e ON u.employee_id = e.employee_id

        WHERE u.user_id = ?

    ");

    $user_stmt->bind_param("i", $user_id);
    $user_stmt->execute();
    $reviewer = $user_stmt->get_result()->fetch_assoc();
    $reviewed_by = $reviewer['first_name'] . ' ' . $reviewer['last_name'];
    $stmt = $conn->prepare("
        UPDATE daily_reports
        SET status = 'Closed',
            reviewed_by = ?,
            reviewed_date = NOW()
        WHERE report_id = ? AND job_id = ?
    ");

    $stmt->bind_param("sii", $reviewed_by, $report_id, $job_id);
    $stmt->execute();
    header("Location: job_view.php?job_id=$job_id");
    exit();
}

// GET TASKS
$tasks = null;
if ($estimate) {
    $stmt = $conn->prepare("
        SELECT *
        FROM job_tasks
        WHERE estimate_id = ?
        ORDER BY task_id
    ");
    $stmt->bind_param("i", $estimate['estimate_id']);
    $stmt->execute();
    $tasks = $stmt->get_result();
}

// GET DAILY REPORTS
$stmt = $conn->prepare("
    SELECT
        dr.*,
        e.first_name,
        e.last_name
    FROM daily_reports dr
    LEFT JOIN employees e
        ON dr.foreman_id = e.employee_id
    WHERE dr.job_id = ?
    ORDER BY dr.report_date DESC
");
$stmt->bind_param("i", $job_id);
$stmt->execute();
$reports = $stmt->get_result();

// GET METRICS
require_once '../Assets/job_metrics.php';
function formatUnit($unit) {
    $units = [
        'LS' => 'Lump Sum',
        'CY' => 'Cubic Yards',
        'LF' => 'Linear Feet',
        'SF' => 'Square Feet',
        'SY' => 'Square Yards',
        'AC' => 'Acres',
        'TON' => 'Tons'
    ];
    return $units[$unit] ?? $unit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo htmlspecialchars($job['job_name']); ?> | FieldLedger
    </title>
    <link rel="stylesheet" href="../Assets/styles.css">
</head>
<body>
<?php require_once '../Assets/header.php'; ?>
<main>

<!-- JOB HEADER -->
<section class="panel job-overview">
    <a href="jobs.php">← Back to Jobs</a>
    <div class="job-overview-grid">
        <!-- LEFT SIDE -->
        <div class="job-summary">
            <div class="job-header">
                <div>
                    <h2><?php echo htmlspecialchars($job['job_name']); ?></h2>
                    <p>
                        <?php echo htmlspecialchars($job['job_number']); ?>
                        |
                        <?php echo htmlspecialchars($job['customer_name']); ?>
                    </p>
                </div>
                <span class="status status-<?php echo strtolower($job['status']); ?>">
                    <?php echo htmlspecialchars($job['status']); ?>
                </span>
            </div>
            <div class="project-progress">
                <div class="progress-label">
                    <strong>Project Progress</strong>
                    <span>
                        <?php echo number_format($project_progress); ?>%
                    </span>
                </div>
                <div class="progress-bar">
                    <div
                        class="progress-fill"
                        style="width: <?php echo $project_progress; ?>%;">
                    </div>
                </div>

    <?php
    $estimate_actions_mode = 'display';
    include '../Assets/estimate_actions.php';
    ?>

    <?php if ($job['status'] === 'Complete' && !empty($job['completed_date'])): ?>
        <p class="completed-date">
            Completed <?php echo date('F j, Y', strtotime($job['completed_date'])); ?>
        </p>
    <?php endif; ?>
                    
            </div>
        </div>
        <!-- RIGHT SIDE -->
        <div class="job-details">
            <h3>Project Details</h3>
            <p>
                <strong>Customer:</strong>
                <?php echo htmlspecialchars($job['customer_name']); ?>
            </p>
            <p>
                <strong>Location:</strong>
                <?php
                echo htmlspecialchars(
                    $job['address'] . ', ' .
                    $job['city'] . ', ' .
                    $job['state'] . ' ' .
                    $job['zip_code']
                );
                ?>
            </p>
            <p>
                <strong>Scope:</strong>
                <?php echo htmlspecialchars($job['scope_description']); ?>
            </p>
            <?php if ($estimate): ?>
                <p>
                    <strong>Planned Start:</strong>
                    <?php echo date('F j, Y', strtotime($estimate['start_date'])); ?>
                </p>
                <p>
                    <strong>Estimated Completion:</strong>
                    <?php echo date('F j, Y', strtotime($estimate['estimated_end_date'])); ?>
                </p>
            <?php endif; ?>
        </div>
    </div>
</section>
<!-- DAILY REPORTS -->
<section class="panel">
    <div class="section-heading">
        <div>
            <h3>Daily Reports</h3>
            <p class="section-help">
                Field activity submitted for this job.
            </p>
        </div>
        <a
            href="daily_report.php?job_id=<?php echo $job_id; ?>"class="button-link">
            + New Daily Report
        </a>
    </div>
    <?php if ($reports->num_rows > 0): ?>
        <div class="report-list">
            <div class="report-list-header">
                <span>Date</span>
                <span>Foreman</span>
                <span>Work Performed</span>
                <span>Status</span>
                <span></span>
            </div>
            <?php while ($report = $reports->fetch_assoc()): ?>
                <?php
                // Get tasks that had activity on this report
                $task_stmt = $conn->prepare("
                    SELECT jt.task_name
                    FROM daily_task_entries dte
                    JOIN job_tasks jt ON dte.task_id = jt.task_id
                    WHERE dte.report_id = ?
                    ORDER BY jt.task_name");
                $task_stmt->bind_param("i", $report['report_id']);
                $task_stmt->execute();
                $report_tasks = $task_stmt->get_result();
                $task_names = [];
                while ($row = $report_tasks->fetch_assoc()) {$task_names[] = $row['task_name'];}
                if (count($task_names) > 2) {$work_summary =
                        $task_names[0] . ', ' .
                        $task_names[1] . ' + ' .
                        (count($task_names) - 2) . ' more';
                } elseif ($task_names) {$work_summary = implode(', ', $task_names);
                } else {$work_summary = 'No task activity entered';}
                $report_id = $report['report_id'];?>

                <!-- REPORT ROW -->
                <div
                    class="report-row"
                    data-report="<?php echo $report_id; ?>">

                    <span><?php echo date('m/d/y',strtotime($report['report_date'])); ?></span>
                    <span><?php echo htmlspecialchars($report['first_name'] . ' ' .$report['last_name']); ?></span>
                    <span><?php echo htmlspecialchars($work_summary); ?></span>
                    <span>
                        <span class="report-status status-<?php echo strtolower(str_replace(' ', '-', $report['status']));?>">
                            <?php echo htmlspecialchars($report['status']); ?>
                        </span>
                    </span>

                    <span>
                        <button type="button"
                                class="report-toggle"
                                data-report="<?php echo $report['report_id']; ?>">View ▾</button>
                    </span>
                </div>

                <!-- EXPANDED REPORT -->
                <div class="report-details" id="report-<?php echo $report_id; ?>">
                    <div class="report-detail-header">
                        <div><strong>Daily Report — <?php echo date('F j, Y', strtotime($report['report_date'])); ?></strong></div>
                        <div><strong>Foreman:</strong><?php echo htmlspecialchars($report['first_name'] . ' ' . $report['last_name']); ?></div>
                        <div><strong>Weather:</strong><?php echo htmlspecialchars($report['weather'] ?? ''); ?></div>
                    </div>

                    <?php if (!empty($report['notes'])): ?>
                        <div class="report-section"><h4>Site / Weather Notes</h4><p><?php echo nl2br(htmlspecialchars($report['notes'])); ?></p>
                        </div><?php endif; ?>

                    <?php
                    // Get labor entered on this report
                    $labor_stmt = $conn->prepare("
                        SELECT
                            le.regular_hours,
                            le.overtime_hours,
                            e.first_name,
                            e.last_name
                        FROM labor_entries le
                        JOIN employees e ON le.employee_id = e.employee_id
                        WHERE le.report_id = ?
                        ORDER BY e.last_name, e.first_name");
                    $labor_stmt->bind_param("i", $report_id);
                    $labor_stmt->execute();
                    $labor_entries = $labor_stmt->get_result();
                    $total_labor_hours = 0;?>

                    <?php if ($labor_entries->num_rows > 0): ?>
                        <div class="report-section"><h4>Labor</h4>
                            <div class="report-labor-list">
                                <?php while ($labor = $labor_entries->fetch_assoc()): ?>
                                <?php
                                    $regular = (float) $labor['regular_hours'];
                                    $overtime = (float) $labor['overtime_hours'];
                                    $total_labor_hours += $regular + $overtime;?>
                                    <div class="report-labor-row">
                                        <span><strong><?php echo htmlspecialchars($labor['first_name'] . ' ' . $labor['last_name']); ?></strong></span>
                                        <span>Regular: <?php echo number_format($regular, 2); ?> hrs</span>
                                        <span>OT: <?php echo number_format($overtime, 2); ?> hrs</span>
                                    </div>
                                <?php endwhile; ?></div>
                            <p class="report-labor-total"><strong>Total Labor: <?php echo number_format($total_labor_hours, 2); ?> hrs</strong></p>
                        </div><?php endif; ?>
                    <div class="report-section"><h4>Work Performed</h4>

                        <?php
                        // Get detailed task activity
                        $detail_stmt = $conn->prepare("
                            SELECT dte.*, jt.task_name
                            FROM daily_task_entries dte
                            JOIN job_tasks jt ON dte.task_id = jt.task_id
                            WHERE dte.report_id = ?
                            ORDER BY jt.task_name");

                        $detail_stmt->bind_param("i", $report_id);
                        $detail_stmt->execute();
                        $details = $detail_stmt->get_result();?>

                        <?php if ($details->num_rows > 0): ?>
                            <?php while ($detail = $details->fetch_assoc()): ?>
                                <div class="report-task-detail">
                                    <strong><?php echo htmlspecialchars($detail['task_name']); ?></strong>

                                    <?php if (!empty($detail['production_qty'])): ?>
                                        <p>
                                            Production:
                                            <?php echo number_format($detail['production_qty'], 2); ?>
                                            <?php echo htmlspecialchars($detail['production_unit']); ?>
                                        </p>
                                    <?php endif; ?>

                                    <?php if (!empty($detail['equipment_used'])): ?>
                                        <p>
                                            Equipment: <?php echo htmlspecialchars($detail['equipment_used']); ?>
                                            <?php if (!empty($detail['equipment_hours'])): ?>
                                                — <?php echo number_format($detail['equipment_hours'], 2); ?> hrs
                                            <?php endif; ?>
                                        </p>
                                    <?php endif; ?>

                                    <?php if (!empty($detail['notes'])): ?>
                                        <p>Notes: <?php echo nl2br(htmlspecialchars($detail['notes'])); ?></p>
                                    <?php endif; ?>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p>No task activity has been entered.</p>
                        <?php endif; ?>
                    </div>

                    <div class="report-detail-actions">
                        <?php if ($report['status'] !== 'Closed'): ?>
                            <a href="daily_report.php?report_id=<?php echo $report_id; ?>" class="button-link">Edit Report</a><?php endif; ?>

                        <?php if ($report['status'] === 'Submitted'): ?>
                            <form method="POST" action="job_view.php?job_id=<?php echo $job_id; ?>">
                                <input type="hidden" name="report_id" value="<?php echo $report_id; ?>">
                                <button type="submit" name="close_report">Mark Reviewed</button>
                            </form>
                        <?php endif; ?></div>
                <?php if (!empty($report['reviewed_by']) && !empty($report['reviewed_date'])): ?>
                    <div class="report-review-info"><strong>Reviewed by:</strong><?php echo htmlspecialchars($report['reviewed_by']); ?>
                        <span>·</span>
                        <?php echo date('m/d/Y g:i A', strtotime($report['reviewed_date'])); ?></div>
                <?php endif; ?></div>
                    </div>
                </div>
            <?php endwhile; ?></div>
    <?php else: ?>
        <p class="empty-message"> No daily reports have been entered for this job.</p>
    <?php endif; ?>
</section>
<!-- Assigned Tasks/ESTIMATE -->
<section class="panel">
    <h3>Tasks</h3>
                <p class="section-help">Required tasks and estimated costs for this job.</p>
    <?php if ($estimate): ?>
        <div class="task-table-wrapper">
            <table class="task-entry-table">
                <thead>
                    <tr>
                        <th>Task</th>
                        <th>Description</th>
                        <th>Days</th>
                        <th>Labor Hrs</th>
                        <th>Resources</th>
                        <th>Production</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($tasks && $tasks->num_rows > 0): ?>
                    <?php while ($task = $tasks->fetch_assoc()): ?>
                       <tr>
                            <td><?php echo htmlspecialchars($task['task_name']); ?></td>
                            <td><?php echo htmlspecialchars($task['description']); ?></td>
                            <td><?php echo number_format($task['estimated_duration_days'], 2); ?></td>
                            <td><?php echo number_format($task['estimated_labor_hours'], 2); ?></td>
                                <! -- RESOURCES -->   
                            <td>
                                <?php
                                $stmt = $conn->prepare("
                                    SELECT r.resource_name, tr.quantity, r.unit
                                    FROM task_resources tr
                                    JOIN resources r ON tr.resource_id = r.resource_id
                                    WHERE tr.task_id = ? AND r.resource_type != 'Labor'
                                    ORDER BY r.resource_type, r.resource_name
                                ");
                                $stmt->bind_param("i", $task['task_id']);
                                $stmt->execute();
                                $task_resources = $stmt->get_result();

                                while ($resource = $task_resources->fetch_assoc()) {
                                    echo htmlspecialchars($resource['resource_name']) . " - " .
                                        number_format($resource['quantity'], 0) . " " .
                                        htmlspecialchars($resource['unit']) . "<br>";
                                }
                                ?>
                                </td>

                            <td><?php echo number_format($task['estimated_production_qty'], 2); ?> <?php echo htmlspecialchars(formatUnit($task['production_unit'])); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>

                </tbody>
            </table>
        </div>

<?php if (
    canManageEstimates() &&
    $job['status'] === 'Estimating' &&
    $estimate['approval_status'] === 'Draft'
): ?>

    <!-- ADD TASK -->
    <button type="button" id="showAddTask">+ Add Task</button>

    <div id="addTaskForm" style="display:none;">
        <form method="POST" action="job_view.php?job_id=<?php echo $job_id; ?>">
            <?php
            $resource_result = $conn->query("
                SELECT resource_id, resource_name, resource_type, unit, unit_cost
                FROM resources
                WHERE active = 1
                ORDER BY resource_type, resource_name
            ");

            $resources = [];

            while ($resource = $resource_result->fetch_assoc()) {
                $resources[] = $resource;
            }

            $task_mode = 'job';
            $task_index = 0;
            include '../Assets/task_form.php';
            ?>
        </form>
    </div>

<?php endif; ?>

    <?php else: ?>
        <p>No estimate has been created for this job.</p>
        <form
            method="POST"
            action="job_view.php?job_id=<?php echo $job_id; ?>"
        >
            <div class="form-group">
                <label>Planned Start</label>
                <input type="date" name="start_date" required>
            </div>
            <div class="form-group">
                <label>Estimated Completion</label>
                <input type="date" name="estimated_end_date" required>
            </div>
            <button type="submit" name="create_estimate">
                Create Estimate
            </button>
        </form>
    <?php endif; ?>
</section>
<?php if (canViewEstimateCosts()): ?>

<div class="restricted-section-heading">
    <h2>Job Estimates</h2>
    <p>Estimated costs, actual performance, and task-level cost details.</p>
</div>

<!-- ESTIMATED Summary -->
<section class="panel">
    <h3>Estimate Costs</h3>
    <table>
    <div class="estimate-costs">
        <div><span>Labor</span><strong>$<?php echo number_format($estimated_labor_cost, 2); ?></strong></div>
        <div><span>Equipment</span><strong>$<?php echo number_format($estimated_equipment_cost, 2); ?></strong></div>
        <div><span>Materials</span><strong>$<?php echo number_format($estimated_material_cost, 2); ?></strong></div>
        <div class="estimate-total"><span>Total Estimated Cost</span><strong>$<?php echo number_format($estimated_job_cost, 2); ?></strong></div>
    </div>
    </table>
    </section>

<!-- ESTIMATED VS ACTUAL -->
<section class="panel">
    <h3>Estimated vs. Actual</h3>
    <table>
        <thead>
            <tr>
                <th>Category</th>
                <th>Estimated</th>
                <th>Actual</th>
                <th>Remaining</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Labor Hours</td>
                <td><?php echo number_format($estimated_labor_hours, 2); ?></td>
                <td><?php echo number_format($actual_labor_hours, 2); ?></td>
                <td><?php echo number_format($remaining_labor_hours, 2); ?></td>
            </tr>
            <tr>
                <td>Equipment Hours</td>
                <td><?php echo number_format($estimated_equipment_hours, 2); ?></td>
                <td><?php echo number_format($actual_equipment_hours, 2); ?></td>
                <td><?php echo number_format($remaining_equipment_hours, 2); ?></td>
            </tr>
            <tr>
                <td>Equipment Cost</td>
                <td>$<?php echo number_format($estimated_equipment_cost, 2); ?></td>
                <td>$<?php echo number_format($actual_equipment_cost, 2); ?></td>
                <td>$<?php echo number_format($remaining_equipment_cost, 2); ?></td>
            </tr>
            <tr>
                <td>Material Cost</td>
                <td>$<?php echo number_format($estimated_material_cost, 2); ?></td>
                <td>$<?php echo number_format($actual_material_cost, 2); ?></td>
                <td>$<?php echo number_format($remaining_material_cost, 2); ?></td>
            </tr>
        </tbody>
    </table>
</section>

<!-- ESTIMATE -->
<section class="panel">
    <h3>Estimate Per Task</h3>
    <?php if ($estimate): ?>
        <div class="task-table-wrapper">
            <table class="task-entry-table">
                <thead>
                    <tr>
                        <th>Task</th>
                        <th>Description</th>
                        <th>Days</th>
                        <th>Labor Hrs</th>
                        <th>Labor $</th>
                        <th>Equip Hrs</th>
                        <th>Equip $</th>
                        <th>Material $</th>
                        <th>Qty</th>
                        <th>Unit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($tasks && $tasks->num_rows > 0): ?>
                        <?php $tasks->data_seek(0); ?>
                        <?php while ($task = $tasks->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($task['task_name']); ?></td>
                            <td>
                                <?php echo htmlspecialchars($task['description']); ?>
                            </td>
                            <td>
                                <?php echo number_format($task['estimated_duration_days'], 2); ?>
                            </td>
                            <td>
                                <?php echo number_format($task['estimated_labor_hours'], 2); ?>
                            </td>
                            <td>
                                $<?php echo number_format($task['estimated_labor_cost'], 2); ?>
                            </td>
                            <td>
                                <?php echo number_format($task['estimated_equipment_hours'], 2); ?>
                            </td>
                            <td>
                                $<?php echo number_format($task['estimated_equipment_cost'], 2); ?>
                            </td>
                            <td>
                                $<?php echo number_format($task['estimated_material_cost'], 2); ?>
                            </td>
                            <td>
                                <?php echo number_format($task['estimated_production_qty'], 2); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars(
                                    formatUnit($task['production_unit'])
                                ); ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>

<?php endif; ?>
</main>

<?php require_once '../Assets/footer.php'; ?>

<script>
// Show add task row
const addTaskButton = document.getElementById('showAddTask');
if (addTaskButton) {addTaskButton.addEventListener('click', function () {document.getElementById('addTaskRow').style.display = 'table-row';});}
// Expand / collapse daily reports
document.querySelectorAll('.report-toggle').forEach(function (button) {button.addEventListener('click', function () {
        let reportId = this.dataset.report;
        let details = document.getElementById('report-' + reportId);
        if (details.classList.contains('open')) {details.classList.remove('open');this.innerHTML = this.innerHTML.replace('▴', '▾');
        } else {details.classList.add('open');this.innerHTML = this.innerHTML.replace('▾', '▴');}
    });
});

</script>
</body>
</html>
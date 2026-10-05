<?php
$task_mode = $task_mode ?? 'estimate';
$task_index = $task_index ?? 0;

if ($task_mode === 'estimate') {
    $task_name = 'task_name[]';
    $task_description = 'task_description[]';
    $duration = 'estimated_duration_days[]';
    $production_qty = 'estimated_production_qty[]';
    $production_unit = 'production_unit[]';
    $resource_id = 'resource_id[' . $task_index . '][]';
    $resource_quantity = 'resource_quantity[' . $task_index . '][]';
} else {
    $task_name = 'task_name';
    $task_description = 'task_description';
    $duration = 'estimated_duration_days';
    $production_qty = 'estimated_production_qty';
    $production_unit = 'production_unit';
    $resource_id = 'resource_id[]';
    $resource_quantity = 'resource_quantity[]';
}
?>

<div class="task-row" data-task-index="<?php echo $task_index; ?>">
    <h2 class="task-heading">New Task</h2>

    <div class="task-form">
        <div class="form-grid">
            <div class="form-group">
                <label>Task Type</label>
                <select name="<?php echo $task_name; ?>" required>
                    <option value="">Select Task</option>
                    <option value="Clearing">Clearing</option>
                    <option value="Sediment Control">Sediment Control</option>
                    <option value="Demolition">Demolition</option>
                    <option value="Strip Topsoil">Strip Topsoil</option>
                    <option value="Grading">Grading</option>
                    <option value="Mass Grading">Mass Grading</option>
                    <option value="Cuts to Fill">Cuts to Fill</option>
                    <option value="Storm Drain">Storm Drain</option>
                    <option value="Public Water">Public Water</option>
                    <option value="Water Service">Water Service</option>
                    <option value="Curb and Sidewalk">Curb and Sidewalk</option>
                    <option value="Roadway Subgrade">Roadway Subgrade</option>
                    <option value="Paving">Paving</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div class="form-group">
                <label>Estimated Days</label>
                <input type="number" step="0.01" name="<?php echo $duration; ?>">
            </div>

            <div class="form-group full-width">
                <label>Description</label>
                <input type="text" name="<?php echo $task_description; ?>">
            </div>

            <div class="form-group">
                <label>Production Coverage</label>
                <input type="number" step="0.01" name="<?php echo $production_qty; ?>">
            </div>

            <div class="form-group">
                <label>Production Unit</label>
                <select name="<?php echo $production_unit; ?>">
                    <option value="">Select Unit</option>
                    <option value="LS">Lump Sum</option>
                    <option value="CY">Cubic Yards</option>
                    <option value="LF">Linear Feet</option>
                    <option value="SF">Square Feet</option>
                    <option value="SY">Square Yards</option>
                    <option value="AC">Acres</option>
                    <option value="TON">Tons</option>
                </select>
            </div>
        </div>

        <div class="task-resources">
            <h3>Resources</h3>

            <div class="resource-rows">
                <div class="resource-row">
                    <select name="<?php echo $resource_id; ?>">
                        <option value="">Select Resource</option>

                        <?php foreach ($resources as $resource): ?>
                            <option value="<?php echo $resource['resource_id']; ?>">
                                <?php
                                echo htmlspecialchars(
                                    $resource['resource_name']
                                    . " - $"
                                    . number_format($resource['unit_cost'], 2)
                                    . " / "
                                    . $resource['unit']
                                );
                                ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <input
                        type="number"
                        step="0.01"
                        name="<?php echo $resource_quantity; ?>"
                        placeholder="Quantity">
                </div>
            </div>

            <a href="#" class="add-resource-link">+ Add Resource</a>
        </div>
    </div>

    <?php if ($task_mode === 'estimate'): ?>
        <button type="button" class="finish-task">Done with Task</button>
    <?php else: ?>
        <div class="form-actions">
            <button type="submit" name="add_task">Save Task</button>
            <button type="button" id="cancelAddTask" class="secondary-button">Cancel</button>
        </div>
    <?php endif; ?>
</div>

<script>

// ADD RESOURCE
document.addEventListener("click", function(event) {
    if (event.target.classList.contains("add-resource-link")) {event.preventDefault();
        const taskRow = event.target.closest(".task-row");
        const resourceRows = taskRow.querySelector(".resource-rows");
        const firstResource = resourceRows.querySelector(".resource-row");
        const newResource = firstResource.cloneNode(true);
        newResource .querySelectorAll("input, select") .forEach(function(input) {input.value = "";});
        resourceRows.appendChild(newResource);}});

        // COLLAPSE / REOPEN TASK
document.addEventListener("click", function(event) {if (event.target.classList.contains("finish-task")) {
        const taskRow = event.target.closest(".task-row");
        const taskForm = taskRow.querySelector(".task-form");
        const taskName = taskRow.querySelector('select[name="task_name[]"]');
        if (taskForm.style.display === "none") {
            // Reopen task
            taskForm.style.display = "";
            event.target.textContent = "Done with Task";} else {
            // Collapse task
            taskForm.style.display = "none";
            const taskHeading = taskRow.querySelector(".task-heading");
            if (taskName.value != "") {taskHeading.textContent = taskName.value;} 
            event.target.textContent = "Edit Task";
        }}});

// ADD TASK
document.addEventListener("click", function(event) {
    if (event.target.id === "addTask") {
        const taskRows = document.getElementById("taskRows");
        const firstTask = taskRows.querySelector(".task-row");
        const newTask = firstTask.cloneNode(true);
        const taskIndex = taskRows.querySelectorAll(".task-row").length;

        newTask.dataset.taskIndex = taskIndex;

        // Keep only one resource row
        const resourceRows = newTask.querySelector(".resource-rows");
        const allResourceRows = resourceRows.querySelectorAll(".resource-row");

        for (let i = 1; i < allResourceRows.length; i++) {
            allResourceRows[i].remove();
        }

        // Clear task inputs
        newTask.querySelectorAll("input, select").forEach(function(input) {
            input.value = "";
        });

        // Reset task display
        newTask.querySelector(".task-heading").textContent = "New Task";
        newTask.querySelector(".task-form").style.display = "";
        newTask.querySelector(".finish-task").textContent = "Done with Task";

        // Update resource field indexes
        const resourceSelect = newTask.querySelector('select[name^="resource_id"]');
        const quantityInput = newTask.querySelector('input[name^="resource_quantity"]');

        resourceSelect.name = "resource_id[" + taskIndex + "][]";
        quantityInput.name = "resource_quantity[" + taskIndex + "][]";

        taskRows.appendChild(newTask);
    }
});
// SHOW / HIDE TASK FORM ON JOB VIEW
document.addEventListener("click", function(event) {
    if (event.target.id === "showAddTask") {
        document.getElementById("addTaskForm").style.display = "block";
        event.target.style.display = "none";
    }

    if (event.target.id === "cancelAddTask") {
        document.getElementById("addTaskForm").style.display = "none";
        document.getElementById("showAddTask").style.display = "inline-block";
    }
});
</script>
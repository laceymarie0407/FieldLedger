<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();}
require_once '../Config/database.php';

// GET RESOURCES
$resource_sql = "
    SELECT resource_id, resource_name, resource_type, unit, unit_cost
    FROM resources
    WHERE active = 1
    ORDER BY resource_type, resource_name";
$resource_result = $conn->query($resource_sql);
$resources = [];
while ($row = $resource_result->fetch_assoc()) {$resources[] = $row;}

// CREATE JOB AND ESTIMATE
if (isset($_POST['create_job'])) {
    // Job information
    $job_name = trim($_POST['job_name']);
    $customer_name = trim($_POST['customer_name']);
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $state = trim($_POST['state']);
    $zip_code = trim($_POST['zip_code']);
    $status = trim($_POST['status']);
    $scope_description = trim($_POST['scope_description']);

    // Estimate dates
    $start_date = $_POST['start_date'];
    $estimated_end_date = $_POST['estimated_end_date'];

    // Insert job
    $sql = "INSERT INTO jobs
            (job_name, customer_name, address, city, state, zip_code, scope_description, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssssssss",
        $job_name,
        $customer_name,
        $address,
        $city,
        $state,
        $zip_code,
        $scope_description,
        $status);
    $stmt->execute();
    $job_id = $conn->insert_id;

    // Create job number
    $clean_job_name = preg_replace('/[^A-Za-z0-9]/', '', $job_name);
    $short_job_name = substr($clean_job_name, 0, 15);
    $job_number = $job_id . "-" . $short_job_name;

    // Update job with job number
    $sql = "UPDATE jobs SET job_number = ? WHERE job_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $job_number, $job_id);
    $stmt->execute();

    // Insert estimate
    $sql = "INSERT INTO estimates
            (job_id, start_date, estimated_end_date)
            VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $job_id, $start_date, $estimated_end_date);
    $stmt->execute();
    $estimate_id = $conn->insert_id;

    // INSERT JOB TASKS
    for ($i = 0; $i < count($_POST['task_name']); $i++) {
        $task_name = trim($_POST['task_name'][$i]);
        $task_description = trim($_POST['task_description'][$i]);
        $estimated_duration_days = $_POST['estimated_duration_days'][$i];
        $estimated_production_qty = $_POST['estimated_production_qty'][$i];
        $production_unit = $_POST['production_unit'][$i];

        if ($task_name != "") {
            // Start calculated totals at zero
            $estimated_labor_hours = 0;
            $estimated_labor_cost = 0;
            $estimated_equipment_hours = 0;
            $estimated_equipment_cost = 0;
            $estimated_material_cost = 0;

            // Calculate resource totals for this task
            if (isset($_POST['resource_id'][$i])) {
                for ($r = 0; $r < count($_POST['resource_id'][$i]); $r++) {
                    $resource_id = $_POST['resource_id'][$i][$r];
                    $quantity = $_POST['resource_quantity'][$i][$r];
                    if ($resource_id != "" && $quantity != "") {
                        foreach ($resources as $resource) {
                            if ($resource['resource_id'] == $resource_id) {
                                $resource_cost =$quantity * $resource['unit_cost'];
                                if ($resource['resource_type'] == "Labor") {
                                    $estimated_labor_hours += $quantity;
                                    $estimated_labor_cost += $resource_cost;}
                                if ($resource['resource_type'] == "Equipment") {
                                    $estimated_equipment_hours += $quantity;
                                    $estimated_equipment_cost += $resource_cost;}
                                if ($resource['resource_type'] == "Material") {
                                    $estimated_material_cost += $resource_cost;}
                            }
                        }
                    }
                }
            }


            // Insert task
            $sql = "INSERT INTO job_tasks
                    (estimate_id, task_name, description,
                    estimated_duration_days, estimated_labor_hours,
                    estimated_labor_cost, estimated_equipment_hours,
                    estimated_equipment_cost, estimated_material_cost,
                    estimated_production_qty, production_unit)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("issddddddds",
                $estimate_id,
                $task_name,
                $task_description,
                $estimated_duration_days,
                $estimated_labor_hours,
                $estimated_labor_cost,
                $estimated_equipment_hours,
                $estimated_equipment_cost,
                $estimated_material_cost,
                $estimated_production_qty,
                $production_unit);
            $stmt->execute();
            $task_id = $conn->insert_id;

            // Insert resources connected to task
            if (isset($_POST['resource_id'][$i])) {
                for ($r = 0; $r < count($_POST['resource_id'][$i]); $r++) {
                    $resource_id = $_POST['resource_id'][$i][$r];
                    $quantity = $_POST['resource_quantity'][$i][$r];
                    if ($resource_id != "" && $quantity != "") {
                        $resource_sql = "
                            INSERT INTO task_resources
                            (task_id, resource_id, quantity)
                            VALUES (?, ?, ?)";
                        $resource_stmt = $conn->prepare($resource_sql);
                        $resource_stmt->bind_param(
                            "iid",
                            $task_id,
                            $resource_id,
                            $quantity);
                        $resource_stmt->execute();}
                }
            }
        }
    }
header("Location: job_view.php?job_id=" . $job_id);
    exit();}?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FieldLedger | New Estimate</title>
    <link rel="stylesheet" href="../Assets/styles.css">
</head>

<body>
<?php require_once '../Assets/header.php'; ?>
<main>
    <section>
        <h1>Create Job & Estimate</h1>
        <p>Enter the job information, estimate schedule, tasks, and required resources.</p>
    </section>
    <form method="POST" action="add_estimate.php">
        
    <!-- JOB INFORMATION -->
        <section class="panel">
            <h2>Job Information</h2>
            <div class="form-grid">
                <div class="form-group">
                    <label for="job_name">Job Name</label>
                    <input type="text" id="job_name" name="job_name" required></div>
                <div class="form-group">
                    <label for="customer_name">Customer Name</label>
                    <input type="text" id="customer_name" name="customer_name" required></div>
                <div class="form-group full-width">
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address"></div>
                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" id="city" name="city"></div>
                <div class="form-group">
                    <label for="state">State</label>
                    <input type="text" id="state" name="state"></div>
                <div class="form-group">
                    <label for="zip_code">Zip Code</label>
                    <input type="text" id="zip_code" name="zip_code"></div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="Estimating">Estimating</option>
                        <option value="Active">Active</option>
                    </select></div>
                <div class="form-group full-width">
                    <label for="scope_description">Scope Description</label>
                    <textarea
                        id="scope_description"
                        name="scope_description"
                        rows="4"></textarea></div>
            </div></section>

        <!-- ESTIMATE SCHEDULE -->
        <section class="panel">
            <h2>Estimate Schedule</h2>
            <div class="form-grid">
                <div class="form-group">
                    <label for="start_date">Job Start Date</label>
                    <input
                        type="date"
                        id="start_date"
                        name="start_date"></div>
                <div class="form-group">
                    <label for="estimated_end_date">Estimated End Date</label>
                    <input
                        type="date"
                        id="estimated_end_date"
                        name="estimated_end_date"></div>
            </div></section>

        <!-- JOB TASKS -->
        <section class="panel">
            <h2>Job Tasks</h2>
            <p>Enter each task and the resources required to complete it.</p>

            <div id="taskRows">
                <?php
                $task_mode = 'estimate';
                $task_index = 0;
                include '../Assets/task_form.php';
                ?>
            </div>

            <button type="button" id="addTask">+ Add Task</button>
        </section>
        <div class="form-actions">
        <button type="submit" name="create_job">Save Draft</button>
            <a
                href="jobs.php"
                class="button secondary-button"> Cancel </a></div>
    </form>
</main>
<?php require_once '../Assets/footer.php'; ?>
</body>
</html>
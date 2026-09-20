<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

require_once '../Config/database.php';

if (!isset($_GET['job_id'])) {
    die("No job selected.");
}

$job_id = (int) $_GET['job_id'];

$sql = "
    SELECT *
    FROM jobs
    WHERE job_id = ?
";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $job_id);

$stmt->execute();

$result = $stmt->get_result();

$job = $result->fetch_assoc();

if (!$job) {
    die("Job not found.");
}
// Get estimate for this job
$estimate_sql = "
    SELECT *
    FROM estimates
    WHERE job_id = ?
";

$estimate_stmt = $conn->prepare($estimate_sql);

$estimate_stmt->bind_param("i", $job_id);

$estimate_stmt->execute();

$estimate_result = $estimate_stmt->get_result();

$estimate = $estimate_result->fetch_assoc();

// Get tasks for this estimate

$tasks = null;

if ($estimate) {

    $task_sql = "
        SELECT *
        FROM job_tasks
        WHERE estimate_id = ?
        ORDER BY task_id
    ";

    $task_stmt = $conn->prepare($task_sql);

    $task_stmt->bind_param(
        "i",
        $estimate['estimate_id']
    );

    $task_stmt->execute();

    $tasks = $task_stmt->get_result();

    // Get actual labor hours for this job

$actual_labor_sql = "
    SELECT SUM(le.regular_hours + le.overtime_hours) AS actual_labor_hours
    FROM labor_entries le
    JOIN daily_reports dr
        ON le.report_id = dr.report_id
    WHERE dr.job_id = ?
";

$actual_labor_stmt = $conn->prepare($actual_labor_sql);

$actual_labor_stmt->bind_param("i", $job_id);

$actual_labor_stmt->execute();

$actual_labor_result = $actual_labor_stmt->get_result();

$actual_labor = $actual_labor_result->fetch_assoc();

$actual_labor_hours =
    $actual_labor['actual_labor_hours'] ?? 0;

// Get total estimated labor hours for this job

$estimated_labor_sql = "
    SELECT SUM(estimated_labor_hours) AS estimated_labor_hours
    FROM job_tasks
    WHERE estimate_id = ?
";

$estimated_labor_stmt = $conn->prepare($estimated_labor_sql);

$estimated_labor_stmt->bind_param(
    "i",
    $estimate['estimate_id']
);

$estimated_labor_stmt->execute();

$estimated_labor_result = $estimated_labor_stmt->get_result();

$estimated_labor = $estimated_labor_result->fetch_assoc();

$estimated_labor_hours =
    $estimated_labor['estimated_labor_hours'] ?? 0;

// Remaining labor hours for this job
$remaining_labor_hours =
    $estimated_labor_hours - $actual_labor_hours;
}

function formatUnit($unit) {

    $units = [
        'LS'  => 'Lump Sum',
        'CY'  => 'Cubic Yards',
        'LF'  => 'Linear Feet',
        'SF'  => 'Square Feet',
        'AC'  => 'Acres',
        'SY'  => 'Square Yards',
        'TON' => 'Tons'
    ];

    return $units[$unit] ?? $unit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        FieldLedger | <?php echo htmlspecialchars($job['job_name']); ?>
    </title>

    <link rel="stylesheet"
          href="../Assets/styles.css">

</head>

<body>

<header>

    <h1>FieldLedger</h1>

    <nav>

        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="jobs.php">Jobs</a></li>
            <li><a href="daily_report.php">Daily Report</a></li>
            <li><a href="admin.php">Admin</a></li>
        </ul>

    </nav>

</header>

<main>

<section class="panel">

    <a href="jobs.php">← Back to Jobs</a>

    <div class="job-header">

        <div>
            <h2>
                <?php echo htmlspecialchars($job['job_name']); ?>
            </h2>

            <p>
                <?php echo htmlspecialchars($job['job_number']); ?>
                |
                <?php echo htmlspecialchars($job['customer_name']); ?>
            </p>
        </div>

        <div>
            <span class="status status-<?php echo strtolower($job['status']); ?>">
                <?php echo htmlspecialchars($job['status']); ?>
            </span>
        </div>

    </div>

</section>

<section class="panel">

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
        <?php echo htmlspecialchars($estimate['start_date']); ?>
    </p>

    <p>
        <strong>Estimated Completion:</strong>
        <?php echo htmlspecialchars($estimate['estimated_end_date']); ?>
    </p>

<?php endif; ?>

<?php if ($job['status'] === 'Complete' && !empty($job['completed_date'])): ?>

    <p>
        <strong>Completed:</strong>
        <?php echo date('F j, Y', strtotime($job['completed_date'])); ?>
    </p>

<?php endif; ?>

</section>

<section class="panel">

    <h3>Estimate</h3>

    <?php if ($tasks && $tasks->num_rows > 0): ?>

        <table>

            <thead>
                <tr>
                    <th>Task</th>
                    <th>Duration</th>
                    <th>Labor Hours</th>
                    <th>Equipment Hours</th>
                    <th>Labor Cost</th>
                    <th>Equipment Cost</th>
                    <th>Material Cost</th>
                    <th>Production</th>
                </tr>
            </thead>

            <tbody>

                <?php while ($task = $tasks->fetch_assoc()): ?>

                    <tr>

                        <td>
                            <?php echo htmlspecialchars($task['task_name']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($task['estimated_duration_days']); ?> days
                        </td>

                        <td>
                            <?php echo htmlspecialchars($task['estimated_labor_hours']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($task['estimated_equipment_hours']); ?>
                        </td>

                        <td>
                            $<?php echo number_format($task['estimated_labor_cost'], 2); ?>
                        </td>

                        <td>
                            $<?php echo number_format($task['estimated_equipment_cost'], 2); ?>
                        </td>

                        <td>
                            $<?php echo number_format($task['estimated_material_cost'], 2); ?>
                        </td>

                        <td>
                            <?php
                            echo number_format($task['estimated_production_qty']) . ' ' .
                                htmlspecialchars(formatUnit($task['production_unit']));
                            ?>
                        </td>

                    </tr>

                <?php endwhile; ?>

            </tbody>

        </table>

    <?php else: ?>

        <p>No estimate tasks have been entered for this job.</p>

    <?php endif; ?>

</section>

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

                <td>
                    <?php echo number_format($estimated_labor_hours); ?>
                </td>

                <td>
                    <?php echo number_format($actual_labor_hours); ?>
                </td>

                <td>
                    <?php echo number_format($remaining_labor_hours); ?>
                </td>
            </tr>

        </tbody>

    </table>

</section>

</main>


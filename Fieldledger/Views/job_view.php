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
            $job['zip']
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



</main>


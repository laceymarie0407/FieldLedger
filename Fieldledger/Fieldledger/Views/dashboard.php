<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}
require_once '../Config/database.php';

$current_user_id = $_SESSION['user_id'];

// ---------------------------------------------------------
// CURRENT USER
// ---------------------------------------------------------
$stmt = $conn->prepare("SELECT first_name FROM users WHERE user_id = ?");
$stmt->bind_param("s", $current_user_id);
$stmt->execute();
$current_user = $stmt->get_result()->fetch_assoc();
$first_name = $current_user['first_name'] ?? $current_user_id;

// ---------------------------------------------------------
// DASHBOARD COUNTS
// ---------------------------------------------------------
$active_jobs = $conn->query("SELECT COUNT(*) AS total FROM jobs WHERE status='Active'")->fetch_assoc()['total'];
$pending_jobs_count = $conn->query("
    SELECT COUNT(DISTINCT j.job_id) AS total
    FROM jobs j
    LEFT JOIN estimates e ON e.estimate_id=(
        SELECT e2.estimate_id
        FROM estimates e2
        WHERE e2.job_id=j.job_id
        ORDER BY e2.estimate_id DESC LIMIT 1
    )
    WHERE j.status IN ('Estimating','Pending Client Approval')
       OR e.approval_status='Pending Executive'
")->fetch_assoc()['total'];
$submitted_reports = $conn->query("SELECT COUNT(*) AS total FROM daily_reports WHERE status='Submitted'")->fetch_assoc()['total'];

// ---------------------------------------------------------
// REPORTS AWAITING REVIEW
// ---------------------------------------------------------
$report_jobs = $conn->query("
    SELECT j.job_id,j.job_number,j.job_name,COUNT(dr.report_id) AS report_count
    FROM jobs j
    JOIN daily_reports dr ON j.job_id=dr.job_id
    WHERE j.status='Active' AND dr.status='Submitted'
    GROUP BY j.job_id,j.job_number,j.job_name
    ORDER BY j.job_name
");

// ---------------------------------------------------------
// READY FOR COMPLETION REVIEW
// ---------------------------------------------------------
$completion_jobs = $conn->query("
    SELECT j.job_id,j.job_number,j.job_name
    FROM jobs j
    JOIN estimates e ON e.estimate_id=(
        SELECT e2.estimate_id FROM estimates e2
        WHERE e2.job_id=j.job_id
        ORDER BY e2.estimate_id DESC LIMIT 1
    )
    WHERE j.status='Active'
    AND NOT EXISTS (
        SELECT 1 FROM daily_reports dr
        WHERE dr.job_id=j.job_id AND dr.status='Submitted'
    )
    AND EXISTS (
        SELECT 1 FROM job_tasks jt
        WHERE jt.estimate_id=e.estimate_id
    )
    AND NOT EXISTS (
        SELECT 1 FROM job_tasks jt
        WHERE jt.estimate_id=e.estimate_id
        AND NOT EXISTS (
            SELECT 1 FROM daily_task_entries dte
            JOIN daily_reports dr ON dte.report_id=dr.report_id
            WHERE dte.task_id=jt.task_id AND dr.job_id=j.job_id
        )
    )
    ORDER BY j.job_name
");

// ---------------------------------------------------------
// ACTIVE JOBS
// ---------------------------------------------------------
$jobs = $conn->query("
    SELECT job_id,job_number,job_name,customer_name
    FROM jobs
    WHERE status='Active'
    ORDER BY job_name
");

// ---------------------------------------------------------
// ESTIMATES / PENDING JOBS
// ---------------------------------------------------------
$pending_jobs = $conn->query("
    SELECT j.job_id,j.job_number,j.job_name,j.customer_name,j.status,e.approval_status
    FROM jobs j
    LEFT JOIN estimates e ON e.estimate_id=(
        SELECT e2.estimate_id
        FROM estimates e2
        WHERE e2.job_id=j.job_id
        ORDER BY e2.estimate_id DESC LIMIT 1
    )
    WHERE j.status IN ('Estimating','Pending Client Approval')
       OR e.approval_status='Pending Executive'
    ORDER BY j.job_name
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FieldLedger Dashboard</title>
    <link rel="stylesheet" href="../Assets/styles.css">
</head>
<body>
<?php require_once '../Assets/header.php'; ?>

<main>
    <h2>Welcome, <?php echo htmlspecialchars($first_name); ?>!</h2>
    <p>Here's what's happening across your jobs.</p>

    <!-- DASHBOARD CARDS -->
    <section class="dashboard-cards">
        <div class="card">
            <h3>Active Jobs</h3>
            <p class="number"><?php echo $active_jobs; ?></p>
        </div>
        <div class="card">
            <h3>Pending Jobs</h3>
            <p class="number"><?php echo $pending_jobs_count; ?></p>
        </div>
        <div class="card">
            <h3>Reports Awaiting Review</h3>
            <p class="number"><?php echo $submitted_reports; ?></p>
        </div>
    </section>

    <section class="dashboard-content">

        <!-- NEEDS ATTENTION -->
<div class="panel">
    <h2>Needs Attention</h2>
    <?php if ($report_jobs->num_rows > 0 || $completion_jobs->num_rows > 0): ?>
        <div class="attention-list">
            <?php while ($job = $report_jobs->fetch_assoc()): ?>
                <div class="attention-item">
                    <div>
                        <strong><?php echo htmlspecialchars($job['job_name']); ?></strong>
                        <small><?php echo htmlspecialchars($job['job_number']); ?></small>
                    </div>
                    <span><?php echo $job['report_count']; ?> daily report<?php echo $job['report_count'] != 1 ? 's' : ''; ?> awaiting review</span>
                    <a href="job_view.php?job_id=<?php echo $job['job_id']; ?>" class="button-link">Review</a>
                </div>
            <?php endwhile; ?>

            <?php while ($job = $completion_jobs->fetch_assoc()): ?>
                <div class="attention-item">
                    <div>
                        <strong><?php echo htmlspecialchars($job['job_name']); ?></strong>
                        <small><?php echo htmlspecialchars($job['job_number']); ?></small>
                    </div>
                    <span>Ready for completion review</span>
                    <a href="admin.php" class="button-link">Review</a>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>No jobs currently need attention.</p>
    <?php endif; ?>
</div>

        <!-- ACTIVE JOBS -->
        <div class="panel">
            <h2>Active Jobs</h2>
            <div class="admin-table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Job</th>
                            <th>Customer</th>
                            <th>Progress</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($jobs->num_rows > 0): ?>
                        <?php while ($job = $jobs->fetch_assoc()): ?>
                            <?php
                            $job_id = $job['job_id'];
                            $estimate_stmt = $conn->prepare("SELECT estimate_id FROM estimates WHERE job_id=? ORDER BY estimate_id DESC LIMIT 1");
                            $estimate_stmt->bind_param("i", $job_id);
                            $estimate_stmt->execute();
                            $estimate = $estimate_stmt->get_result()->fetch_assoc();
                            require '../Assets/job_metrics.php';
                            ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($job['job_name']); ?></strong>
                                <br><small><?php echo htmlspecialchars($job['job_number']); ?></small></td>
                                <td><?php echo htmlspecialchars($job['customer_name']); ?></td>
                                <td><?php echo number_format($project_progress, 0); ?>%</td>
                                <td><a href="job_view.php?job_id=<?php echo $job_id; ?>" class="button-link">View</a></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6">No active jobs.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ESTIMATES / PENDING JOBS -->
        <div class="panel">
            <h2>Estimates / Pending Jobs</h2>
            <div class="admin-table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Job</th>
                            <th>Customer</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($pending_jobs->num_rows > 0): ?>
                        <?php while ($job = $pending_jobs->fetch_assoc()): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($job['job_name']); ?></strong><br><small><?php echo htmlspecialchars($job['job_number']); ?></small></td>
                                <td><?php echo htmlspecialchars($job['customer_name']); ?></td>
                                <td>
                                    <?php
                                    if ($job['approval_status']==='Pending Executive') {
                                        echo 'Pending Executive Approval';
                                    } elseif ($job['status']==='Pending Client Approval') {
                                        echo 'Pending Client Approval';
                                    } else {
                                        echo htmlspecialchars($job['status']);
                                    }
                                    ?>
                                    </td>
                                <td><a href="job_view.php?job_id=<?php echo $job['job_id']; ?>" class="button-link">View</a></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="4">No pending estimates.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </section>
</main>

<?php require_once '../Assets/footer.php'; ?>
</body>
</html>
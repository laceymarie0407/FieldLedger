<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}
require_once '../Config/database.php';
$current_user_id = $_SESSION['user_id'];
$message = '';
$error = '';
// ---------------------------------------------------------
// MARK REPORT REVIEWED
// ---------------------------------------------------------
if (isset($_POST['review_report'])) {
    $report_id = (int) $_POST['report_id'];
    $stmt = $conn->prepare("
        SELECT first_name, last_name
        FROM users
        WHERE user_id = ?
    ");
    $stmt->bind_param("s", $current_user_id);
    $stmt->execute();
    $reviewer = $stmt->get_result()->fetch_assoc();
    $reviewed_by = $reviewer
        ? $reviewer['first_name'] . ' ' . $reviewer['last_name']
        : $current_user_id;
    $stmt = $conn->prepare("
        UPDATE daily_reports
        SET status = 'Reviewed',
            reviewed_by = ?,
            reviewed_date = NOW()
        WHERE report_id = ?
          AND status = 'Submitted'
    ");
    $stmt->bind_param("si", $reviewed_by, $report_id);
    $stmt->execute();
    header('Location: admin.php?message=report_reviewed');
    exit();
}
// ---------------------------------------------------------
// MARK JOB COMPLETE
// ---------------------------------------------------------
if (isset($_POST['complete_job'])) {
    $job_id = (int) $_POST['job_id'];
    $stmt = $conn->prepare("SELECT estimate_id FROM estimates WHERE job_id = ? ORDER BY estimate_id DESC LIMIT 1");
    $stmt->bind_param("i", $job_id); $stmt->execute();
    $estimate = $stmt->get_result()->fetch_assoc();
    if (!$estimate) {$error = 'This job cannot be completed because it does not have an estimate.';}
    else {
        $stmt = $conn->prepare("SELECT COUNT(*) AS submitted_count FROM daily_reports WHERE job_id = ? AND status = 'Submitted'");
        $stmt->bind_param("i", $job_id); $stmt->execute();
        $submitted_count = (int) $stmt->get_result()->fetch_assoc()['submitted_count'];
        $stmt = $conn->prepare("SELECT COUNT(*) AS missing_tasks FROM job_tasks jt WHERE jt.estimate_id = ? AND NOT EXISTS (SELECT 1 FROM daily_task_entries dte JOIN daily_reports dr ON dte.report_id = dr.report_id WHERE dte.task_id = jt.task_id AND dr.job_id = ?)");
        $stmt->bind_param("ii", $estimate['estimate_id'], $job_id); $stmt->execute();
        $missing_tasks = (int) $stmt->get_result()->fetch_assoc()['missing_tasks'];
        if ($submitted_count > 0) {$error = 'This job cannot be completed while daily reports are awaiting review.';}
        elseif ($missing_tasks > 0) {$error = 'Every estimated task must appear on at least one daily report before the job can be completed.';}
        else {
            $stmt = $conn->prepare("UPDATE jobs SET status = 'Complete', completed_date = CURDATE() WHERE job_id = ? AND status = 'Active'");
            $stmt->bind_param("i", $job_id); $stmt->execute();
            header('Location: admin.php?message=job_completed'); exit();
        }
    }
}
// ---------------------------------------------------------
// ACTIVATE JOB
// ---------------------------------------------------------
if (isset($_POST['activate_job'])) {$job_id = (int) $_POST['job_id'];
    $stmt = $conn->prepare("
        UPDATE jobs
        SET status = 'Active'
        WHERE job_id = ?
          AND status = 'Estimating'");
    $stmt->bind_param("i", $job_id);
    $stmt->execute();
    header('Location: admin.php?message=job_activated');
    exit();}
// ---------------------------------------------------------
// DECLINE JOB
// ---------------------------------------------------------
if (isset($_POST['decline_job'])) {
    $job_id = (int) $_POST['job_id'];
    $stmt = $conn->prepare("UPDATE jobs SET status = 'Declined' WHERE job_id = ? AND status = 'Estimating'");
    $stmt->bind_param("i", $job_id); $stmt->execute();
    header('Location: admin.php?message=job_declined'); exit();
}
// ---------------------------------------------------------
// ADD EMPLOYEE
// ---------------------------------------------------------
if (isset($_POST['add_employee'])) {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $job_title = trim($_POST['job_title']);
    $hourly_rate = $_POST['hourly_rate'] !== '' ? (float) $_POST['hourly_rate'] : null;
    if ($first_name === '' || $last_name === '') {
        $error = 'First and last name are required.';
    } else {
        $stmt = $conn->prepare("
            INSERT INTO employees
                (first_name, last_name, job_title, hourly_rate, active)
            VALUES (?, ?, ?, ?, 1)
        ");
        $stmt->bind_param(
            "sssd",
            $first_name,
            $last_name,
            $job_title,
            $hourly_rate
        );
        $stmt->execute();
        header('Location: admin.php?message=employee_added');
        exit();}
}
// ---------------------------------------------------------
// UPDATE EMPLOYEE ACTIVE STATUS
// ---------------------------------------------------------
if (isset($_POST['toggle_employee'])) {
    $employee_id = (int) $_POST['employee_id'];
    $new_status = (int) $_POST['new_status'];
    $stmt = $conn->prepare("
        UPDATE employees
        SET active = ?
        WHERE employee_id = ?");
    $stmt->bind_param("ii", $new_status, $employee_id);
    $stmt->execute();
    header('Location: admin.php?message=employee_updated');
    exit();}
// ---------------------------------------------------------
// CREATE USER
// ---------------------------------------------------------
if (isset($_POST['add_user'])) {
    $user_id = trim($_POST['user_id']);
    $employee_id = $_POST['employee_id'] !== ''
        ? (int) $_POST['employee_id']
        : null;
    $first_name = trim($_POST['user_first_name']);
    $last_name = trim($_POST['user_last_name']);
    $email = trim($_POST['email']);
    $role = trim($_POST['role']);
    $password = $_POST['password'];
    if (
        $user_id === '' ||
        $first_name === '' ||
        $last_name === '' ||
        $email === '' ||
        $password === '') {
        $error = 'All required user fields must be completed.';} else {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("
            INSERT INTO users
                (user_id, employee_id, first_name, last_name,
                 email, password_hash, role)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "sisssss",
            $user_id,
            $employee_id,
            $first_name,
            $last_name,
            $email,
            $password_hash,
            $role);
        if ($stmt->execute()) {
            header('Location: admin.php?message=user_added');
            exit();
        } else {$error = 'Unable to create user. Check that the User ID and email are unique.';}
    }
}
// ---------------------------------------------------------
// RESET USER PASSWORD
// ---------------------------------------------------------
if (isset($_POST['reset_password'])) {
    $user_id = $_POST['reset_user_id'];
    $new_password = $_POST['new_password'];
    if ($new_password === '') {
        $error = 'Enter a new password.';
    } else {
        $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("
            UPDATE users
            SET password_hash = ?
            WHERE user_id = ?
        ");
        $stmt->bind_param("ss", $password_hash, $user_id);
        $stmt->execute();
        header('Location: admin.php?message=password_reset');
        exit();
    }
}
// ---------------------------------------------------------
// DELETE USER
// ---------------------------------------------------------
if (isset($_POST['delete_user'])) {
    $delete_user_id = $_POST['delete_user_id'];
    if ($delete_user_id === $current_user_id) {
        $error = 'You cannot delete the account you are currently using.';
    } else {
        $stmt = $conn->prepare("
            DELETE FROM users
            WHERE user_id = ?
        ");
        $stmt->bind_param("s", $delete_user_id);
        $stmt->execute();
        header('Location: admin.php?message=user_deleted');
        exit();
    }
}
// ---------------------------------------------------------
// PAGE MESSAGES
// ---------------------------------------------------------
if (isset($_GET['message'])) {$messages = [
        'report_reviewed' => 'Daily report marked as reviewed.',
        'job_completed' => 'Job marked complete.',
        'job_activated' => 'Job marked active.',
        'job_declined' => 'Job declined.',
        'employee_added' => 'Employee added.',
        'employee_updated' => 'Employee status updated.',
        'user_added' => 'User account created.',
        'password_reset' => 'Password reset.',
        'user_deleted' => 'User account deleted.'];
    $message = $messages[$_GET['message']] ?? '';
}
// ---------------------------------------------------------
// REPORTS WAITING FOR REVIEW
// ---------------------------------------------------------
$stmt = $conn->prepare("
    SELECT
        dr.report_id,
        dr.job_id,
        dr.report_date,
        dr.weather,
        dr.notes,
        dr.status,
        j.job_number,
        j.job_name,
        e.first_name AS foreman_first,
        e.last_name AS foreman_last
    FROM daily_reports dr
    JOIN jobs j
        ON dr.job_id = j.job_id
    LEFT JOIN employees e
        ON dr.foreman_id = e.employee_id
    WHERE dr.status = 'Submitted'
    ORDER BY dr.report_date
");
$stmt->execute();
$submitted_reports = $stmt->get_result();
// ---------------------------------------------------------
// JOB COMPLETION REVIEW
// ---------------------------------------------------------
$completion_jobs = [];
$job_result = $conn->query("SELECT
        j.job_id,
        j.job_number,
        j.job_name,
        j.status,
        e.estimate_id
    FROM jobs j
    LEFT JOIN estimates e
        ON e.estimate_id = (
            SELECT e2.estimate_id
            FROM estimates e2
            WHERE e2.job_id = j.job_id
            ORDER BY e2.estimate_id DESC
            LIMIT 1
        )
    WHERE j.status IN ('Active', 'Estimating')
    ORDER BY j.job_name");
while ($completion_job = $job_result->fetch_assoc()) {
    $completion_job['progress'] = 0;
    $completion_job['submitted_reports'] = 0;
    $completion_job['missing_tasks'] = 0;
    // Calculate progress
    if (!empty($completion_job['estimate_id'])) {$stmt = $conn->prepare("
            SELECT
                jt.task_id,
                jt.estimated_production_qty,
                COALESCE(SUM(dte.production_qty), 0) AS actual_production_qty
            FROM job_tasks jt
            LEFT JOIN daily_task_entries dte
                ON jt.task_id = dte.task_id
            WHERE jt.estimate_id = ?
            GROUP BY
                jt.task_id,
                jt.estimated_production_qty");
        $stmt->bind_param("i",$completion_job['estimate_id']);
        $stmt->execute();
        $progress_result = $stmt->get_result();
        $progress_total = 0;
        $progress_tasks = 0;
        while ($task = $progress_result->fetch_assoc()) {$estimated_qty = (float) $task['estimated_production_qty'];
            if ($estimated_qty > 0) {$task_progress = ((float) $task['actual_production_qty']
                    / $estimated_qty) * 100;
                $progress_total += min($task_progress, 100);
                $progress_tasks++;
            }
        }
        if ($progress_tasks > 0) {$completion_job['progress'] = $progress_total / $progress_tasks;
        }
    }
    // Count reports still awaiting review
    $stmt = $conn->prepare("
        SELECT COUNT(*) AS submitted_count
        FROM daily_reports
        WHERE job_id = ?
          AND status = 'Submitted'");
    $stmt->bind_param("i", $completion_job['job_id']);
    $stmt->execute();
    $completion_job['submitted_reports'] = (int) $stmt->get_result() ->fetch_assoc()['submitted_count'];
    if (!empty($completion_job['estimate_id'])) {
    $stmt = $conn->prepare("
        SELECT COUNT(*) AS missing_tasks
        FROM job_tasks jt
        WHERE jt.estimate_id = ?
        AND NOT EXISTS (
            SELECT 1
            FROM daily_task_entries dte
            JOIN daily_reports dr ON dte.report_id = dr.report_id
            WHERE dte.task_id = jt.task_id AND dr.job_id = ?)
    ");
    $stmt->bind_param("ii", $completion_job['estimate_id'], $completion_job['job_id']);
    $stmt->execute();
    $completion_job['missing_tasks'] = (int) $stmt->get_result()->fetch_assoc()['missing_tasks'];}
    $completion_jobs[] = $completion_job;}
    usort($completion_jobs, function($a, $b) {return $b['progress'] <=> $a['progress'];});
// ---------------------------------------------------------
// WEEKLY HOURS
// ---------------------------------------------------------
$week_start = $_GET['week_start'] ?? date('Y-m-d', strtotime('monday this week'));
$week_end = date('Y-m-d', strtotime($week_start . ' +6 days'));
$stmt = $conn->prepare("
    SELECT
        e.employee_id,
        e.first_name,
        e.last_name,
        SUM(le.regular_hours) AS regular_hours,
        SUM(le.overtime_hours) AS overtime_hours
    FROM labor_entries le
    JOIN employees e
        ON le.employee_id = e.employee_id
    JOIN daily_reports dr
        ON le.report_id = dr.report_id
    WHERE dr.report_date BETWEEN ? AND ?
    GROUP BY
        e.employee_id,
        e.first_name,
        e.last_name
    ORDER BY e.last_name, e.first_name
");
$stmt->bind_param("ss", $week_start, $week_end);
$stmt->execute();
$weekly_hours = $stmt->get_result();
// ---------------------------------------------------------
// EMPLOYEES
// ---------------------------------------------------------
$employees = $conn->query("
    SELECT *
    FROM employees
    ORDER BY active DESC, last_name, first_name
");
// Employee list used by Create User dropdown
$user_employee_options = $conn->query("
    SELECT employee_id, first_name, last_name
    FROM employees
    WHERE active = 1
    ORDER BY last_name, first_name
");
// ---------------------------------------------------------
// USERS
// ---------------------------------------------------------
$users = $conn->query("
    SELECT
        u.*,
        e.job_title
    FROM users u
    LEFT JOIN employees e
        ON u.employee_id = e.employee_id
    ORDER BY u.last_name, u.first_name
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration | FieldLedger</title>
    <link rel="stylesheet" href="../Assets/styles.css">
    <style>
        .manage-user-row {
            display: none;}
        .manage-user-row.open {
            display: table-row;}
        .manage-user-row > td {
            padding: 0;
            border-top: 0;}
        .account-management {
            padding: 12px 18px;
            background: #f1f3f2;
            border-top: 1px solid #d5d9d7;
            border-bottom: 1px solid #d5d9d7;}
        .account-management-title {
            margin-bottom: 8px;
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--text-dark);}
        .account-management-actions {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px 18px;}
        .admin-reset-form {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            margin: 0;}
        .admin-reset-form label {
            margin: 0;
            font-weight: 600;}
        .admin-reset-form input[type="password"] {
            width: 220px;
            max-width: 100%;
            margin: 0;}
        .admin-reset-form button {
            margin: 0;
            white-space: nowrap;}
        .delete-user-form {
            margin: 0;}
        .admin-delete-link {
            padding: 0;
            border: 0;
            background: none;
            color: var(--text);
            font: inherit;
            font-size: 0.9rem;
            text-decoration: underline;
            cursor: pointer;}
        .admin-delete-link:hover {
            color: var(--text-dark);
            background: none;}
        .current-user-note {
            color: var(--text);
            font-size: 0.9rem;
            font-style: italic;}
        @media (max-width: 768px) {
            .account-management-actions,
            .admin-reset-form {
                align-items: flex-start;
                flex-direction: column;}
            .admin-reset-form input[type="password"] {
                width: 100%;}}
    </style>
</head>
<body>
<?php require_once '../Assets/header.php'; ?>
<main>
    <h2>Administration</h2>
    <?php if ($message): ?>
        <div class="admin-message">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="admin-error">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>
    <!-- =====================================================
         REPORTS AWAITING REVIEW
         ===================================================== -->
    <section class="panel">
        <div class="section-heading">
            <div>
                <h3>Reports Awaiting Review</h3>
                <p class="section-help">
                    Submitted daily reports waiting for approval.
                </p>
            </div>
            <span class="admin-count">
                <?php echo $submitted_reports->num_rows; ?> Submitted
            </span>
        </div>
        <?php if ($submitted_reports->num_rows > 0): ?>
            <div class="admin-table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Job</th>
                            <th>Foreman</th>
                            <th>Work Performed</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($report = $submitted_reports->fetch_assoc()): ?>
                        <?php
                        $report_id = $report['report_id'];
                        // Get task names for the report
                        $task_stmt = $conn->prepare("
                            SELECT jt.task_name
                            FROM daily_task_entries dte
                            JOIN job_tasks jt
                                ON dte.task_id = jt.task_id
                            WHERE dte.report_id = ?
                            ORDER BY jt.task_name
                        ");
                        $task_stmt->bind_param("i", $report_id);
                        $task_stmt->execute();
                        $task_result = $task_stmt->get_result();
                        $task_names = [];
                        while ($task = $task_result->fetch_assoc()) {
                            $task_names[] = $task['task_name'];
                        }
                        $work_summary = $task_names
                            ? implode(', ', $task_names)
                            : 'No task activity entered';
                        ?>
                        <tr>
                            <td>
                                <?php echo date(
                                    'm/d/y',
                                    strtotime($report['report_date'])
                                ); ?>
                            </td>
                            <td>
                                <strong>
                                    <?php echo htmlspecialchars($report['job_name']); ?>
                                </strong>
                                <br>
                                <small>
                                    <?php echo htmlspecialchars($report['job_number']); ?>
                                </small>
                            </td>
                            <td>
                                <?php echo htmlspecialchars(
                                    trim(
                                        $report['foreman_first'] . ' ' .
                                        $report['foreman_last']
                                    )
                                ); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($work_summary); ?>
                            </td>
                            <td>
                                <button
                                    type="button"
                                    class="table-button admin-report-toggle"
                                    data-report="<?php echo $report_id; ?>"
                                >
                                    Review ▾
                                </button>
                            </td>
                        </tr>
                        <!-- EXPANDED REPORT -->
                        <tr
                            id="admin-report-<?php echo $report_id; ?>"
                            hidden
                        >
                            <td colspan="5">
                                <div class="report-details open">
                                    <div class="report-detail-header">
                                        <strong>
                                            Daily Report —
                                            <?php echo date(
                                                'F j, Y',
                                                strtotime($report['report_date'])
                                            ); ?>
                                        </strong>
                                        <span>
                                            <strong>Foreman:</strong>
                                            <?php echo htmlspecialchars(
                                                trim(
                                                    $report['foreman_first'] . ' ' .
                                                    $report['foreman_last']
                                                )
                                            ); ?>
                                        </span>
                                        <span>
                                            <strong>Weather:</strong>
                                            <?php echo htmlspecialchars(
                                                $report['weather'] ?? ''
                                            ); ?>
                                        </span>
                                    </div>
                                    <?php if (!empty($report['notes'])): ?>
                                        <div class="report-section">
                                            <h4>Site / Weather Notes</h4>
                                            <p>
                                                <?php echo nl2br(
                                                    htmlspecialchars($report['notes'])
                                                ); ?>
                                            </p>
                                        </div>
                                    <?php endif; ?>
                                    <?php
                                    // Labor for this report
                                    $labor_stmt = $conn->prepare("
                                        SELECT
                                            e.first_name,
                                            e.last_name,
                                            le.regular_hours,
                                            le.overtime_hours
                                        FROM labor_entries le
                                        JOIN employees e
                                            ON le.employee_id = e.employee_id
                                        WHERE le.report_id = ?
                                        ORDER BY e.last_name, e.first_name
                                    ");
                                    $labor_stmt->bind_param("i", $report_id);
                                    $labor_stmt->execute();
                                    $labor_result = $labor_stmt->get_result();
                                    $total_regular = 0;
                                    $total_ot = 0;
                                    ?>
                                    <div class="report-section">
                                        <h4>Labor</h4>
                                        <?php if ($labor_result->num_rows > 0): ?>
                                            <div class="report-labor-list">
                                            <?php while ($labor = $labor_result->fetch_assoc()): ?>
                                                <?php
                                                $total_regular += $labor['regular_hours'];
                                                $total_ot += $labor['overtime_hours'];
                                                ?>
                                                <div class="report-labor-row">
                                                    <strong>
                                                        <?php echo htmlspecialchars(
                                                            $labor['first_name'] . ' ' .
                                                            $labor['last_name']
                                                        ); ?>
                                                    </strong>
                                                    <span>
                                                        Regular:
                                                        <?php echo number_format(
                                                            $labor['regular_hours'],
                                                            2
                                                        ); ?> hrs
                                                    </span>
                                                    <span>
                                                        OT:
                                                        <?php echo number_format(
                                                            $labor['overtime_hours'],
                                                            2
                                                        ); ?> hrs
                                                    </span>
                                                </div>
                                            <?php endwhile; ?>
                                            </div>
                                            <p class="report-labor-total">
                                                <strong>
                                                    Total Labor:
                                                    <?php echo number_format(
                                                        $total_regular + $total_ot,
                                                        2
                                                    ); ?> hrs
                                                </strong>
                                            </p>
                                        <?php else: ?>
                                            <p>No labor entered.</p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="report-section">
                                        <h4>Work Performed</h4>
                                        <?php
                                        $detail_stmt = $conn->prepare("
                                            SELECT
                                                dte.*,
                                                jt.task_name
                                            FROM daily_task_entries dte
                                            JOIN job_tasks jt
                                                ON dte.task_id = jt.task_id
                                            WHERE dte.report_id = ?
                                            ORDER BY jt.task_name
                                        ");
                                        $detail_stmt->bind_param("i", $report_id);
                                        $detail_stmt->execute();
                                        $details = $detail_stmt->get_result();
                                        ?>
                                        <?php if ($details->num_rows > 0): ?>
                                            <?php while ($detail = $details->fetch_assoc()): ?>
                                                <div class="report-task-detail">
                                                    <strong>
                                                        <?php echo htmlspecialchars(
                                                            $detail['task_name']
                                                        ); ?>
                                                    </strong>
                                                    <?php if (!empty($detail['production_qty'])): ?>
                                                        <p>
                                                            Production:
                                                            <?php echo number_format(
                                                                $detail['production_qty'],
                                                                2
                                                            ); ?>
                                                            <?php echo htmlspecialchars(
                                                                $detail['production_unit']
                                                            ); ?>
                                                        </p>
                                                    <?php endif; ?>
                                                    <?php if (!empty($detail['equipment_used'])): ?>
                                                        <p>
                                                            Equipment:
                                                            <?php echo htmlspecialchars(
                                                                $detail['equipment_used']
                                                            ); ?>
                                                            <?php if (!empty($detail['equipment_hours'])): ?>
                                                                —
                                                                <?php echo number_format(
                                                                    $detail['equipment_hours'],
                                                                    2
                                                                ); ?> hrs
                                                            <?php endif; ?>
                                                        </p>
                                                    <?php endif; ?>
                                                    <?php if (!empty($detail['materials_used'])): ?>
                                                        <p>
                                                            Materials:
                                                            <?php echo htmlspecialchars(
                                                                $detail['materials_used']
                                                            ); ?>
                                                            <?php if (!empty($detail['material_quantity'])): ?>
                                                                —
                                                                <?php echo number_format(
                                                                    $detail['material_quantity'],
                                                                    2
                                                                ); ?>
                                                                <?php echo htmlspecialchars(
                                                                    $detail['material_unit']
                                                                ); ?>
                                                            <?php endif; ?>
                                                        </p>
                                                    <?php endif; ?>
                                                    <?php if (!empty($detail['notes'])): ?>
                                                        <p>
                                                            Notes:
                                                            <?php echo nl2br(
                                                                htmlspecialchars($detail['notes'])
                                                            ); ?>
                                                        </p>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <p>No task activity entered.</p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="report-detail-actions">
                                        <a
                                            href="daily_report.php?report_id=<?php echo $report_id; ?>"
                                            class="button-link"
                                        >
                                            Edit Report
                                        </a>
                                        <form method="POST">
                                            <input
                                                type="hidden"
                                                name="report_id"
                                                value="<?php echo $report_id; ?>"
                                            >
                                            <button
                                                type="submit"
                                                name="review_report"
                                            >
                                                Mark Reviewed
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="empty-message">
                No reports are waiting for review.
            </p>
        <?php endif; ?>
    </section>
    <!-- =====================================================
     JOB COMPLETION REVIEW
     ===================================================== -->
<section class="panel">
    <div class="section-heading"><div><h3>Job Completion Review</h3><p class="section-help">Jobs can be completed once all daily reports are reviewed and every estimated task has appeared on a report. Final production may be under or over the original estimate.</p></div></div>
    <div class="admin-table-wrapper"><table>
        <thead><tr><th>Job</th><th>Status</th><th>Progress</th><th>Reports Awaiting Review</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($completion_jobs as $completion_job): ?>
            <tr>
                <td><?php echo htmlspecialchars($completion_job['job_name']); ?><br><small><?php echo htmlspecialchars($completion_job['job_number']); ?></small></td>
                <td><?php echo htmlspecialchars($completion_job['status']); ?></td>
                <td><?php echo number_format($completion_job['progress'], 2); ?>%</td>
                <td><?php echo $completion_job['submitted_reports']; ?></td>
                <td>
                    <?php if ($completion_job['status'] === 'Estimating'): ?>
                        <form method="POST" style="display:inline;"><input type="hidden" name="job_id" value="<?php echo $completion_job['job_id']; ?>"><button type="submit" name="activate_job">Make Active</button></form>
                        <form method="POST" style="display:inline;" onsubmit="return confirm('Decline this job?');"><input type="hidden" name="job_id" value="<?php echo $completion_job['job_id']; ?>"><button type="submit" name="decline_job">Decline</button></form>
                    <?php elseif (empty($completion_job['estimate_id'])): ?><span class="section-help">Estimate required</span>
                    <?php elseif ($completion_job['submitted_reports'] > 0): ?><span class="section-help">Reports need review</span>
                    <?php elseif ($completion_job['missing_tasks'] > 0): ?><span class="section-help"><?php echo $completion_job['missing_tasks']; ?> task(s) missing report</span>
                    <?php else: ?><form method="POST" style="display:inline;" onsubmit="return confirm('Mark this job complete?');"><input type="hidden" name="job_id" value="<?php echo $completion_job['job_id']; ?>"><button type="submit" name="complete_job">Mark Job Complete</button></form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($completion_jobs)): ?><tr><td colspan="5">No open jobs.</td></tr><?php endif; ?>
        </tbody>
    </table></div>
</section>
<!-- =====================================================
         WEEKLY EMPLOYEE HOURS
         ===================================================== -->
    <section class="panel">
        <h3>Weekly Employee Hours</h3>
        <form method="GET" class="admin-week-form">
            <div class="form-group">
                <label for="week_start">Week Of</label>
                <input
                    type="date"
                    id="week_start"
                    name="week_start"
                    value="<?php echo htmlspecialchars($week_start); ?>"
                >
            </div>
            <button type="submit">View Week</button>
        </form>
        <p class="section-help">
            <?php echo date('F j', strtotime($week_start)); ?>
            –
            <?php echo date('F j, Y', strtotime($week_end)); ?>
        </p>
        <div class="admin-table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Regular</th>
                        <th>OT</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $grand_regular = 0;
                $grand_ot = 0;
                ?>
                <?php if ($weekly_hours->num_rows > 0): ?>
                    <?php while ($hours = $weekly_hours->fetch_assoc()): ?>
                        <?php
                        $regular = (float) $hours['regular_hours'];
                        $ot = (float) $hours['overtime_hours'];
                        $grand_regular += $regular;
                        $grand_ot += $ot;
                        ?>
                        <tr>
                            <td>
                                <?php echo htmlspecialchars(
                                    $hours['first_name'] . ' ' .
                                    $hours['last_name']
                                ); ?>
                            </td>
                            <td><?php echo number_format($regular, 2); ?></td>
                            <td><?php echo number_format($ot, 2); ?></td>
                            <td>
                                <strong>
                                    <?php echo number_format($regular + $ot, 2); ?>
                                </strong>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    <tr class="admin-total-row">
                        <td><strong>Total</strong></td>
                        <td><strong><?php echo number_format($grand_regular, 2); ?></strong></td>
                        <td><strong><?php echo number_format($grand_ot, 2); ?></strong></td>
                        <td>
                            <strong>
                                <?php echo number_format(
                                    $grand_regular + $grand_ot,
                                    2
                                ); ?>
                            </strong>
                        </td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td colspan="4">
                            No labor hours entered for this week.
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
    <!-- =====================================================
         EMPLOYEES
         ===================================================== -->
    <section class="panel">
        <div class="section-heading">
            <div>
                <h3>Employees</h3>
                <p class="section-help">
                    Manage employees used for labor reporting.
                </p>
            </div>
            <button type="button" id="showAddEmployee">
                + Add Employee
            </button>
        </div>
        <div id="addEmployeeForm" class="admin-hidden-form">
            <form method="POST" class="form-grid">
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="first_name" required>
                </div>
                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" name="last_name" required>
                </div>
                <div class="form-group">
                    <label>Job Title</label>
                    <input type="text" name="job_title">
                </div>
                <div class="form-group">
                    <label>Hourly Rate</label>
                    <input
                        type="number"
                        step="0.01"
                        min="0"
                        name="hourly_rate"
                    >
                </div>
                <div class="full-width">
                    <button type="submit" name="add_employee">
                        Save Employee
                    </button>
                </div>
            </form>
        </div>
        <div class="admin-table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Job Title</th>
                        <th>Hourly Rate</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($employee = $employees->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <?php echo htmlspecialchars(
                                $employee['first_name'] . ' ' .
                                $employee['last_name']
                            ); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars(
                                $employee['job_title'] ?? ''
                            ); ?>
                        </td>
                        <td>
                            <?php
                            echo $employee['hourly_rate'] !== null
                                ? '$' . number_format($employee['hourly_rate'], 2)
                                : '—';
                            ?>
                        </td>
                        <td>
                            <?php echo $employee['active']
                                ? 'Active'
                                : 'Inactive'; ?>
                        </td>
                        <td>
                            <form method="POST">
                                <input
                                    type="hidden"
                                    name="employee_id"
                                    value="<?php echo $employee['employee_id']; ?>"
                                >
                                <input
                                    type="hidden"
                                    name="new_status"
                                    value="<?php echo $employee['active'] ? 0 : 1; ?>"
                                >
                                <button
                                    type="submit"
                                    name="toggle_employee"
                                    class="secondary-button"
                                >
                                    <?php echo $employee['active']
                                        ? 'Deactivate'
                                        : 'Reactivate'; ?>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </section>
    <!-- =====================================================
         FIELDLEDGER USERS
         ===================================================== -->
    <section class="panel">
        <div class="section-heading">
            <div>
                <h3>FieldLedger Users</h3>
                <p class="section-help">
                    Manage accounts that can sign in to FieldLedger.
                </p>
            </div>
            <button type="button" id="showAddUser">
                + Add User
            </button>
        </div>
        <div id="addUserForm" class="admin-hidden-form">
            <form method="POST" class="form-grid">
                <div class="form-group">
                    <label>User ID</label>
                    <input
                        type="text"
                        name="user_id"
                        placeholder="Example: ReedM"
                        required
                    >
                </div>
                <div class="form-group">
                    <label>Employee</label>
                    <select name="employee_id">
                        <option value="">Not Linked to Employee</option>
                        <?php while ($option = $user_employee_options->fetch_assoc()): ?>
                            <option value="<?php echo $option['employee_id']; ?>">
                                <?php echo htmlspecialchars(
                                    $option['first_name'] . ' ' .
                                    $option['last_name']
                                ); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="user_first_name" required>
                </div>
                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" name="user_last_name" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Role</label>
                    <select name="role" required>
                        <option value="Foreman">Foreman</option>
                        <option value="Estimator">Estimator</option>
                        <option value="Admin">Admin</option>
                        <option value="Executive">Executive</option>
                    </select>
                </div>
                <div class="form-group full-width">
                    <label>Temporary Password</label>
                    <input type="password" name="password" required>
                </div>
                <div class="full-width">
                    <button type="submit" name="add_user">
                        Create User
                    </button>
                </div>
            </form>
        </div>
        <div class="admin-table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Employee</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($user = $users->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <strong>
                                <?php echo htmlspecialchars(
                                    $user['first_name'] . ' ' .
                                    $user['last_name']
                                ); ?>
                            </strong>
                            <br>
                            <small>
                                <?php echo htmlspecialchars($user['user_id']); ?>
                            </small>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($user['email']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($user['role']); ?>
                        </td>
                        <td>
                            <?php echo $user['employee_id']
                                ? htmlspecialchars($user['job_title'] ?? 'Linked')
                                : '—'; ?>
                        </td>
                        <td>
                            <button
                                type="button"
                                class="secondary-button manage-user-button"
                                data-user="<?php echo htmlspecialchars($user['user_id']); ?>"
                            >
                                Manage
                            </button>
                        </td>
                    </tr>
                    <tr
                        class="manage-user-row"
                        id="manage-<?php echo htmlspecialchars($user['user_id']); ?>"
                    >
                        <td colspan="5">
                            <div class="manage-user-content account-management">
                                <div class="account-management-title">
                                    Account Management
                                </div>
                                <div class="account-management-actions">
                                    <form method="POST" class="admin-reset-form">
                                        <input
                                            type="hidden"
                                            name="reset_user_id"
                                            value="<?php echo htmlspecialchars($user['user_id']); ?>"
                                        >
                                        <label for="password-<?php echo htmlspecialchars($user['user_id']); ?>">
                                            New Password
                                        </label>
                                        <input
                                            type="password"
                                            id="password-<?php echo htmlspecialchars($user['user_id']); ?>"
                                            name="new_password"
                                            required
                                        >
                                        <button
                                            type="submit"
                                            name="reset_password"
                                        >
                                            Reset Password
                                        </button>
                                    </form>
                                    <?php if ($user['user_id'] !== $current_user_id): ?>
                                        <form
                                            method="POST"
                                            class="delete-user-form"
                                            onsubmit="return confirmUserDelete('<?php
                                                echo htmlspecialchars(
                                                    $user['user_id'],
                                                    ENT_QUOTES
                                                );
                                            ?>');"
                                        >
                                            <input
                                                type="hidden"
                                                name="delete_user_id"
                                                value="<?php echo htmlspecialchars($user['user_id']); ?>"
                                            >
                                            <button
                                                type="submit"
                                                name="delete_user"
                                                class="admin-delete-link"
                                            >
                                                Delete User
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <span class="current-user-note">
                                            Current account — cannot delete.
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>
<?php require_once '../Assets/footer.php'; ?>
<script>
// Show / hide Add Employee
document.getElementById('showAddEmployee').addEventListener('click', function () {
    document.getElementById('addEmployeeForm').classList.toggle('open');
});
// Show / hide Add User
document.getElementById('showAddUser').addEventListener('click', function () {
    document.getElementById('addUserForm').classList.toggle('open');
});
// Expand submitted reports
document.querySelectorAll('.admin-report-toggle').forEach(function (button) {
    button.addEventListener('click', function () {
        let reportId = this.dataset.report;
        let row = document.getElementById('admin-report-' + reportId);
        row.hidden = !row.hidden;
        if (!row.hidden) {
            this.innerHTML = 'Review ▴';
        } else {
            this.innerHTML = 'Review ▾';
        }
    });
});
// Expand user management
document.querySelectorAll('.manage-user-button').forEach(function (button) {
    button.addEventListener('click', function () {
        let userId = this.dataset.user;
        let row = document.getElementById('manage-' + userId);
        row.classList.toggle('open');
    });
});
// Confirm permanent user deletion
function confirmUserDelete(userId) {
    return confirm(
        'Delete FieldLedger user "' + userId + '"?\n\n' +
        'This permanently deletes the login account. ' +
        'The employee and historical labor records will remain.'
    );
}
</script>
</body>
</html>
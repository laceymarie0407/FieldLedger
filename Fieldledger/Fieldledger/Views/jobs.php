<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

require_once '../Config/database.php';

// VARIABLES
$job_id = -1;
$job_number = "";
$job_name = "";
$customer_name = "";
$address = "";
$city = "";
$state = "";
$zip_code = "";
$scope_description = "";
$status = "";
$completed_date = "";
$edit = false;
$message = "";

// ARCHIVE JOB
if (isset($_POST['archive'])) {
    $job_id = (int) $_POST['job_id'];
    $password = $_POST['archive_password'] ?? "";

    $stmt = $conn->prepare("SELECT password_hash FROM users WHERE user_id = ?");
    $stmt->bind_param("s", $_SESSION['user_id']);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user && password_verify($password, $user['password_hash'])) {
        $stmt = $conn->prepare("UPDATE jobs SET status = 'Archived' WHERE job_id = ?");
        $stmt->bind_param("i", $job_id);
        $stmt->execute();

        header('Location: jobs.php?archived=1');
        exit();
    }

    $message = "Password was incorrect. Job was not archived.";
}

// EDIT JOB
if (isset($_POST['edit'])) {
    $job_id = (int) $_POST['job_id'];
    $edit = true;

    $stmt = $conn->prepare("SELECT * FROM jobs WHERE job_id = ?");
    $stmt->bind_param("i", $job_id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    if ($row) {
        $job_number = $row['job_number'];
        $job_name = $row['job_name'];
        $customer_name = $row['customer_name'];
        $address = $row['address'];
        $city = $row['city'];
        $state = $row['state'];
        $zip_code = $row['zip_code'];
        $scope_description = $row['scope_description'];
        $status = $row['status'];
        $completed_date = $row['completed_date'];
    }
}

// UPDATE JOB
if (isset($_POST['update'])) {
    $job_id = (int) $_POST['job_id'];
    $job_name = trim($_POST['job_name']);
    $customer_name = trim($_POST['customer_name']);
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $state = trim($_POST['state']);
    $zip_code = trim($_POST['zip_code']);
    $scope_description = trim($_POST['scope_description']);
    $status = trim($_POST['status']);
    $completed_date = trim($_POST['completed_date']);

    if ($completed_date == "") {
        $completed_date = null;
    }

    $sql = "UPDATE jobs
            SET job_name = ?, customer_name = ?, address = ?, city = ?,
                state = ?, zip_code = ?, scope_description = ?, status = ?,
                completed_date = ?
            WHERE job_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sssssssssi",
        $job_name,
        $customer_name,
        $address,
        $city,
        $state,
        $zip_code,
        $scope_description,
        $status,
        $completed_date,
        $job_id
    );
    $stmt->execute();

    header('Location: jobs.php');
    exit();
}

// SEARCH / FILTER JOBS
$search = trim($_GET['search'] ?? "");
$status_filter = $_GET['status'] ?? "Current";

$sql = "SELECT * FROM jobs WHERE 1=1";
$params = [];
$types = "";

// Jobs screen only shows operational records.
// Declined and Archived records are retained for Admin/history.
$sql .= " AND status NOT IN ('Declined', 'Archived')";

if ($status_filter != "Current") {
    $sql .= " AND status = ?";
    $params[] = $status_filter;
    $types .= "s";
}

if ($search != "") {
    $sql .= " AND (job_name LIKE ? OR job_number LIKE ? OR customer_name LIKE ?)";
    $searchTerm = "%" . $search . "%";

    for ($i = 0; $i < 3; $i++) {
        $params[] = $searchTerm;
        $types .= "s";
    }
}

$sql .= " ORDER BY job_name";

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$jobs = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobs | FieldLedger</title>
    <link rel="stylesheet" href="../Assets/styles.css">
    <style>
        .archive-job-option {
            margin-top: 18px;
            padding-top: 14px;
            border-top: 1px solid var(--border);
        }

        .archive-link {
            padding: 0;
            border: 0;
            background: none;
            color: var(--text);
            font: inherit;
            font-size: 0.85rem;
            text-decoration: underline;
            cursor: pointer;
        }

        .archive-link:hover {
            color: var(--text-dark);
            background: none;
        }
    </style>
</head>

<body>

<?php require_once '../Assets/header.php'; ?>

<main>

    <section>
        <h2>Jobs</h2>
        <p>Search current jobs by job number, project name, customer, or status.</p>
    </section>

    <?php if ($message != ""): ?>
        <p class="message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <section class="panel">

        <form method="GET" action="jobs.php">
            <div class="job-search-filters">

                <div class="form-group">
                    <label for="search">Search Jobs</label>
                    <input type="text"
                           id="search"
                           name="search"
                           placeholder="Job number, name, or customer"
                           value="<?php echo htmlspecialchars($search); ?>">
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="Current" <?php if ($status_filter == "Current") echo "selected"; ?>>All Current</option>
                        <option value="Estimating" <?php if ($status_filter == "Estimating") echo "selected"; ?>>Estimating</option>
                        <option value="Active" <?php if ($status_filter == "Active") echo "selected"; ?>>Active</option>
                        <option value="On Hold" <?php if ($status_filter == "On Hold") echo "selected"; ?>>On Hold</option>
                        <option value="Complete" <?php if ($status_filter == "Complete") echo "selected"; ?>>Complete</option>
                    </select>
                </div>

            </div>

            <button type="submit">Search</button>
            <a href="jobs.php" class="button secondary-button">Clear</a>
            <a href="add_estimate.php" class="button">Create Job</a>
        </form>

    </section>

    <section class="panel">

        <h2>Job Results</h2>

        <?php if ($jobs->num_rows > 0): ?>

            <table>
                <thead>
                    <tr>
                        <th>Job #</th>
                        <th>Job Name</th>
                        <th>Customer</th>
                        <th>Status</th>
                        <th>View</th>
                        <th>Edit</th>
                    </tr>
                </thead>

                <tbody>

                <?php while ($job = $jobs->fetch_assoc()): ?>

                    <tr>
                        <td><?php echo htmlspecialchars($job['job_number']); ?></td>
                        <td><?php echo htmlspecialchars($job['job_name']); ?></td>
                        <td><?php echo htmlspecialchars($job['customer_name']); ?></td>
                        <td><?php echo htmlspecialchars($job['status']); ?></td>

                        <td>
                            <a href="job_view.php?job_id=<?php echo $job['job_id']; ?>"
                               class="button table-button">View</a>
                        </td>

                        <td>
                            <form method="POST" action="jobs.php">
                                <input type="hidden" name="job_id" value="<?php echo $job['job_id']; ?>">
                                <button type="submit" name="edit" class="table-button">Edit</button>
                            </form>
                        </td>

                    </tr>

                    <?php if ($edit && $job_id == $job['job_id']): ?>

                        <tr class="edit-row">
                            <td colspan="6">

                                <h3>Edit Job: <?php echo htmlspecialchars($job_number); ?></h3>

                                <form method="POST" action="jobs.php">
                                    <input type="hidden" name="job_id" value="<?php echo $job_id; ?>">

                                    <div class="form-group">
                                        <label for="job_name">Job Name</label>
                                        <input type="text" id="job_name" name="job_name"
                                               value="<?php echo htmlspecialchars($job_name); ?>" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="customer_name">Customer Name</label>
                                        <input type="text" id="customer_name" name="customer_name"
                                               value="<?php echo htmlspecialchars($customer_name); ?>" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="edit_status">Status</label>
                                        <select id="edit_status" name="status">
                                            <option value="Estimating" <?php if ($status == "Estimating") echo "selected"; ?>>Estimating</option>
                                            <option value="Active" <?php if ($status == "Active") echo "selected"; ?>>Active</option>
                                            <option value="On Hold" <?php if ($status == "On Hold") echo "selected"; ?>>On Hold</option>
                                            <option value="Complete" <?php if ($status == "Complete") echo "selected"; ?>>Complete</option>
                                            <option value="Declined" <?php if ($status == "Declined") echo "selected"; ?>>Declined</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="completed_date">Completed Date</label>
                                        <input type="date" id="completed_date" name="completed_date"
                                               value="<?php echo htmlspecialchars($completed_date ?? ''); ?>">
                                    </div>

                                    <div class="form-group full-width">
                                        <label for="address">Address</label>
                                        <input type="text" id="address" name="address"
                                               value="<?php echo htmlspecialchars($address); ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="city">City</label>
                                        <input type="text" id="city" name="city"
                                               value="<?php echo htmlspecialchars($city); ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="state">State</label>
                                        <input type="text" id="state" name="state"
                                               value="<?php echo htmlspecialchars($state); ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="zip_code">Zip Code</label>
                                        <input type="text" id="zip_code" name="zip_code"
                                               value="<?php echo htmlspecialchars($zip_code); ?>">
                                    </div>

                                    <div class="form-group full-width">
                                        <label for="scope_description">Scope Description / Notes</label>
                                        <textarea id="scope_description"
                                                  name="scope_description"
                                                  rows="5"><?php echo htmlspecialchars($scope_description); ?></textarea>
                                        <small>If declined, add the date, who declined the estimate, and any feedback provided.</small>
                                    </div>

                                    <div class="full-width">
                                        <button type="submit" name="update">Save Changes</button>
                                        <a href="jobs.php" class="button secondary-button">Cancel</a>
                                    </div>

                                </form>

                                <div class="full-width archive-job-option">
                                    <form method="POST"
                                          action="jobs.php"
                                          onsubmit="return confirmArchive(this, '<?php echo htmlspecialchars($job_name, ENT_QUOTES); ?>');">
                                        <input type="hidden" name="job_id" value="<?php echo $job_id; ?>">
                                        <input type="hidden" name="archive_password" value="">
                                        <button type="submit" name="archive" class="archive-link">Archive this job</button>
                                    </form>
                                </div>



                            </td>
                        </tr>

                    <?php endif; ?>

                <?php endwhile; ?>

                </tbody>
            </table>

        <?php else: ?>
            <p>No jobs found.</p>
        <?php endif; ?>

    </section>

</main>

<?php require_once '../Assets/footer.php'; ?>

<script>
function confirmArchive(form, jobName) {
    if (!confirm('Archive "' + jobName + '"? It will be hidden from normal job searches but kept in FieldLedger.')) {
        return false;
    }

    const password = prompt('Enter your password to confirm archive:');

    if (password === null || password === '') {
        return false;
    }

    form.archive_password.value = password;
    return true;
}
</script>

</body>
</html>

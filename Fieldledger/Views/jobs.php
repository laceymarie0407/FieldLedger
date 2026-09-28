<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

require_once '../Config/database.php';


// -------------------------------------------------
// CRUD VARIABLES
// -------------------------------------------------

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

$add = false;
$edit = false;
$update = false;
$delete = false;


// -------------------------------------------------
// DETERMINE CRUD OPERATION
// -------------------------------------------------

if (isset($_POST['job_id'])) {

    $job_id = (int) $_POST['job_id'];

    $add = isset($_POST['add']);
    $edit = isset($_POST['edit']);
    $update = isset($_POST['update']);
    $delete = isset($_POST['delete']);
}


// -------------------------------------------------
// ADD JOB
// -------------------------------------------------

if ($add) {

    $job_name = $_POST['job_name'];
    $customer_name = $_POST['customer_name'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zip_code = $_POST['zip_code'];
    $scope_description = $_POST['scope_description'];
    $status = $_POST['status'];
    $completed_date = $_POST['completed_date'];

    $addQuery = "
        INSERT INTO jobs
        (
            job_name,
            customer_name,
            address,
            city,
            state,
            zip_code,
            scope_description,
            status,
            completed_date
        )
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, NULLIF(?, ''))
    ";

    $stmt = $conn->prepare($addQuery);

    $stmt->bind_param(
        "sssssssss",
        $job_name,
        $customer_name,
        $address,
        $city,
        $state,
        $zip_code,
        $scope_description,
        $status,
        $completed_date
    );

    $stmt->execute();

    // Reset form variables
    $job_id = -1;
    $job_name = "";
    $customer_name = "";
    $address = "";
    $city = "";
    $state = "";
    $zip_code = "";
    $scope_description = "";
    $status = "";
    $completed_date = "";
}


// -------------------------------------------------
// EDIT JOB
// -------------------------------------------------

else if ($edit) {

    $selQuery = "
        SELECT *
        FROM jobs
        WHERE job_id = ?
    ";

    $stmt = $conn->prepare($selQuery);
    $stmt->bind_param("i", $job_id);
    $stmt->execute();

    // Use a separate result variable for the selected job
    $editResult = $stmt->get_result();
    $row = $editResult->fetch_assoc();

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


// -------------------------------------------------
// SEARCH JOBS
// -------------------------------------------------

$search = '';

if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
}

if ($search !== '') {

    $sql = "
        SELECT *
        FROM jobs
        WHERE job_name LIKE ?
           OR job_number LIKE ?
           OR customer_name LIKE ?
           OR status LIKE ?
        ORDER BY job_name
    ";

    $stmt = $conn->prepare($sql);

    $searchTerm = "%" . $search . "%";

    $stmt->bind_param(
        "ssss",
        $searchTerm,
        $searchTerm,
        $searchTerm,
        $searchTerm
    );

    $stmt->execute();
    $result = $stmt->get_result();

} else {

    $sql = "
        SELECT *
        FROM jobs
        ORDER BY job_name
    ";

    $result = $conn->query($sql);
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>FieldLedger | Jobs</title>

    <link rel="stylesheet" href="../Assets/styles.css">
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

    <section>
        <h2>Jobs</h2>

        <p>
            Search for a job by job number, project name,
            customer, or status.
        </p>
    </section>


    <!-- SEARCH -->
    <section class="panel">

        <form method="GET" action="jobs.php">

            <div class="form-group">

                <label for="search">Search Jobs</label>

                <input
                    type="text"
                    id="search"
                    name="search"
                    placeholder="Job number, name, customer, or status"
                    value="<?php echo htmlspecialchars($search); ?>"
                >

            </div>

            <button type="submit">
                Search
            </button>

            <a href="jobs.php" class="button">
                Clear
            </a>

        </form>

    </section>


    <!-- JOB RESULTS -->
    <section class="panel">

        <h2>Job Results</h2>

        <?php if ($result->num_rows > 0): ?>

            <table>

                <thead>
                    <tr>
                        <th>Job #</th>
                        <th>Job Name</th>
                        <th>Customer</th>
                        <th>Status</th>
                        <th>View</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>

                <tbody>

                <?php while ($job = $result->fetch_assoc()): ?>

                    <tr>

                        <td>
                            <?php echo htmlspecialchars($job['job_number']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($job['job_name']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($job['customer_name']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($job['status']); ?>
                        </td>

                        <td>
                            <a
                                href="job_view.php?job_id=<?php echo $job['job_id']; ?>"
                                class="button"
                            >
                                View Job
                            </a>
                        </td>

                        <td>
                            <form method="POST" action="jobs.php">

                                <input
                                    type="hidden"
                                    name="job_id"
                                    value="<?php echo $job['job_id']; ?>"
                                >

                                <button type="submit" name="edit">
                                    Edit Job
                                </button>

                            </form>
                        </td>

                        <td>
                            <form method="POST" action="jobs.php">

                                <input
                                    type="hidden"
                                    name="job_id"
                                    value="<?php echo $job['job_id']; ?>"
                                >

                                <button type="submit" name="delete">
                                    Delete Job
                                </button>

                            </form>
                        </td>

                    </tr>

                <?php endwhile; ?>

                </tbody>

            </table>

        <?php else: ?>

            <p>No jobs found.</p>

        <?php endif; ?>

    </section>

</main>


<footer>
    <p>&copy; 2026 FieldLedger</p>
</footer>

</body>
</html>
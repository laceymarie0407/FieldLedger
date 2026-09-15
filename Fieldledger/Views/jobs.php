<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

require_once '../Config/database.php';
?>
<?php

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
                    <th></th>
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
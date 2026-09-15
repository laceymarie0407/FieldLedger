<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

require_once '../Config/database.php';

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

        <h2>
            Welcome, <?php echo htmlspecialchars($_SESSION['user_id']); ?>!
        </h2>

        <p>
            This is your dashboard where you can manage your field operations.
        </p>


        <section class="dashboard-cards">

            <div class="card">
                <h3>Active Jobs</h3>
                <p class="number">4</p>
            </div>

            <div class="card">
                <h3>Estimating</h3>
                <p class="number">2</p>
            </div>

            <div class="card">
                <h3>Complete</h3>
                <p class="number">2</p>
            </div>

        </section>


        <section class="dashboard-content">

            <div class="panel">

                <h2>Recent Activity</h2>

                <ul>
                    <li>Recent activity will display here.</li>
                </ul>

            </div>


            <div class="panel">

                <h2>Job Progress Overview</h2>

                <table>

                    <thead>
                        <tr>
                            <th>Job</th>
                            <th>Customer</th>
                            <th>Status</th>
                            <th>Progress</th>
                        </tr>
                    </thead>

                    <tbody>

                        <tr>
                            <td>Oak Ridge Site Development</td>
                            <td>Oak Ridge Properties</td>
                            <td>Active</td>
                            <td>In Progress</td>
                        </tr>

                    </tbody>

                </table>

            </div>

        </section>

    </main>

    <?php require_once '../Assets/footer.php'; ?>

</body>

</html>
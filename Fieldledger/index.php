<?php
session_start();
require_once 'Config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $user_id = trim($_POST['user_id']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);

    $stmt->bind_param("s", $user_id);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password_hash'])) {

            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = $user['role'];

            header('Location: Views/dashboard.php');
            exit();

        } else {
            $error = "Invalid User ID or password.";
        }

    } else {
        $error = "Invalid User ID or password.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>FieldLedger | Login</title>

    <link rel="stylesheet" href="Assets/styles.css">
</head>

<body class="login-page">

    <main class="login-container">

        <section class="login-card">

            <div class="login-header">

                <h1>FieldLedger</h1>

                <p class="tagline">
                    Field Operations & Project Reporting
                </p>

            </div>

            <?php if (!empty($error)): ?>
                <p class="error-message">
                    <?php echo htmlspecialchars($error); ?>
                </p>
            <?php endif; ?>

            <form method="POST" action="">

                <div class="form-group">

                    <label for="user_id">User ID</label>

                    <input
                        type="text"
                        id="user_id"
                        name="user_id"
                        placeholder="Enter your User ID"
                        required
                    >

                </div>

                <div class="form-group">

                    <label for="password">Password</label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Enter your password"
                        required
                    >

                </div>

                <button type="submit" class="login-button">
                    Sign In
                </button>

            </form>

            <div class="login-footer">

                <p>
                    Manage jobs, field reports, estimates,
                    and project performance in one place.
                </p>

            </div>

        </section>

    </main>

</body>

</html>
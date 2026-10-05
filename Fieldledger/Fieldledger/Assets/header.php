<?php
require_once __DIR__ . '/permissions.php';
?>

<header class="site-header">
    <div class="company-brand">
        <img
            src="../Assets/images/SIER Site Grading Excavator Logo.png"
            alt="Sier Site Grading"
            class="company-logo"
        >
    </div>

    <div class="header-company">
        <span>Sier Site Grading</span>
    </div>
</header>

<nav class="main-nav">

    <div class="mobile-nav-bar">
        <span>
            <?= htmlspecialchars($_SESSION['user_id'] ?? 'User') ?>
        </span>

        <button
            type="button"
            class="nav-toggle"
            aria-label="Open navigation"
            aria-expanded="false"
            onclick="toggleNavigation(this)"
        >
            ☰
        </button>
    </div>

    <div class="nav-menu" id="navMenu">

        <ul class="nav-links">
            <li>
                <a href="dashboard.php">Dashboard</a>
            </li>

            <li>
                <a href="jobs.php">Jobs</a>
            </li>

            <?php if (canManageEstimates()): ?>
                <li>
                    <a href="add_estimate.php">New Estimate</a>
                </li>
            <?php endif; ?>

            <?php if (canCreateDailyReports()): ?>
                <li>
                    <a href="daily_report.php">Daily Report</a>
                </li>
            <?php endif; ?>

            <?php if (canViewAdmin()): ?>
                <li>
                    <a href="admin.php">Admin</a>
                </li>
            <?php endif; ?>
        </ul>

        <div class="nav-user">
            <span>
                Logged in as
                <?= htmlspecialchars($_SESSION['user_id'] ?? 'User') ?>
            </span>

            <a href="../logout.php" class="logout-link">
                Logout
            </a>
        </div>

    </div>

</nav>

<script>
function toggleNavigation(button) {
    const menu = document.getElementById('navMenu');

    menu.classList.toggle('open');

    const isOpen = menu.classList.contains('open');
    button.setAttribute('aria-expanded', isOpen);
    button.textContent = isOpen ? '✕' : '☰';
}
</script>
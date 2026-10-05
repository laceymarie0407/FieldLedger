<footer class="site-footer">

    <div class="powered-by">
        <span>Powered by</span>

        <img
            src="../Assets/images/FieldLedger Industrial Wordmark.png"
            alt="FieldLedger"
            class="footer-logo"
        >

        <span class="footer-credit">
            Lacey Roof &copy; 2026
        </span>
    </div>
    <?php if (isset($_SESSION['role']) && ($_SESSION['role'] == 'Executive' || $_SESSION['role'] == 'Admin')): ?>
    <a href="executive.php">Executive Dashboard</a><?php endif; ?>
</footer>
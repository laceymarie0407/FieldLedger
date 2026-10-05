<?php

$estimate_actions_mode = $estimate_actions_mode ?? 'display';

// PROCESS SUBMISSION
if ($estimate_actions_mode === 'process') {

    if (isset($_POST['submit_for_approval']) && $estimate) {

        if (
            $job['status'] === 'Estimating' &&
            $estimate['approval_status'] === 'Draft'
        ) {
            // Update estimate status
            $stmt = $conn->prepare("
                UPDATE estimates
                SET approval_status = 'Pending Executive'
                WHERE estimate_id = ? AND job_id = ?
            ");
            $stmt->bind_param(
                "ii",
                $estimate['estimate_id'],
                $job_id
            );
            $stmt->execute();

            // Update job status
            $stmt = $conn->prepare("
                UPDATE jobs
                SET status = 'Pending Exec Approval'
                WHERE job_id = ?
            ");
            $stmt->bind_param("i", $job_id);
            $stmt->execute();
        }

        header("Location: job_view.php?job_id=$job_id");
        exit();
    }
}

// DISPLAY ACTIONS
if ($estimate_actions_mode === 'display' && $estimate):
?>

    <?php if (
        $job['status'] === 'Estimating' &&
        $estimate['approval_status'] === 'Draft'
    ): ?>

        <div class="estimate-actions">
            <form method="POST" action="job_view.php?job_id=<?php echo $job_id; ?>">
                <button type="submit" name="submit_for_approval">
                    Submit for Executive Approval
                </button>
            </form>
        </div>

    <?php elseif (
        $job['status'] === 'Pending Exec Approval' &&
        $estimate['approval_status'] === 'Pending Executive'
    ): ?>

        <div class="estimate-actions">
            <p class="status-message">
                Pending Executive Approval
            </p>
        </div>

    <?php endif; ?>

<?php endif; ?>
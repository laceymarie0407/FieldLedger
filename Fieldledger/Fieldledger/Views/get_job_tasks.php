<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    exit();
}

require_once '../Config/database.php';

if (!isset($_GET['job_id']) || !is_numeric($_GET['job_id'])) {
    echo json_encode([]);
    exit();
}

$job_id = (int) $_GET['job_id'];

$sql = "
    SELECT
        jt.task_id,
        jt.task_name,
        jt.description,
        jt.production_unit
    FROM estimates e
    JOIN job_tasks jt
        ON jt.estimate_id = e.estimate_id
    WHERE e.job_id = ?
    ORDER BY jt.task_id
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $job_id);
$stmt->execute();

$result = $stmt->get_result();

$tasks = [];

while ($row = $result->fetch_assoc()) {
    $tasks[] = $row;
}

header('Content-Type: application/json');
echo json_encode($tasks);
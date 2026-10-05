<?php

// Default values
$estimated_labor_hours = 0;
$actual_labor_hours = 0;
$remaining_labor_hours = 0;

$estimated_labor_cost = 0;
$actual_labor_cost = 0;
$estimated_job_cost = 0;

$estimated_equipment_hours = 0;
$actual_equipment_hours = 0;
$remaining_equipment_hours = 0;

$estimated_equipment_cost = 0;
$actual_equipment_cost = 0;
$remaining_equipment_cost = 0;

$estimated_material_cost = 0;
$actual_material_cost = 0;
$remaining_material_cost = 0;

$actual_job_cost = 0;

$project_progress = 0;


if ($estimate) {
    // Get estimated totals
    $estimated_sql = "
        SELECT
            SUM(estimated_labor_hours) AS labor_hours,
            SUM(estimated_labor_cost) AS labor_cost,
            SUM(estimated_equipment_hours) AS equipment_hours,
            SUM(estimated_equipment_cost) AS equipment_cost,
            SUM(estimated_material_cost) AS material_cost
        FROM job_tasks
        WHERE estimate_id = ?
    ";

    $estimated_stmt = $conn->prepare($estimated_sql);
    $estimated_stmt->bind_param("i", $estimate['estimate_id']);
    $estimated_stmt->execute();

    $estimated_result = $estimated_stmt->get_result();
    $estimated = $estimated_result->fetch_assoc();

    $estimated_labor_hours = $estimated['labor_hours'] ?? 0;
    $estimated_labor_cost = $estimated['labor_cost'] ?? 0;

    $estimated_equipment_hours = $estimated['equipment_hours'] ?? 0;
    $estimated_equipment_cost = $estimated['equipment_cost'] ?? 0;

    $estimated_material_cost = $estimated['material_cost'] ?? 0;

    $estimated_job_cost =
    $estimated_labor_cost +
    $estimated_equipment_cost +
    $estimated_material_cost;

    // Get actual labor hours and cost
$actual_labor_sql = "
    SELECT
        COALESCE(SUM(le.regular_hours + le.overtime_hours), 0) AS labor_hours,
        COALESCE(SUM((le.regular_hours + le.overtime_hours) * e.hourly_rate), 0) AS labor_cost
    FROM labor_entries le
    JOIN daily_reports dr
        ON le.report_id = dr.report_id
    JOIN employees e
        ON le.employee_id = e.employee_id
    WHERE dr.job_id = ?
";

$actual_labor_stmt = $conn->prepare($actual_labor_sql);
$actual_labor_stmt->bind_param("i", $job_id);
$actual_labor_stmt->execute();

$actual_labor = $actual_labor_stmt->get_result()->fetch_assoc();

$actual_labor_hours = $actual_labor['labor_hours'] ?? 0;
$actual_labor_cost = $actual_labor['labor_cost'] ?? 0;


    // Get actual equipment and material totals
    $actual_task_sql = "
        SELECT
            SUM(dte.equipment_hours) AS equipment_hours,
            SUM(dte.equipment_cost) AS equipment_cost,
            SUM(dte.material_cost) AS material_cost
        FROM daily_task_entries dte
        JOIN daily_reports dr
            ON dte.report_id = dr.report_id
        WHERE dr.job_id = ?
    ";

    $actual_task_stmt = $conn->prepare($actual_task_sql);
    $actual_task_stmt->bind_param("i", $job_id);
    $actual_task_stmt->execute();

    $actual_task_result = $actual_task_stmt->get_result();
    $actual_task = $actual_task_result->fetch_assoc();

    $actual_equipment_hours = $actual_task['equipment_hours'] ?? 0;
    $actual_equipment_cost = $actual_task['equipment_cost'] ?? 0;
    $actual_material_cost = $actual_task['material_cost'] ?? 0;

    $actual_job_cost =
    $actual_labor_cost +
    $actual_equipment_cost +
    $actual_material_cost;

    // Calculate remaining totals
    $remaining_labor_hours =
        $estimated_labor_hours - $actual_labor_hours;

    $remaining_equipment_hours =
        $estimated_equipment_hours - $actual_equipment_hours;

    $remaining_equipment_cost =
        $estimated_equipment_cost - $actual_equipment_cost;

    $remaining_material_cost =
        $estimated_material_cost - $actual_material_cost;


    // Calculate project progress
    $progress_sql = "
        SELECT
            jt.task_id,
            jt.estimated_production_qty,
            COALESCE(SUM(dte.production_qty), 0) AS actual_production_qty
        FROM job_tasks jt
        LEFT JOIN daily_task_entries dte
            ON jt.task_id = dte.task_id
        WHERE jt.estimate_id = ?
        GROUP BY jt.task_id, jt.estimated_production_qty
    ";

    $progress_stmt = $conn->prepare($progress_sql);
    $progress_stmt->bind_param("i", $estimate['estimate_id']);
    $progress_stmt->execute();

    $progress_result = $progress_stmt->get_result();

    $total_progress = 0;
    $task_count = 0;

    while ($progress = $progress_result->fetch_assoc()) {

        if ($progress['estimated_production_qty'] > 0) {

            $task_progress =
                ($progress['actual_production_qty'] /
                $progress['estimated_production_qty']) * 100;

            if ($task_progress > 100) {
                $task_progress = 100;
            }

            $total_progress += $task_progress;
            $task_count++;
        }
    }

    if ($task_count > 0) {
        $project_progress = $total_progress / $task_count;
    }
}

?>
<?php
session_start();
require_once '../Assets/permissions.php';

if (!canViewExecutiveMetrics()) {
    header('Location: dashboard.php');
    exit();
}
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

if ($_SESSION['role'] != 'Executive' && $_SESSION['role'] != 'Admin') {
    header('Location: dashboard.php');
    exit();
}

require_once '../Config/database.php';

// APPROVE QUOTE
if (isset($_POST['approve_quote'])) {
    $job_id = (int) $_POST['job_id'];
    $estimate_id = (int) $_POST['estimate_id'];
    $contract_value = (float) $_POST['contract_value'];

    $stmt = $conn->prepare("UPDATE jobs SET contract_value = ?, status = 'Pending Client Approval' WHERE job_id = ?");
    $stmt->bind_param("di", $contract_value, $job_id);
    $stmt->execute();

    $stmt = $conn->prepare("UPDATE estimates SET approval_status = 'Approved' WHERE estimate_id = ?");
    $stmt->bind_param("i", $estimate_id);
    $stmt->execute();

    header('Location: executive.php');
    exit();}

// EXECUTIVE SUMMARY
$summary_sql = "
SELECT
    (SELECT COUNT(*) FROM jobs WHERE status = 'Active') AS active_jobs,
    (SELECT COALESCE(SUM(contract_value), 0)
     FROM jobs
     WHERE status = 'Active') AS active_contract_value,
    (SELECT COALESCE(SUM(CEIL(estimated_cost / 0.80 / 1000) * 1000), 0)
        FROM (SELECT e.estimate_id,
                SUM(jt.estimated_labor_cost +
                    jt.estimated_equipment_cost +
                    jt.estimated_material_cost) AS estimated_cost
            FROM estimates e
            JOIN job_tasks jt ON e.estimate_id = jt.estimate_id
            WHERE e.approval_status = 'Pending Executive'
            GROUP BY e.estimate_id
        ) pending_exec)
    +
    (SELECT COALESCE(SUM(contract_value), 0)
        FROM jobs
        WHERE status = 'Pending Client Approval'
    ) AS pipeline_value";
$summary = $conn->query($summary_sql)->fetch_assoc();

// PENDING EXECUTIVE APPROVALS
$pending_sql = "
    SELECT j.job_id, j.job_number, j.job_name, j.customer_name, e.estimate_id,
        COALESCE(SUM(jt.estimated_labor_cost + jt.estimated_equipment_cost + jt.estimated_material_cost), 0) AS estimated_cost
    FROM jobs j
    JOIN estimates e ON j.job_id = e.job_id
    JOIN job_tasks jt ON e.estimate_id = jt.estimate_id
    WHERE e.approval_status = 'Pending Executive'
    GROUP BY j.job_id, j.job_number, j.job_name, j.customer_name, e.estimate_id
    ORDER BY j.job_name
";
$pending_result = $conn->query($pending_sql);

// PENDING CLIENT APPROVAL
$pipeline_sql = "
    SELECT job_id, job_number, job_name, customer_name, contract_value
    FROM jobs
    WHERE status = 'Pending Client Approval'
    ORDER BY job_name
";
$pipeline_result = $conn->query($pipeline_sql);

// ACTIVE PROJECT FINANCIALS
$active_sql = "
    SELECT j.job_id, j.job_number, j.job_name, j.contract_value,
        COALESCE(SUM(jt.estimated_labor_cost + jt.estimated_equipment_cost + jt.estimated_material_cost), 0) AS estimated_cost
    FROM jobs j
    LEFT JOIN estimates e ON j.job_id = e.job_id
    LEFT JOIN job_tasks jt ON e.estimate_id = jt.estimate_id
    WHERE j.status = 'Active'
    GROUP BY j.job_id, j.job_number, j.job_name, j.contract_value
    ORDER BY j.job_name
";
$active_result = $conn->query($active_sql);
$chart_labels = [];
$chart_margins = [];

while ($job = $active_result->fetch_assoc()) {
    $profit = $job['contract_value'] - $job['estimated_cost'];
    $margin = $job['contract_value'] > 0 ? ($profit / $job['contract_value']) * 100 : 0;

    $chart_labels[] = $job['job_name'];
    $chart_margins[] = round($margin, 1);}

$active_result = $conn->query($active_sql);
// EXECUTIVE ALERTS - LOW MARGIN
$alert_sql = "
SELECT j.job_id, j.job_name, j.contract_value,
    COALESCE(SUM(jt.estimated_labor_cost + jt.estimated_equipment_cost + jt.estimated_material_cost), 0) AS estimated_cost
FROM jobs j
JOIN estimates e ON j.job_id = e.job_id
JOIN job_tasks jt ON e.estimate_id = jt.estimate_id
WHERE j.status = 'Active'
GROUP BY j.job_id, j.job_name, j.contract_value
HAVING ((j.contract_value - estimated_cost) / j.contract_value) * 100 < 10
ORDER BY ((j.contract_value - estimated_cost) / j.contract_value) * 100
";
$alert_result = $conn->query($alert_sql);

// ---------------------------------------------------------
// COMPLETED JOB HISTORY - LAST 6 MONTHS
// ---------------------------------------------------------
$history_sql = "
    SELECT j.job_id,j.contract_value,j.completed_date,
        COALESCE(SUM(jt.estimated_labor_cost+jt.estimated_equipment_cost+jt.estimated_material_cost),0) AS estimated_cost
    FROM jobs j
    LEFT JOIN estimates e ON e.estimate_id=(
        SELECT e2.estimate_id FROM estimates e2
        WHERE e2.job_id=j.job_id ORDER BY e2.estimate_id DESC LIMIT 1
    )
    LEFT JOIN job_tasks jt ON jt.estimate_id=e.estimate_id
    WHERE j.status='Complete'
    AND j.completed_date>=DATE_FORMAT(DATE_SUB(CURDATE(),INTERVAL 5 MONTH),'%Y-%m-01')
    GROUP BY j.job_id,j.contract_value,j.completed_date
    ORDER BY j.completed_date
";
$history_result = $conn->query($history_sql);

$months = [];
for ($i=5;$i>=0;$i--) {
    $key=date('Y-m',strtotime("-$i months"));
    $months[$key]=[
        'label'=>date('M',strtotime($key.'-01')),
        'jobs'=>0,'profit'=>0,'estimated'=>0,'actual'=>0
    ];
}

while ($job=$history_result->fetch_assoc()) {
    $job_id=(int)$job['job_id'];

    $stmt=$conn->prepare("
        SELECT COALESCE(SUM((le.regular_hours+le.overtime_hours)*emp.hourly_rate),0) AS labor_cost
        FROM labor_entries le
        JOIN daily_reports dr ON le.report_id=dr.report_id
        JOIN employees emp ON le.employee_id=emp.employee_id
        WHERE dr.job_id=?
    ");
    $stmt->bind_param("i",$job_id);
    $stmt->execute();
    $labor_cost=(float)$stmt->get_result()->fetch_assoc()['labor_cost'];

    $stmt=$conn->prepare("
        SELECT COALESCE(SUM(dte.equipment_cost),0) AS equipment_cost,
               COALESCE(SUM(dte.material_cost),0) AS material_cost
        FROM daily_task_entries dte
        JOIN daily_reports dr ON dte.report_id=dr.report_id
        WHERE dr.job_id=?
    ");
    $stmt->bind_param("i",$job_id);
    $stmt->execute();
    $actual=$stmt->get_result()->fetch_assoc();

    $actual_cost=$labor_cost+(float)$actual['equipment_cost']+(float)$actual['material_cost'];
    $profit=(float)$job['contract_value']-$actual_cost;
    $month=date('Y-m',strtotime($job['completed_date']));

    if (isset($months[$month])) {
        $months[$month]['jobs']++;
        $months[$month]['profit']+=$profit;
        $months[$month]['estimated']+=(float)$job['estimated_cost'];
        $months[$month]['actual']+=$actual_cost;
    }
}

$history_labels=array_column($months,'label');
$history_jobs=array_column($months,'jobs');
$history_profit=array_column($months,'profit');
$history_estimated=array_column($months,'estimated');
$history_actual=array_column($months,'actual');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FieldLedger | Executive Dashboard</title>
    <link rel="stylesheet" href="../Assets/styles.css">
</head>
<body>
<?php include '../Assets/header.php'; ?>

<main>
<div class="executive-page">
<section class="executive-hero">
    <img src="../Assets/images/SIER Site Grading Excavator Logo.png" class="executive-logo-bg" alt="">
    <div class="executive-hero-content">
        <div>
            <span class="executive-label">SIER SITE GRADING</span>
            <h1>Executive Dashboard</h1>
            <p>Financial performance, pipeline and project outlook.</p>
        </div>
    </div>
</section>

    <section class="dashboard-cards">
        <div class="card"><h3>Active Jobs</h3>
            <div class="number"><?php echo $summary['active_jobs']; ?></div>
        </div>
        <div class="card"><h3>Active Contract Value</h3>
            <div class="number">$<?php echo number_format($summary['active_contract_value'], 0); ?></div>
        </div>
        <div class="card"><h3>Pipeline Value</h3>
            <div class="number">$<?php echo number_format($summary['pipeline_value'], 0); ?></div>
        </div>
    </section>

<section class="panel executive-chart">
    <h2>Projected Margin by Active Project</h2>
    <div class="chart-container">
        <canvas id="marginChart"></canvas>
    </div>
</section>
<section class="executive-history-grid">
    <div class="panel executive-chart">
        <h2>Closed Jobs & Profit</h2>
        <p class="section-help">Last 6 months</p>
        <div class="chart-container"><canvas id="profitChart"></canvas></div>
    </div>

    <div class="panel executive-chart">
        <h2>Estimated vs Actual Cost</h2>
        <p class="section-help">Completed jobs — last 6 months</p>
        <div class="chart-container"><canvas id="costChart"></canvas></div>
    </div>
</section>
    <section class="panel">
        <h2>Pending Executive Approval</h2>
        <?php if ($pending_result->num_rows > 0): ?>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Job</th>
                        <th>Customer</th>
                        <th>Estimated Cost</th>
                        <th>Suggested Quote</th>
                        <th>Approved Quote</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($quote = $pending_result->fetch_assoc()):
                    $suggested_quote = ceil(($quote['estimated_cost'] * 1.20) / 1000) * 1000;
                ?>
                    <tr>
                        <td>
                            <a href="job_view.php?job_id=<?php echo $quote['job_id']; ?>">
                                <strong><?php echo htmlspecialchars($quote['job_name']); ?></strong>
                            </a><br>
                            <small><?php echo htmlspecialchars($quote['job_number']); ?></small>
                        </td>
                        <td><?php echo htmlspecialchars($quote['customer_name']); ?></td>
                        <td>$<?php echo number_format($quote['estimated_cost'], 0); ?></td>
                        <td><strong>$<?php echo number_format($suggested_quote, 0); ?></strong></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="job_id" value="<?php echo $quote['job_id']; ?>">
                                <input type="hidden" name="estimate_id" value="<?php echo $quote['estimate_id']; ?>">
                                <div class="quote-input">
                                    <input type="text" name="contract_value" value="$<?php echo number_format($suggested_quote, 0); ?>">
                                </div>
                            </td>
                        <td>
                                <button type="submit" name="approve_quote">Approve</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
            <p>No estimates are currently waiting for Executive approval.</p>
        <?php endif; ?>
    </section>


    <section class="panel">
        <h2>Pending Client Approval</h2>

        <?php if ($pipeline_result->num_rows > 0): ?>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Job</th>
                        <th>Customer</th>
                        <th>Quoted Value</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($job = $pipeline_result->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <a href="job_view.php?job_id=<?php echo $job['job_id']; ?>"><?php echo htmlspecialchars($job['job_name']); ?></a><br>
                            <small><?php echo htmlspecialchars($job['job_number']); ?></small>
                        </td>
                        <td><?php echo htmlspecialchars($job['customer_name']); ?></td>
                        <td><strong>$<?php echo number_format($job['contract_value'], 0); ?></strong></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
            <p>No jobs are currently waiting for client approval.</p>
        <?php endif; ?>
    </section>

    <section class="panel">
        <h2>Active Project Financials</h2>

        <?php if ($active_result->num_rows > 0): ?>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Job</th>
                        <th>Estimated Cost</th>
                        <th>Contract Value</th>
                        <th>Projected Profit</th>
                        <th>Margin</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($job = $active_result->fetch_assoc()):
                    $profit = $job['contract_value'] - $job['estimated_cost'];
                    $margin = $job['contract_value'] > 0 ? ($profit / $job['contract_value']) * 100 : 0;?>
                    <tr class="<?php echo $margin < 10 ? 'margin-alert' : ''; ?>">
                        <td><a href="job_view.php?job_id=<?php echo $job['job_id']; ?>"><?php echo htmlspecialchars($job['job_name']); ?></a></td>
                        <td>$<?php echo number_format($job['estimated_cost'], 0); ?></td>
                        <td>$<?php echo number_format($job['contract_value'], 0); ?></td>
                        <td>$<?php echo number_format($profit, 0); ?></td>
                        <td><?php echo number_format($margin, 1); ?>%</td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
            <p>No active projects found.</p>
        <?php endif; ?>
    </section>
</main>
</div>
<?php include '../Assets/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const marginChart = document.getElementById('marginChart');

new Chart(marginChart, {
    type: 'bar',
    data: {labels: <?php echo json_encode($chart_labels); ?>,
        datasets: [{label: 'Projected Margin %',
            data: <?php echo json_encode($chart_margins); ?>,
            borderWidth: 1}]
    },
    options: {
responsive: true,
        maintainAspectRatio: false,
        plugins: {legend: {display: false}},
        scales: {y: {beginAtZero: true,
                ticks: {callback: function(value) {return value + '%';
                    }
                }
            }
        }
    }
});
const historyLabels=<?php echo json_encode($history_labels); ?>;

new Chart(document.getElementById('profitChart'),{
    data:{
        labels:historyLabels,
        datasets:[
            {
                type:'bar',
                label:'Jobs Completed',
                data:<?php echo json_encode($history_jobs); ?>,
                yAxisID:'y'
            },
            {
                type:'line',
                label:'Final Profit',
                data:<?php echo json_encode($history_profit); ?>,
                yAxisID:'y1',
                tension:.25
            }
        ]
    },
    options:{
        responsive:true,
        maintainAspectRatio:false,
        scales:{
            y:{
                beginAtZero:true,
                ticks:{precision:0},
                title:{display:true,text:'Jobs Completed'}
            },
            y1:{
                beginAtZero:true,
                position:'right',
                grid:{drawOnChartArea:false},
                ticks:{callback:value=>'$'+Number(value).toLocaleString()},
                title:{display:true,text:'Final Profit'}
            }
        }
    }
});

new Chart(document.getElementById('costChart'),{
    type:'bar',
    data:{
        labels:historyLabels,
        datasets:[
            {label:'Estimated Cost',data:<?php echo json_encode($history_estimated); ?>},
            {label:'Actual Cost',data:<?php echo json_encode($history_actual); ?>}
        ]
    },
    options:{
        responsive:true,
        maintainAspectRatio:false,
        scales:{
            y:{
                beginAtZero:true,
                ticks:{callback:value=>'$'+Number(value).toLocaleString()}
            }
        }
    }
});
</script>
</body>
</html>
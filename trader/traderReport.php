<?php
session_start();
// require_once '../connection.php';

$dailyData = $weeklyData = $monthlyData = $dailyLabels = $weeklyLabels = $monthlyLabels = $topSelling = [];

// if (isset($_SESSION['trader_id'])) {
//     $trader_id = $_SESSION['trader_id'];

    // Daily Sales
    $sqlDaily = "SELECT TO_CHAR(P.Payment_Date, 'Dy') AS DayLabel, TO_CHAR(P.Payment_Date, 'YYYY-MM-DD') AS DayDate,
                        SUM(P.Total_Amount) AS Total
                 FROM PAYMENT P
                 JOIN ORDER_TABLE O ON O.Order_ID = P.fk1_Order_ID
                 JOIN PRODUCT_ORDER PO ON PO.Order_ID = O.Order_ID
                 JOIN PRODUCT PR ON PR.Product_ID = PO.Product_ID
                 JOIN SHOP S ON S.Shop_ID = PR.fk1_Shop_ID
                 WHERE S.Trader_ID = :trader_id AND P.Payment_Date >= TRUNC(SYSDATE - 6)
                 GROUP BY TO_CHAR(P.Payment_Date, 'Dy'), TO_CHAR(P.Payment_Date, 'YYYY-MM-DD')
                 ORDER BY TO_CHAR(P.Payment_Date, 'YYYY-MM-DD')";
    // $stmt = oci_parse($conn, $sqlDaily);
    // oci_bind_by_name($stmt, ':trader_id', $trader_id);
    // oci_execute($stmt);
    // while ($row = oci_fetch_assoc($stmt)) {
    //     $dailyLabels[] = $row['DAYDATE'];
    //     $dailyData[] = (float)$row['TOTAL'];
    // }
    // oci_free_statement($stmt);

    // Weekly Sales
    $sqlWeekly = "SELECT 'Week ' || TO_CHAR(P.Payment_Date, 'IW') AS WeekLabel, SUM(P.Total_Amount) AS Total
                  FROM PAYMENT P
                  JOIN ORDER_TABLE O ON O.Order_ID = P.fk1_Order_ID
                  JOIN PRODUCT_ORDER PO ON PO.Order_ID = O.Order_ID
                  JOIN PRODUCT PR ON PR.Product_ID = PO.Product_ID
                  JOIN SHOP S ON S.Shop_ID = PR.fk1_Shop_ID
                  WHERE S.Trader_ID = :trader_id AND P.Payment_Date >= TRUNC(SYSDATE, 'IW') - 28
                  GROUP BY TO_CHAR(P.Payment_Date, 'IW')
                  ORDER BY TO_CHAR(P.Payment_Date, 'IW')";
    // $stmt = oci_parse($conn, $sqlWeekly);
    // oci_bind_by_name($stmt, ':trader_id', $trader_id);
    // oci_execute($stmt);
    // while ($row = oci_fetch_assoc($stmt)) {
    //     $weeklyLabels[] = $row['WEEKLABEL'];
    //     $weeklyData[] = (float)$row['TOTAL'];
    // }
    // oci_free_statement($stmt);

    // Monthly Sales
    $sqlMonthly = "SELECT TO_CHAR(P.Payment_Date, 'Month') AS MonthLabel, SUM(P.Total_Amount) AS Total
                   FROM PAYMENT P
                   JOIN ORDER_TABLE O ON O.Order_ID = P.fk1_Order_ID
                   JOIN PRODUCT_ORDER PO ON PO.Order_ID = O.Order_ID
                   JOIN PRODUCT PR ON PR.Product_ID = PO.Product_ID
                   JOIN SHOP S ON S.Shop_ID = PR.fk1_Shop_ID
                   WHERE S.Trader_ID = :trader_id AND P.Payment_Date >= ADD_MONTHS(TRUNC(SYSDATE, 'MM'), -5)
                   GROUP BY TO_CHAR(P.Payment_Date, 'Month'), TO_CHAR(P.Payment_Date, 'MM')
                   ORDER BY TO_CHAR(P.Payment_Date, 'MM')";
    // $stmt = oci_parse($conn, $sqlMonthly);
    // oci_bind_by_name($stmt, ':trader_id', $trader_id);
    // oci_execute($stmt);
    // while ($row = oci_fetch_assoc($stmt)) {
    //     $monthlyLabels[] = trim($row['MONTHLABEL']);
    //     $monthlyData[] = (float)$row['TOTAL'];
    // }
    // oci_free_statement($stmt);

    // Top Selling Products
    $sqlTopSelling = "SELECT P.Product_Name, SUM(PO.Quantity) AS Units_Sold
                      FROM PRODUCT_ORDER PO
                      JOIN PRODUCT P ON P.Product_ID = PO.Product_ID
                      JOIN SHOP S ON S.Shop_ID = P.fk1_Shop_ID
                      WHERE S.Trader_ID = :trader_id
                      GROUP BY P.Product_Name
                      ORDER BY Units_Sold DESC FETCH FIRST 5 ROWS ONLY";
//     $stmt = oci_parse($conn, $sqlTopSelling);
//     oci_bind_by_name($stmt, ':trader_id', $trader_id);
//     oci_execute($stmt);
//     while ($row = oci_fetch_assoc($stmt)) {
//         $topSelling[] = $row;
//     }
//     oci_free_statement($stmt);
// }
?>
<?php include 'navbar.php'; ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('toggleBtn');
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function () {
            document.body.classList.toggle('sidebar-collapsed');
        });
    }
});
</script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Reports - CoolCarters</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    :root {
        --sidebar-width: 240px;
        --collapsed-width: 70px;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f4f6f9;
        margin: 0;
        padding-left: var(--sidebar-width);
        padding-top: 80px;
        padding-bottom: 80px;
        transition: padding-left 0.3s ease;
    }

    body.sidebar-collapsed {
        padding-left: var(--collapsed-width);
    }

    main {
        padding: 1rem;
        padding-bottom: 5rem;
    }

    canvas#reportChart {
        max-width: 100%;
        height: auto !important;
    }
    .socials a {
        font-size: 1.2rem;
        color: #858796;
        margin-left: 1rem;
        transition: color 0.2s;
    }

    .socials a:hover {
        color: #4e73df;
    }

    @media (max-width: 768px) {
        body {
            padding-left: 0;
            padding-top: 70px;
        }


        .flex.gap-4.mb-4 {
            flex-direction: column;
        }
    }
</style>

</head>
<body>
<main class="px-4 pb-28">

    <h1 class="text-2xl font-semibold text-blue-600 mb-6">Sales Reports</h1>

    <div class="flex gap-4 mb-4">
        <button id="btnDaily" class="px-4 py-2 bg-blue-600 text-white rounded">Daily</button>
        <button id="btnWeekly" class="px-4 py-2 bg-gray-300 text-black rounded">Weekly</button>
        <button id="btnMonthly" class="px-4 py-2 bg-gray-300 text-black rounded">Monthly</button>
    </div>

    <div class="bg-white p-6 rounded shadow mb-6">
        <canvas id="reportChart"></canvas>
    </div>

    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Top Selling Products</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-left text-gray-600">
                    <tr>
                        <th class="p-2">Product Name</th>
                        <th class="p-2 text-center">Units Sold</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($topSelling as $item): ?>
                    <tr class="border-t hover:bg-gray-50">
                        <td class="p-2"><?= htmlspecialchars($item['PRODUCT_NAME']) ?></td>
                        <td class="p-2 text-center font-medium text-blue-600"><?= htmlspecialchars($item['UNITS_SOLD']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php include 'traderFooter.php'; ?>

<script>
    const dailyData = <?= json_encode($dailyData) ?>;
    const dailyLabels = <?= json_encode($dailyLabels) ?>;
    const weeklyData = <?= json_encode($weeklyData) ?>;
    const weeklyLabels = <?= json_encode($weeklyLabels) ?>;
    const monthlyData = <?= json_encode($monthlyData) ?>;
    const monthlyLabels = <?= json_encode($monthlyLabels) ?>;

    const ctx = document.getElementById('reportChart').getContext('2d');
    let chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: dailyLabels,
            datasets: [{
                label: 'Daily Sales',
                data: dailyData,
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.1)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    function updateChart(label, labels, data) {
        chart.data.labels = labels;
        chart.data.datasets[0].label = label;
        chart.data.datasets[0].data = data;
        chart.update();
    }

    function setActive(activeId) {
        document.querySelectorAll('button').forEach(btn => btn.className = 'px-4 py-2 bg-gray-300 text-black rounded');
        document.getElementById(activeId).className = 'px-4 py-2 bg-blue-600 text-white rounded';
    }

    document.getElementById('btnDaily').addEventListener('click', () => {
        updateChart('Daily Sales', dailyLabels, dailyData);
        setActive('btnDaily');
    });

    document.getElementById('btnWeekly').addEventListener('click', () => {
        updateChart('Weekly Sales', weeklyLabels, weeklyData);
        setActive('btnWeekly');
    });

    document.getElementById('btnMonthly').addEventListener('click', () => {
        updateChart('Monthly Sales', monthlyLabels, monthlyData);
        setActive('btnMonthly');
    });
</script>

</body>
</html>

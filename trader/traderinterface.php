<?php
session_start();
// require_once '../connection.php';

// $trader_id = $_SESSION['trader_id'];

// Shop count
$sqlShopCount = "SELECT COUNT(*) AS Shop_Count FROM SHOP WHERE Trader_ID = :trader_id";
// $stmt = oci_parse($conn, $sqlShopCount);
// oci_bind_by_name($stmt, ':trader_id', $trader_id);
// oci_execute($stmt);
// $shopData = oci_fetch_assoc($stmt);
// oci_free_statement($stmt);
$shopCount = $shopData['SHOP_COUNT'] ?? 0;

// Sales today
$sqlSales = "
    SELECT NVL(SUM(P.Total_Amount), 0) AS Total
    FROM PAYMENT P
    JOIN ORDER_TABLE O ON P.fk1_Order_ID = O.Order_ID
    JOIN PRODUCT_ORDER PO ON PO.Order_ID = O.Order_ID
    JOIN PRODUCT PR ON PR.Product_ID = PO.Product_ID
    JOIN SHOP S ON PR.fk1_Shop_ID = S.Shop_ID
    WHERE S.Trader_ID = :trader_id
      AND TRUNC(P.Payment_Date) = TRUNC(SYSDATE)
";
// $stmt = oci_parse($conn, $sqlSales);
// oci_bind_by_name($stmt, ':trader_id', $trader_id);
// oci_execute($stmt);
// $salesData = oci_fetch_assoc($stmt);
// oci_free_statement($stmt);
$totalSalesToday = $salesData['TOTAL'] ?? 0;

// Average rating
$sqlRating = "
    SELECT ROUND(AVG(R.Rating), 2) AS Avg_Rating
    FROM REVIEW R
    JOIN PRODUCT P ON R.fk1_Product_ID = P.Product_ID
    JOIN SHOP S ON P.fk1_Shop_ID = S.Shop_ID
    WHERE S.Trader_ID = :trader_id
";
// $stmt = oci_parse($conn, $sqlRating);
// oci_bind_by_name($stmt, ':trader_id', $trader_id);
// oci_execute($stmt);
// $ratingData = oci_fetch_assoc($stmt);
// oci_free_statement($stmt);
$avgRating = $ratingData['AVG_RATING'] ?? 'N/A';

// Units sold
$sqlSold = "
    SELECT NVL(SUM(1), 0) AS Units_Sold
    FROM PRODUCT_ORDER PO
    JOIN PRODUCT P ON PO.Product_ID = P.Product_ID
    JOIN SHOP S ON P.fk1_Shop_ID = S.Shop_ID
    WHERE S.Trader_ID = :trader_id
";
// $stmt = oci_parse($conn, $sqlSold);
// oci_bind_by_name($stmt, ':trader_id', $trader_id);
// oci_execute($stmt);
// $soldData = oci_fetch_assoc($stmt);
// oci_free_statement($stmt);
$totalUnitsSold = $soldData['UNITS_SOLD'] ?? 0;
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
  <meta charset="UTF-8" />
  <title>Trader Dashboard - CoolCarters</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
  <style>
    :root {
      --primary: #4e73df;
      --secondary: #2e59d9;
      --light: #f8f9fc;
      --dark: #343a40;
      --text: #858796;
      --bg: #fff;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background: var(--light);
      color: var(--dark);
      margin: 0;
    }

    .main-content {
      margin-left: 240px;
      padding: 80px 1rem 100px 1rem;
      transition: margin-left 0.3s ease;
    }

    body.sidebar-collapsed .main-content {
      margin-left: 70px;
    }

    @media (max-width: 768px) {
      .main-content {
        margin-left: 0;
        padding: 70px 1rem 100px 1rem;
      }
    }
  </style>
</head>

<body>

<main class="main-content">
  <h1 class="text-2xl font-semibold text-blue-600 mb-6">Welcome, Trader!</h1>

  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- Shop Count -->
    <a href="traderShop.php" class="block bg-white p-6 rounded shadow hover:shadow-lg transition">
      <h2 class="text-lg font-semibold text-gray-700 mb-2">Your Shops</h2>
      <p class="text-4xl font-bold text-blue-600"><?= $shopCount ?></p>
      <p class="text-sm text-gray-500 mt-1">Click to manage shops</p>
    </a>

    <!-- Sales Today -->
    <a href="traderReport.php" class="block bg-white p-6 rounded shadow hover:shadow-lg transition">
      <h2 class="text-lg font-semibold text-gray-700 mb-2">Today's Sales</h2>
      <p class="text-4xl font-bold text-green-600">£<?= number_format($totalSalesToday, 2) ?></p>
      <p class="text-sm text-gray-500 mt-1">Click for sales report</p>
    </a>

    <!-- Avg Rating -->
    <a href="traderFeedbacks.php" class="block bg-white p-6 rounded shadow hover:shadow-lg transition">
      <h2 class="text-lg font-semibold text-gray-700 mb-2">Avg. Rating</h2>
      <p class="text-4xl font-bold text-yellow-500">⭐ <?= $avgRating ?></p>
      <p class="text-sm text-gray-500 mt-1">Click to view feedback</p>
    </a>

    <!-- Units Sold -->
    <a href="traderReport.php" class="block bg-white p-6 rounded shadow hover:shadow-lg transition">
      <h2 class="text-lg font-semibold text-gray-700 mb-2">Units Sold</h2>
      <p class="text-4xl font-bold text-indigo-600"><?= $totalUnitsSold ?></p>
      <p class="text-sm text-gray-500 mt-1">Click to see top sellers</p>
    </a>
  </div>
</main>

<?php include 'traderFooter.php'; ?>
</body>
</html>

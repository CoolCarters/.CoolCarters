<?php
session_start();
require_once '../connection.php';

$feedbacks = [];
$avgRatings = [];

if (isset($_SESSION['trader_id'])) {
    $trader_id = $_SESSION['trader_id'];

    // Individual feedbacks
    $sql = "
        SELECT
            R.Review_ID AS fid,
            U.First_Name || ' ' || U.Last_Name AS customer_name,
            U.Email AS email,
            R.Rating AS rating,
            TO_CHAR(R.Review_ID + 100, 'FM999') AS submitted_at
        FROM REVIEW R
        JOIN PRODUCT P ON R.fk1_Product_ID = P.Product_ID
        JOIN SHOP S ON P.fk1_Shop_ID = S.Shop_ID
        JOIN USER_TABLE U ON R.fk2_User_ID = U.User_ID
        WHERE S.Trader_ID = :trader_id
        ORDER BY R.Review_ID DESC
    ";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ':trader_id', $trader_id);
    oci_execute($stmt);
    while ($row = oci_fetch_assoc($stmt)) {
        $feedbacks[] = $row;
    }
    oci_free_statement($stmt);

    // Average rating per product
    $avgSql = "
        SELECT 
            P.Product_Name,
            ROUND(AVG(R.Rating), 2) AS Avg_Rating
        FROM REVIEW R
        JOIN PRODUCT P ON R.fk1_Product_ID = P.Product_ID
        JOIN SHOP S ON P.fk1_Shop_ID = S.Shop_ID
        WHERE S.Trader_ID = :trader_id
        GROUP BY P.Product_Name
        ORDER BY P.Product_Name
    ";
    $avgStmt = oci_parse($conn, $avgSql);
    oci_bind_by_name($avgStmt, ':trader_id', $trader_id);
    oci_execute($avgStmt);
    while ($row = oci_fetch_assoc($avgStmt)) {
        $avgRatings[] = $row;
    }
    oci_free_statement($avgStmt);
}
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
    <title>Trader Feedback - CoolCarters</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 80px 1rem 100px 1rem;
        }

        .main-content {
            margin-left: 240px;
            transition: margin-left 0.3s ease;
        }

        body.sidebar-collapsed .main-content {
            margin-left: 70px;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding-top: 70px;
                padding-bottom: 90px;
            }
        }

        .fa-star.fas {
            color: #facc15;
        }

        .fa-star.far {
            color: #e5e7eb;
        }

        input#searchInput {
            max-width: 400px;
        }
    </style>
</head>
<body>

<main class="main-content">
    <h1 class="text-2xl font-semibold text-blue-600 mb-6">Customer Feedback</h1>

    <!-- Average Ratings -->
    <div class="bg-white rounded shadow mb-6 p-4">
        <h2 class="text-lg font-semibold mb-3 text-gray-700">Average Ratings per Product</h2>
        <div class="overflow-x-auto">
            <table class="w-full table-auto text-sm">
                <thead class="bg-blue-50 text-blue-900">
                    <tr>
                        <th class="p-3 text-left">Product Name</th>
                        <th class="p-3 text-center">Avg. Rating</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($avgRatings)): ?>
                        <?php foreach ($avgRatings as $avg): ?>
                            <tr class="border-t">
                                <td class="p-3"><?= htmlspecialchars($avg['PRODUCT_NAME']) ?></td>
                                <td class="p-3 text-center"><?= htmlspecialchars($avg['AVG_RATING']) ?> ⭐</td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="2" class="p-4 text-center text-gray-500">No ratings yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Search & Feedback Table -->
    <input type="text" id="searchInput" placeholder="Search..." class="w-full mb-4 p-2 border border-gray-300 rounded shadow-sm focus:ring focus:ring-blue-200">

    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full table-auto text-sm">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="p-3 text-left">ID</th>
                    <th class="p-3 text-left">Customer</th>
                    <th class="p-3 text-center">Rating</th>
                    <th class="p-3 text-center">Date</th>
                </tr>
            </thead>
            <tbody id="feedbackTable">
                <?php if (!empty($feedbacks)): ?>
                    <?php foreach ($feedbacks as $f): ?>
                        <tr class="border-t hover:bg-gray-50">
                            <td class="p-3"><?= htmlspecialchars($f['fid']) ?></td>
                            <td class="p-3">
                                <div class="font-medium"><?= htmlspecialchars($f['customer_name']) ?></div>
                                <div class="text-xs text-gray-500"><?= htmlspecialchars($f['email']) ?></div>
                            </td>
                            <td class="p-3 text-center">
                                <div class="flex justify-center">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fa-star <?= $i <= $f['rating'] ? 'fas' : 'far' ?>"></i>
                                    <?php endfor; ?>
                                </div>
                            </td>
                            <td class="p-3 text-center"><?= htmlspecialchars($f['submitted_at']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4" class="p-4 text-center text-gray-500">No feedback available.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<script>
    document.getElementById('searchInput').addEventListener('input', function () {
        const filter = this.value.toLowerCase();
        document.querySelectorAll('#feedbackTable tr').forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });
</script>

<?php include 'traderFooter.php'; ?>
</body>
</html>

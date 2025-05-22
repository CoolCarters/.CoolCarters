<?php
session_start();
require_once '../connection.php';

$errors = [];
$products = [];

// Get trader ID from session
$trader_id = $_SESSION['trader_id'] ?? 0;
$categories = ['Butchers', 'Greengrocer', 'Fishmonger', 'Bakery', 'Delicatessen'];

// Fetch products for this trader if trader ID is set
if ($trader_id) {
    $query = "
        SELECT 
            p.Product_ID,
            p.Product_Name,
            p.Price,
            p.Stock,
            p.Minimum_Order,
            p.Maximum_Order,
            p.Category,
            p.Allergy_Warning,
            NVL(SUM(po.Quantity), 0) AS Units_Sold
        FROM PRODUCT p
        JOIN SHOP s ON p.fk1_Shop_ID = s.Shop_ID
        LEFT JOIN PRODUCT_ORDER po ON po.Product_ID = p.Product_ID
        WHERE s.Trader_ID = :trader_id
        GROUP BY 
            p.Product_ID, 
            p.Product_Name, 
            p.Price, 
            p.Stock,
            p.Minimum_Order, 
            p.Maximum_Order, 
            p.Category,
            p.Allergy_Warning
        ORDER BY p.Product_ID DESC
    ";

    $statement = oci_parse($conn, $query);
    oci_bind_by_name($statement, ':trader_id', $trader_id);

    if (oci_execute($statement)) {
        while ($row = oci_fetch_assoc($statement)) {
            $products[] = [
                'pid'              => $row['PRODUCT_ID'],
                'product_name'     => $row['PRODUCT_NAME'],
                'price'            => $row['PRICE'],
                'stock'            => $row['STOCK'],
                'min_order'        => $row['MINIMUM_ORDER'],
                'max_order'        => $row['MAXIMUM_ORDER'],
                'category'         => $row['CATEGORY'],
                'allergy_warnings' => $row['ALLERGY_WARNING'],
                'units_sold'       => $row['UNITS_SOLD'],
                'image_url'        => '../images/products/default.jpg' // Placeholder
            ];
        }
    } else {
        $e = oci_error($statement);
        $errors[] = "❌ SQL Error: " . $e['message'];
    }
    oci_free_statement($statement);
}
?>
<?php include 'navbar.php'; ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('toggleBtn');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                document.body.classList.toggle('sidebar-collapsed');
            });
        }
    });
</script>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Trader Products</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4e73df;
            --secondary: #2e59d9;
            --light: #f8f9fc;
            --dark: #343a40;
            --text: #858796;
            --bg: #fff;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--light);
            color: var(--dark);
            overflow-x: hidden;
            min-height: 100vh;
            padding-bottom: 80px;
        }

        /* Sidebar Responsive */
        .sidebar-collapsed .main-content {
            margin-left: 60px !important;
        }

        .main-content {
            margin-left: 240px;
            padding: 5rem 2rem 2rem;
            transition: margin-left 0.3s ease;
        }

        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .content-header h1 {
            font-size: 1.5rem;
            color: var(--dark);
        }

        .add-product {
            padding: 0.6rem 1rem;
            background: var(--primary);
            color: var(--bg);
            text-decoration: none;
            border-radius: 4px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .add-product:hover {
            background: var(--secondary);
        }

        .search-filter {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .search-bar {
            flex: 1;
            max-width: 300px;
            position: relative;
        }

        .search-bar input {
            width: 100%;
            padding: 0.5rem 1rem;
            padding-left: 2.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 0.9rem;
        }

        .search-bar i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text);
        }

        .filter-button {
            padding: 0.5rem 1rem;
            background: var(--bg);
            border: 1px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .products-table-wrapper {
            overflow-x: auto;
        }

        .products-table {
            width: 100%;
            min-width: 1000px;
            background: var(--bg);
            border-radius: 4px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .products-table th,
        .products-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .products-table th {
            background: var(--light);
            font-weight: 500;
            color: var(--dark);
        }

        .products-table img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 4px;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .action-buttons button {
            padding: 0.4rem 0.7rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            color: var(--bg);
            font-size: 0.85rem;
        }

        .action-buttons .edit {
            background: var(--primary);
        }

        .action-buttons .delete {
            background: #dc3545;
        }

        /* Mobile Styles */
        @media (max-width: 768px) {
            body {
                padding-left: 0 !important;
            }

            .main-content {
                margin-left: 0 !important;
                padding: 1rem;
            }

            .content-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .add-product {
                width: 100%;
                justify-content: center;
                font-size: 1rem;
                margin-top: 0.5rem;
            }

            .search-filter {
                flex-direction: column;
                width: 100%;
            }

            .search-bar,
            .filter-button {
                width: 100%;
            }

            .products-table {
                min-width: 100%;
                font-size: 0.9rem;
            }

            .products-table th,
            .products-table td {
                padding: 0.75rem;
            }

            .action-buttons {
                flex-direction: column;
            }

            .action-buttons button {
                width: 100%;
                font-size: 0.8rem;
            }
        }
    </style>
</head>

<main class="main-content">
    <div class="content-header">
        <h1>Products</h1>
        <a href="traderAddProduct.php" class="add-product">
            <i class="fas fa-plus"></i>
            Add Product
        </a>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="error" style="padding:1rem;color:red;">
            <?php foreach ($errors as $err): ?>
                <p><?= htmlspecialchars($err) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form class="search-filter" method="get">
        <div class="search-bar">
            <i class="fas fa-search"></i>
            <input type="text" name="search" placeholder="Search products...">
        </div>
        <select name="category" class="filter-button">
            <option value="">All Category</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></option>
            <?php endforeach; ?>
        </select>
    </form>

    <div class="products-table-wrapper">
        <table class="products-table">
            <thead>
                <tr>
                    <th>PID</th>
                    <th>Product Name</th>
                    <th>Image</th>
                    <th>Stock</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Min Order</th>
                    <th>Max Order</th>
                    <th>Allergy Info</th>
                    <th>Units Sold</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($products)): ?>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?= htmlspecialchars($product['pid']) ?></td>
                            <td><?= htmlspecialchars($product['product_name']) ?></td>
                            <td><img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['product_name']) ?>"></td>
                            <td><?= htmlspecialchars($product['stock']) ?></td>
                            <td>£<?= number_format($product['price'], 2) ?></td>
                            <td><?= htmlspecialchars($product['category']) ?></td>
                            <td><?= htmlspecialchars($product['min_order']) ?></td>
                            <td><?= htmlspecialchars($product['max_order']) ?></td>
                            <td><?= htmlspecialchars($product['allergy_warnings']) ?></td>
                            <td><?= htmlspecialchars($product['units_sold']) ?></td>
                            <td class="action-buttons">
                                <button class="edit" type="button" onclick="editProduct(<?= $product['pid'] ?>)"><i class="fas fa-edit"></i></button>
                                <button class="delete" type="button" onclick="deleteProduct(<?= $product['pid'] ?>)"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="11" style="text-align:center;">No products found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>
<?php include 'traderFooter.php'; ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.querySelector('.search-bar input');
        const categoryFilter = document.querySelector('select[name="category"]');

        function filterProducts() {
            const searchTerm = searchInput.value.toLowerCase();
            const categoryValue = categoryFilter.value.toLowerCase();
            document.querySelectorAll('.products-table tbody tr').forEach(row => {
                const productName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const productCategory = row.querySelector('td:nth-child(6)').textContent.toLowerCase();
                const matchesSearch = productName.includes(searchTerm);
                const matchesCategory = categoryValue === '' || productCategory === categoryValue;
                row.style.display = (matchesSearch && matchesCategory) ? '' : 'none';
            });
        }
        searchInput.addEventListener('input', filterProducts);
        categoryFilter.addEventListener('change', filterProducts);
    });

    function editProduct(productId) {
        window.location.href = `editProduct.php?id=${productId}`;
    }

    function deleteProduct(productId) {
        if (confirm('Are you sure you want to delete this product?')) {
            window.location.href = `deleteProduct.php?id=${productId}`;
        }
    }
</script>
</body>

</html>
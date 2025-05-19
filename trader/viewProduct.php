<?php
session_start();

// Static data for demonstration
$products = [
    [
        'pid' => 1,
        'product_name' => 'Fresh Chicken Breast',
        'image_url' => '../images/products/chicken.jpg',
        'stock' => 100,
        'price' => 12.99,
        'category' => 'Meat',
        'min_order' => 1,
        'max_order' => 10,
        'allergy_warnings' => 'None'
    ],
    [
        'pid' => 2,
        'product_name' => 'Premium Rice 5kg',
        'image_url' => '../images/products/rice.jpg',
        'stock' => 150,
        'price' => 15.99,
        'category' => 'Grocery',
        'min_order' => 1,
        'max_order' => 5,
        'allergy_warnings' => 'Processed in a facility that handles nuts'
    ],
    [
        'pid' => 3,
        'product_name' => 'Fresh Salmon Fillet',
        'image_url' => '../images/products/salmon.jpg',
        'stock' => 50,
        'price' => 18.99,
        'category' => 'Fish',
        'min_order' => 1,
        'max_order' => 8,
        'allergy_warnings' => 'Contains fish'
    ],
    [
        'pid' => 4,
        'product_name' => 'Artisan Sourdough Bread',
        'image_url' => '../images/products/bread.jpg',
        'stock' => 30,
        'price' => 6.99,
        'category' => 'Bakery',
        'min_order' => 1,
        'max_order' => 5,
        'allergy_warnings' => 'Contains gluten'
    ],
    [
        'pid' => 5,
        'product_name' => 'Premium Caviar 50g',
        'image_url' => '../images/products/caviar.jpg',
        'stock' => 20,
        'price' => 89.99,
        'category' => 'Delicacy',
        'min_order' => 1,
        'max_order' => 3,
        'allergy_warnings' => 'Contains fish roe'
    ]
];

$categories = ['Meat', 'Grocery', 'Fish', 'Bakery', 'Delicacy'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
      :root {
            --primary: #4e73df;
            --secondary: #2e59d9;
            --light: #f8f9fc;
            --dark: #343a40;
            --text: #858796;
            --bg: #fff;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Poppins', sans-serif;
            background: var(--light);
            color: var(--dark);
            overflow-x: hidden;
            min-height: 100vh;
            padding-bottom: 60px;
        }
        
        /* Main Content */
        .main-content {
            margin-left: 240px;
            padding: 5rem 2rem 2rem;
        }
        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        .content-header h1 {
            font-size: 1.5rem;
            color: var(--dark);
        }
        .add-product {
            padding: 0.5rem 1rem;
            background: var(--primary);
            color: var(--bg);
            text-decoration: none;
            border-radius: 4px;
            transition: 0.3s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .add-product:hover {
            background: var(--secondary);
        }

        /* Search and Filter */
        .search-filter {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
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
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
        }

        /* Products Table */
        .products-table {
            width: 100%;
            background: var(--bg);
            border-radius: 4px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
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
            padding: 0.25rem 0.5rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: 0.3s;
            color: var(--bg);
        }
        .action-buttons button:hover {
            opacity: 0.8;
        }
        .action-buttons .edit {
            background: var(--primary);
        }
        .action-buttons .delete {
            background: #dc3545;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: 0.3s;
            }
            .sidebar.active {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
            .top-nav {
                left: 0;
            }
            .search-filter {
                flex-direction: column;
            }
            .search-bar {
                max-width: 100%;
            }
        }
                /* Footer */
        footer {
            position: fixed;
            bottom: 0;
            left: 240px;
            right: 0;
            background: var(--bg);
            padding: .75rem 2rem;
            box-shadow: 0 -2px 4px rgba(0,0,0,.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 10;
            transition: left 0.3s ease;
        }
        .socials a {
            font-size: 1.2rem;
            color: var(--text);
            margin-left: 1rem;
            transition: color .2s;
        }
        .socials a:hover {
            color: var(--primary);
        }
        footer p {
            font-size: .85rem;
            color: var(--text);
        }
    </style>
</head>
<body>
    <!-- Include the navbar -->
     <?php include 'navbar.php'; ?>

    <!-- Main Content -->
    <main class="main-content">
        <div class="content-header">
            <h1>Products</h1>
            <a href="traderAddProduct.php" class="add-product">
                <i class="fas fa-plus"></i>
                Add Product
            </a>
        </div>

        <form class="search-filter">
            <div class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" name="search" placeholder="Search products...">
            </div>
            <select name="category" class="filter-button">
                <option value="">All Category</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= htmlspecialchars($category) ?>">
                        <?= htmlspecialchars($category) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>

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
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?= htmlspecialchars($product['pid']) ?></td>
                        <td><?= htmlspecialchars($product['product_name']) ?></td>
                        <td>
                            <img src="<?= htmlspecialchars($product['image_url']) ?>" 
                                 alt="<?= htmlspecialchars($product['product_name']) ?>">
                        </td>
                        <td><?= htmlspecialchars($product['stock']) ?></td>
                        <td>$<?= number_format($product['price'], 2) ?></td>
                        <td><?= htmlspecialchars($product['category']) ?></td>
                        <td><?= htmlspecialchars($product['min_order']) ?></td>
                        <td><?= htmlspecialchars($product['max_order']) ?></td>
                        <td><?= htmlspecialchars($product['allergy_warnings']) ?></td>
                        <td class="action-buttons">
                            <button class="edit" onclick="editProduct(<?= $product['pid'] ?>)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="delete" onclick="deleteProduct(<?= $product['pid'] ?>)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
    <!-- Footer -->
    <footer>
        <p>&copy; 2025 CoolCarter. All rights reserved</p>
        <div class="socials">
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fas fa-times"></i></a>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('toggleBtn');
            const sidebar = document.getElementById('sidebar');
            const main = document.getElementById('main');
            const footer = document.querySelector('footer');
            
            toggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('collapsed');
                main.classList.toggle('expanded');
                footer.classList.toggle('expanded');
            });
        });
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('toggleBtn');
        const sidebar = document.getElementById('sidebar');
        const main = document.getElementById('main');
        const footer = document.querySelector('footer');
        
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            main.classList.toggle('expanded');
            footer.classList.toggle('expanded');
        });

        // Search functionality
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
        alert('Editing product with ID: ' + productId);
    }

    function deleteProduct(productId) {
        if (confirm('Are you sure you want to delete this product?')) {
            alert('Product with ID ' + productId + ' would be deleted');
        }
    }
</script>
</body>
</html>
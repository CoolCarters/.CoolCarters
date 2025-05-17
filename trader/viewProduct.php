<?php
session_start();

// Sample product data
$products = [
    [
        'pid' => 'P001',
        'name' => 'Premium Hammer',
        'image' => 'hammer.jpg',
        'stock' => 45,
        'price' => 24.99,
        'discount' => 5
    ],
    [
        'pid' => 'P002',
        'name' => 'Professional Screwdriver Set',
        'image' => 'screwdriver.jpg',
        'stock' => 32,
        'price' => 39.99,
        'discount' => 10
    ],
    [
        'pid' => 'P003',
        'name' => 'Heavy Duty Wrench',
        'image' => 'wrench.jpg',
        'stock' => 18,
        'price' => 15.50,
        'discount' => 0
    ],
    [
        'pid' => 'P004',
        'name' => 'Power Drill',
        'image' => 'drill.jpg',
        'stock' => 12,
        'price' => 89.99,
        'discount' => 15
    ]
];

$_SESSION['username'] = 'ToolMaster';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>ToolCarters - Products</title>
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
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
        }

        /* Sidebar */
        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 240px;
            background: var(--dark);
            padding: 1.5rem 1rem;
        }

        #sidebar h2 {
            color: var(--bg);
            text-align: center;
            margin-bottom: 2rem;
            font-weight: 600;
        }

        #sidebar a {
            display: flex;
            align-items: center;
            color: var(--text);
            text-decoration: none;
            padding: .75rem;
            border-radius: .5rem;
            margin-bottom: .5rem;
            transition: background .2s, color .2s;
        }

        #sidebar a i {
            width: 20px;
            margin-right: .75rem;
        }

        #sidebar a:hover,
        #sidebar a.active {
            background: var(--primary);
            color: var(--bg);
        }

        /* Top nav */
        #topnav {
            position: fixed;
            top: 0;
            left: 240px;
            right: 0;
            height: 60px;
            background: var(--bg);
            display: flex;
            align-items: center;
            padding: 0 2rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, .1);
            z-index: 10;
        }

        #topnav #toggleBtn {
            font-size: 1.4rem;
            margin-right: 1rem;
            cursor: pointer;
            color: var(--text);
        }

        #topnav h1 {
            flex: 1;
            font-size: 1.2rem;
            color: var(--dark);
            font-weight: 500;
        }

        #topnav .user {
            font-size: .9rem;
            color: var(--text);
        }

        /* Main content */
        #main {
            margin: 80px 2rem 60px 260px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #ccc;
            padding: .75rem 1rem;
            font-weight: 500;
            font-size: 1rem;
            margin-bottom: 1rem;
        }

        /* Search and Add Product */
        .product-actions {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .search-box {
            position: relative;
            width: 300px;
        }

        .search-box input {
            width: 100%;
            padding: 0.5rem 1rem 0.5rem 2.5rem;
            border: 1px solid #ddd;
            border-radius: 0.375rem;
        }

        .search-box i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text);
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--secondary);
        }

        /* Products Table */
        .products-table {
            width: 100%;
            border-collapse: collapse;
            background: var(--bg);
            box-shadow: 0 4px 6px rgba(0, 0, 0, .05);
            border-radius: 0.375rem;
            overflow: hidden;
        }

        .products-table th,
        .products-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .products-table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .products-table tr:last-child td {
            border-bottom: none;
        }

        .products-table tr:hover {
            background-color: #f8f9fa;
        }

        .product-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 4px;
        }

        .action-btns {
            display: flex;
            gap: 0.5rem;
        }

        .action-btn {
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            font-weight: 500;
        }

        .edit-btn {
            background-color: #4e73df;
            color: white;
        }

        .delete-btn {
            background-color: #e74a3b;
            color: white;
        }

        .action-btn:hover {
            opacity: 0.8;
        }

        /* Footer */
        #footer {
            position: fixed;
            bottom: 0;
            left: 240px;
            right: 0;
            background: var(--bg);
            padding: .75rem 2rem;
            box-shadow: 0 -2px 4px rgba(0, 0, 0, .05);
            display: flex;
            align-items: center;
            justify-content: space-between;
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

        #footer p {
            font-size: .85rem;
            color: var(--text);
        }

        /* Responsive */
        @media (max-width: 768px) {
            #sidebar {
                width: 0;
                padding: 0;
            }

            #topnav {
                left: 0;
            }

            #main {
                margin-left: 1rem;
            }

            #footer {
                left: 0;
            }

            .products-table {
                display: block;
                overflow-x: auto;
            }

            .product-actions {
                flex-direction: column;
                gap: 1rem;
            }

            .search-box {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <nav id="sidebar">
        <h2>ToolCarters</h2>
        <a href="traderInterface.php"><i class="fas fa-tachometer-alt"></i>Dashboard</a>
        <a href="viewProduct.php" class="active"><i class="fas fa-box"></i>Products</a>
        <a href="traderReport.php"><i class="fas fa-chart-bar"></i>Reports</a>
        <a href="traderFeedback.php"><i class="fas fa-comments"></i>Feedbacks</a>
        <a href="traderCalendar.php"><i class="fas fa-calendar-alt"></i>Calendar</a>
        <a href="traderAccount.php"><i class="fas fa-user-circle"></i>Account</a>
        <a href="traderShop.php"><i class="fas fa-store"></i>Shop</a>
    </nav>

    <!-- Top nav -->
    <header id="topnav">
        <div id="toggleBtn"><i class="fas fa-bars"></i></div>
        <h1>Products</h1>
        <div class="user"><i class="fas fa-user-circle"></i> <?= $_SESSION['username'] ?></div>
    </header>

    <!-- Main content -->
    <section id="main">
        <div class="section-header">
            <span>Products</span>
        </div>

        <div class="product-actions">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search products...">
            </div>
            <a href="traderAddProduct.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Product
            </a>
        </div>

        <table class="products-table">
            <thead>
                <tr>
                    <th>PID</th>
                    <th>Product Name</th>
                    <th>Image</th>
                    <th>Stock</th>
                    <th>Price</th>
                    <th>Discount</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?= $product['pid'] ?></td>
                        <td><?= $product['name'] ?></td>
                        <td>
                            <img src="images/<?= $product['image'] ?>" alt="<?= $product['name'] ?>" class="product-image">
                        </td>
                        <td><?= $product['stock'] ?></td>
                        <td>$<?= number_format($product['price'], 2) ?></td>
                        <td><?= $product['discount'] ?>%</td>
                        <td>
                            <div class="action-btns">
                                <button class="action-btn edit-btn">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="action-btn delete-btn">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <!-- Footer -->
    <footer id="footer">
        <div>
            <a href="#">Home</a> |
            <a href="#">About Us</a>
        </div>
        <div class="socials">
            <span>To keep us connected</span>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fa-brands fa-x"></i></a>
        </div>
    </footer>

    <script>
        // Toggle sidebar
        document.getElementById('toggleBtn').onclick = () => {
            document.getElementById('sidebar').classList.toggle('collapsed');
            document.getElementById('topnav').classList.toggle('expanded');
            document.getElementById('main').classList.toggle('expanded');
            document.getElementById('footer').classList.toggle('expanded');
        };

        // Search functionality
        document.querySelector('.search-box input').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('.products-table tbody tr');

            rows.forEach(row => {
                const productName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const pid = row.querySelector('td:nth-child(1)').textContent.toLowerCase();

                if (productName.includes(searchTerm) || pid.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</body>

</html>
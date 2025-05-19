<?php
session_start();

// Sample shop data
$shops = [
    [
        'id' => 1,
        'name' => 'Butcher',
        'logo' => '../images/shops/meat-market.jpg',
        'location' => '123 Main St, Anytown',
        'description' => 'Specializing in premium quality meats and poultry'
    ],
    [
        'id' => 2,
        'name' => 'Bakery',
        'logo' => '../images/shops/bakery.jpg',
        'location' => '456 Oak Ave, Somewhere',
        'description' => 'Fresh bread and pastries made daily'
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
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
            transition: margin-left 0.3s ease;
        }
        .main-content.expanded {
            margin-left: 260px;
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
        .add-shop {
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
        .add-shop:hover {
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

        /* Shops Table */
        .shops-table {
            width: 100%;
            background: var(--bg);
            border-radius: 4px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .shops-table th,
        .shops-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        .shops-table th {
            background: var(--light);
            font-weight: 500;
            color: var(--dark);
        }
        .shops-table img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
        }
        .shop-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .shop-details {
            line-height: 1.4;
        }
        .shop-name {
            font-weight: 500;
            color: var(--dark);
        }
        .shop-location {
            font-size: 0.875rem;
            color: var(--text);
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
 
        /* Responsive */
        @media (max-width: 768px) {
            .main-content { 
                margin-left: 0;
                padding: 5rem 1rem 2rem;
            }
            .main-content.expanded {
                margin-left: 260px;
            }
            footer { 
                left: 0;
            }
            footer.expanded {
                left: 240px;
            }
        }
    </style>
</head>
<body>
    <!-- Include the navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Main Content -->
    <main class="main-content">
        <div class="content-header">
            <h1>My Shops</h1>
            <a href="traderAddShop.php" class="add-shop">
                <i class="fas fa-plus"></i>
                Add Shop
            </a>
        </div>

        <div class="search-filter">
            <div class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" name="search" placeholder="Search shops...">
            </div>
        </div>

        <table class="shops-table">
            <thead>
                <tr>
                    <th>Shop ID</th>
                    <th>Shop Name</th>
                    <th>Logo</th>
                    <th>Location</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($shops as $shop): ?>
                    <tr>
                        <td><?= htmlspecialchars($shop['id']) ?></td>
                        <td><?= htmlspecialchars($shop['name']) ?></td>
                        <td>
                            <img src="<?= htmlspecialchars($shop['logo']) ?>" 
                                 alt="<?= htmlspecialchars($shop['name']) ?>">
                        </td>
                        <td><?= htmlspecialchars($shop['location']) ?></td>
                        <td><?= htmlspecialchars($shop['description']) ?></td>
                        <td class="action-buttons">
                            <button class="edit" onclick="editShop(<?= $shop['id'] ?>)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="delete" onclick="deleteShop(<?= $shop['id'] ?>)">
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
            <a href="#"><i class="fab fa-twitter"></i></a>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('toggleBtn');
            const sidebar = document.getElementById('sidebar');
            const main = document.querySelector('.main-content');
            const footer = document.querySelector('footer');
            
            toggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('collapsed');
                main.classList.toggle('expanded');
                footer.classList.toggle('expanded');
            });

            // Search functionality
            const searchInput = document.querySelector('.search-bar input');
            searchInput.addEventListener('input', function() {
                const filter = this.value.toLowerCase();
                document.querySelectorAll('.shops-table tbody tr').forEach(row => {
                    const text = row.innerText.toLowerCase();
                    row.style.display = text.includes(filter) ? '' : 'none';
                });
            });
        });

        function editShop(shopId) {
            // In a real application, this would redirect to an edit page
            alert('Editing shop with ID: ' + shopId);
            // window.location.href = 'traderEditShop.php?id=' + shopId;
        }

        function deleteShop(shopId) {
            if (confirm('Are you sure you want to delete this shop?')) {
                // In a real application, this would make an AJAX call or redirect
                alert('Shop with ID ' + shopId + ' would be deleted');
                // window.location.href = 'traderDeleteShop.php?id=' + shopId;
            }
        }
    </script>
</body>
</html>
<?php
session_start();

// Sample data
$shops = [
    ['id' => 1, 'name' => 'Shop1'],
    ['id' => 2, 'name' => 'Shop2']
];
$_SESSION['username'] = 'Username';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CoolCarters Trader Dashboard</title>
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        }
        /* Sidebar */
        #sidebar {
            position: fixed; top: 0; left: 0; bottom: 0;
            width: 240px; background: var(--dark);
            padding: 1.5rem 1rem;
        }
        #sidebar h2 {
            color: var(--bg); text-align: center; margin-bottom: 2rem;
            font-weight: 600;
        }
        #sidebar a {
            display: flex; align-items: center;
            color: var(--text); text-decoration: none;
            padding: .75rem; border-radius: .5rem;
            margin-bottom: .5rem; transition: background .2s, color .2s;
        }
        #sidebar a i { width: 20px; margin-right: .75rem; }
        #sidebar a:hover, #sidebar a.active { background: var(--primary); color: var(--bg); }
        /* Top nav */
        #topnav {
            position: fixed; top: 0; left: 240px; right: 0;
            height: 60px; background: var(--bg);
            display: flex; align-items: center;
            padding: 0 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
            z-index: 10;
        }
        #topnav #toggleBtn {
            font-size: 1.4rem; margin-right: 1rem; cursor: pointer;
            color: var(--text);
        }
        #topnav h1 {
            flex: 1; font-size: 1.2rem; color: var(--dark); font-weight: 500;
        }
        #topnav .user {
            font-size: .9rem; color: var(--text);
        }
        /* Main content */
        #main {
            margin: 80px 2rem 60px 260px;
        }
        .section-header {
            background: #ccc; padding: .75rem 1rem;
            font-weight: 500; font-size: 1rem;
        }
        .cards {
            display: grid;
            grid-template-columns: 1fr 1fr; /* Two-column layout */
            gap: 1.5rem; margin-bottom: 2rem;
        }
        .card {
            background: var(--bg);
            padding: 1.5rem;
            border-radius: .75rem;
            box-shadow: 0 4px 6px rgba(0,0,0,.05);
            overflow: hidden;
            transition: transform .2s, box-shadow .2s;
            text-align: center;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 12px rgba(0,0,0,.1);
        }
        .card .shops-container {
            display: flex; justify-content: space-around; align-items: center;
        }
        .shop-circle {
            background: white; border-radius: 50%; width: 80px; height: 80px;
            display: flex; align-items: center; justify-content: center;
            border: 2px solid #000;
        }
        .chart-container {
            background: var(--bg);
            padding: 1.5rem;
            border-radius: .75rem;
            box-shadow: 0 4px 6px rgba(0,0,0,.05);
            height: 200px; display: flex; align-items: center; justify-content: center;
        }
        /* Footer */
        #footer {
            position: fixed; bottom: 0; left: 240px; right: 0;
            background: var(--bg); padding: .75rem 2rem;
            box-shadow: 0 -2px 4px rgba(0,0,0,.05);
            display: flex; align-items: center; justify-content: space-between;
        }
        .socials a {
            font-size: 1.2rem; color: var(--text); margin-left: 1rem;
            transition: color .2s;
        }
        .socials a:hover { color: var(--primary); }
        #footer p { font-size: .85rem; color: var(--text); }
        /* Responsive */
        @media (max-width: 768px) {
            #sidebar { width: 0; padding: 0; }
            #topnav { left: 0; }
            #main { margin-left: 1rem; }
            #footer { left: 0; }
            .cards { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <nav id="sidebar">
        <h2>CoolCarters</h2>
        <a href="trader_dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i>Dashboard</a>
        <a href="traders_products.php"><i class="fas fa-box"></i>Products</a>
        <a href="traders_reports.php"><i class="fas fa-chart-bar"></i>Reports</a>
        <a href="traders_feedbacks.php"><i class="fas fa-comments"></i>Feedbacks</a>
        <a href="traders_calendar.php"><i class="fas fa-calendar-alt"></i>Calendar</a>
        <a href="traders_account.php"><i class="fas fa-user-circle"></i>Account</a>
        <a href="traders_shop.php"><i class="fas fa-store"></i>Shop</a>
    </nav>

    <!-- Top nav -->
    <header id="topnav">
        <div id="toggleBtn"><i class="fas fa-bars"></i></div>
        <h1>Trader Dashboard</h1>
        <div class="user"><i class="fas fa-user-circle"></i> <?= $_SESSION['username'] ?></div>
    </header>

    <!-- Main content -->
    <section id="main">
        <div class="section-header">Trader Dashboard</div>
        <div class="cards">
            <!-- Your Shops -->
            <div class="card">
                <h3>Your Shops</h3>
                <div class="shops-container">
                    <?php foreach ($shops as $shop): ?>
                        <div class="shop-circle">
                            <span><?= $shop['name'] ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Customer rate in 7 days -->
            <div class="card">
                <h3>Customer rate in 7 days</h3>
                <div class="chart-container">
                    <svg width="150" height="150">
                        <path d="M30 120 L60 60 L90 90 L120 30" stroke="black" stroke-width="5" fill="none"/>
                        <circle cx="75" cy="75" r="60" fill="none" stroke="black" stroke-width="2"/>
                    </svg>
                </div>
            </div>

            <!-- Product Evaluation -->
            <div class="card">
                <h3>Product Evaluation</h3>
                <div class="chart-container">
                    <svg width="150" height="150">
                        <rect x="50" y="50" width="20" height="50" fill="black"/>
                        <rect x="80" y="70" width="20" height="30" fill="black"/>
                        <rect x="110" y="30" width="20" height="70" fill="black"/>
                    </svg>
                </div>
            </div>

            <!-- Product Sales -->
            <div class="card">
                <h3>Product Sales</h3>
                <div class="chart-container">
                    <svg width="150" height="150">
                        <circle cx="75" cy="75" r="60" fill="none" stroke="black" stroke-width="2"/>
                        <path d="M75 15 A60 60 0 0 1 135 75 L75 75 Z" fill="black"/>
                        <path d="M75 135 A60 60 0 0 1 15 75 L75 75 Z" fill="black"/>
                    </svg>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="footer">
        <p>Â© 2025 CoolCarter. All rights reserved</p>
        <div class="socials">
            <span>Keep us connected:</span>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fa-brands fa-x"></i></a>
        </div>
    </footer>

    <script>
        document.getElementById('toggleBtn').onclick = () => {
            document.getElementById('sidebar').classList.toggle('collapsed');
            document.getElementById('topnav').classList.toggle('expanded');
            document.getElementById('main').classList.toggle('expanded');
            document.getElementById('footer').classList.toggle('expanded');
        };
    </script>
</body>
</html>
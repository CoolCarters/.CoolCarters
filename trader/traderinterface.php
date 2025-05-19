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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            padding-bottom: 60px; /* Space for footer */
        }
        
        /* Main content */
        /* Main content */
        #main {
            margin: 80px 2rem 60px 260px;
            transition: margin-left 0.3s ease;
        }
        #main.expanded {
            margin-left: 260px;
        }
        .section-header {
            background: #f8f9fa;
            padding: .75rem 1rem;
            font-weight: 500;
            font-size: 1rem;
            border-bottom: 1px solid #e3e6f0;
            color: #4e73df;
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
            #main { 
                margin-left: 1rem;
                margin-right: 1rem;
            }
            #main.expanded {
                margin-left: 260px;
            }
            .cards { grid-template-columns: 1fr; }
            footer { 
                left: 0;
            }
            footer.expanded {
                left: 240px;
            }
            body { padding-bottom: 60px; }
        }
        
    </style>
</head>
<body>
    <!-- Include the navbar -->
     <?php include 'navbar.php'; ?>

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
</body>
</html>
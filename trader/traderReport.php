
// Sample data - in a real application, this would come from a database
<?php
session_start();

// Sample report data for trader
$dailyData = [45, 60, 75, 55, 80, 90, 100]; // Daily sales
$weeklyData = [350, 420, 380, 400]; // Weekly sales
$monthlyData = [1500, 1800, 2000, 2200, 2400]; // Monthly sales
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <!-- Chart.js -->
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
            padding-bottom: 60px;
        }
        
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
        .controls {
            display: flex;
            gap: 1rem;
            margin: 1rem 0;
        }
        .controls button {
            padding: 0.5rem 1rem;
            background: var(--light);
            border: 1px solid #ddd;
            border-radius: 0.25rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .controls button.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        .chart-container {
            background: var(--bg);
            padding: 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            margin-top: 1rem;
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
        <div class="section-header">Sales Reports</div>
        <div class="controls">
            <button id="btnDaily" class="active">Daily</button>
            <button id="btnWeekly">Weekly</button>
            <button id="btnMonthly">Monthly</button>
        </div>
        <div class="chart-container">
            <canvas id="reportChart"></canvas>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Data from PHP
            const dailyData = <?= json_encode($dailyData); ?>;
            const weeklyData = <?= json_encode($weeklyData); ?>;
            const monthlyData = <?= json_encode($monthlyData); ?>;
            
            // Chart setup
            const ctx = document.getElementById('reportChart').getContext('2d');
            let chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Daily Sales',
                        data: dailyData,
                        borderColor: 'var(--primary)',
                        backgroundColor: 'rgba(78, 115, 223, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Toggle button functionality
            document.getElementById('btnDaily').addEventListener('click', function() {
                updateChart('Daily', dailyData, ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'], 0);
                setActiveButton(this);
            });
            
            document.getElementById('btnWeekly').addEventListener('click', function() {
                updateChart('Weekly', weeklyData, ['Week 1', 'Week 2', 'Week 3', 'Week 4'], 1);
                setActiveButton(this);
            });
            
            document.getElementById('btnMonthly').addEventListener('click', function() {
                updateChart('Monthly', monthlyData, ['Jan', 'Feb', 'Mar', 'Apr', 'May'], 2);
                setActiveButton(this);
            });

            function updateChart(label, data, labels, idx) {
                chart.data.labels = labels;
                chart.data.datasets[0].label = label + ' Sales';
                chart.data.datasets[0].data = data;
                chart.update();
            }

            function setActiveButton(activeBtn) {
                document.querySelectorAll('.controls button').forEach(btn => {
                    btn.classList.remove('active');
                });
                activeBtn.classList.add('active');
            }

            // Sidebar toggle functionality
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
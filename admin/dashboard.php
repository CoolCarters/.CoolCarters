<?php
// dashboard.php — show all errors
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>CoolCarters Admin Dashboard</title>

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

  <!-- Font Awesome for icons -->
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
    * { box-sizing: border-box; margin:0; padding:0; }
    body {
      font-family: 'Poppins', sans-serif;
      background: var(--light);
      color: var(--dark);
      overflow-x: hidden;
    }

    /* Sidebar */
    #sidebar {
      position: fixed; top:0; left:0; bottom:0;
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
      margin-bottom: .5rem;
      transition: background .2s, color .2s;
    }
    #sidebar a i {
      width: 20px; margin-right: .75rem;
    }
    #sidebar a:hover {
      background: var(--primary);
      color: var(--bg);
    }

    /* Top nav */
    #topnav {
      position: fixed; top:0; left:240px; right:0;
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
    #main h2 {
      font-weight: 500; margin-bottom: 1rem; color: var(--dark);
    }

    .cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px,1fr));
      gap: 1.5rem; margin-bottom: 2rem;
    }
    .card {
      position: relative;
      background: var(--bg);
      padding: 1.5rem;
      border-radius: .75rem;
      box-shadow: 0 4px 6px rgba(0,0,0,.05);
      overflow: hidden;
      transition: transform .2s, box-shadow .2s;
    }
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 12px rgba(0,0,0,.1);
    }
    .card .icon-bg {
      position: absolute; top:-10px; right:-10px;
      font-size: 4rem; color: var(--light); opacity: .2;
    }
    .card i.icon {
      font-size: 1.8rem; color: var(--primary);
    }
    .card .value {
      font-size: 1.8rem; font-weight: 600; margin: .5rem 0;
    }
    .card .label {
      color: var(--text); font-size: .9rem;
    }

    /* Charts */
    .charts {
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: 1.5rem;
    }
    .chart-container {
      background: var(--bg);
      padding: 1.5rem;
      border-radius: .75rem;
      box-shadow: 0 4px 6px rgba(0,0,0,.05);
    }
    .chart-container h3 {
      margin-bottom: 1rem; font-weight: 500; color: var(--dark);
    }

    /* Footer */
    #footer {
      position: fixed; bottom:0; left:240px; right:0;
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
      #sidebar { width: 0; padding:0; }
      #topnav { left:0; }
      #main { margin-left:1rem; }
      #footer { left:0; }
      .charts { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>

   <!-- Sidebar -->
  <nav id="sidebar">
    <h2>CoolCarters</h2>
    <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i>Dashboard</a>
    <a href="traders.php" class="active"><i class="fas fa-store"></i>Traders</a>
    <a href="customers.php"><i class="fas fa-users"></i>Customers</a>
    <a href="products.php"><i class="fas fa-box"></i>Products</a>
    <a href="reports.php"><i class="fas fa-chart-line"></i>Report</a>
    <a href="feedbacks.php"><i class="fas fa-comments"></i>Feedbacks</a>
    <a href="calendar.php"><i class="fas fa-calendar-alt"></i>Calendar</a>
    <a href="administration.php"><i class="fas fa-cogs"></i>Administration</a>
  </nav>

  <!-- Top nav -->
  <header id="topnav">
    <div id="toggleBtn"><i class="fas fa-bars"></i></div>
    <h1>Admin Dashboard</h1>
    <div class="user"><i class="fas fa-user-circle"></i> Administrator</div>
  </header>

  <!-- Main content -->
  <section id="main">
    <h2>General Update</h2>
    <div class="cards">
      <div class="card">
        <div class="icon-bg"><i class="fas fa-dollar-sign"></i></div>
        <i class="fas fa-dollar-sign icon"></i>
        <div class="value">$12,345</div>
        <div class="label">Total Sales</div>
      </div>
      <div class="card">
        <div class="icon-bg"><i class="fas fa-user-friends"></i></div>
        <i class="fas fa-user-friends icon"></i>
        <div class="value">3,210</div>
        <div class="label">Total Users</div>
      </div>
      <div class="card">
        <div class="icon-bg"><i class="fas fa-store-alt"></i></div>
        <i class="fas fa-store-alt icon"></i>
        <div class="value">158</div>
        <div class="label">Total Shops</div>
      </div>
      <div class="card">
        <div class="icon-bg"><i class="fas fa-boxes"></i></div>
        <i class="fas fa-boxes icon"></i>
        <div class="value">7,890</div>
        <div class="label">Total Stock</div>
      </div>
    </div>

    <div class="charts">
      <div class="chart-container">
        <h3>User Productivity (7 days)</h3>
        <canvas id="lineChart"></canvas>
      </div>
      <div class="chart-container">
        <h3>Product Sales Breakdown</h3>
        <canvas id="pieChart"></canvas>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer id="footer">
    <div>&copy; 2025 CoolCarter. All rights reserved</div>
    <div class="socials">
      <span>Keep us connected:</span>
      <a href="#"><i class="fab fa-instagram"></i></a>
      <a href="#"><i class="fab fa-facebook-f"></i></a>
      <a href="#"><i class="fa-brands fa-x"></i></a>
    </div>
  </footer>

  <script>
    // Chart.js: line chart
    new Chart(document.getElementById('lineChart'), {
      type: 'line',
      data: {
        labels: ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'],
        datasets: [{
          label: 'Active Users',
          data: [120, 150, 170, 140, 180, 200, 220],
          borderColor: 'var(--primary)',
          backgroundColor: 'rgba(78,115,223,0.15)',
          tension: 0.4,
          fill: true,
          pointRadius: 3
        }]
      },
      options: {
        scales: {
          y: { beginAtZero: true, grid: {color: '#eee'} },
          x: { grid: {display: false} }
        },
        plugins: { legend: {display: false} }
      }
    });

    // Chart.js: pie chart
    new Chart(document.getElementById('pieChart'), {
      type: 'pie',
      data: {
        labels: ['Electronics','Apparel','Home Goods'],
        datasets: [{
          data: [45, 25, 30],
          backgroundColor: ['#4e73df','#1cc88a','#36b9cc']
        }]
      },
      options: {
        plugins: {
          legend: {position:'bottom'}
        }
      }
    });

    // Sidebar toggle for mobile
    document.getElementById('toggleBtn').onclick = () => {
      document.getElementById('sidebar').classList.toggle('collapsed');
      document.getElementById('topnav').classList.toggle('expanded');
      document.getElementById('main').classList.toggle('expanded');
      document.getElementById('footer').classList.toggle('expanded');
    };
  </script>
</body>
</html>

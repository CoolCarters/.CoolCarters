<?php
// reports.php — show all errors
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

// You might fetch summary data here. Placeholder arrays:
$dailyData   = [120, 150, 170, 140, 180, 200, 220];
$weeklyData  = [800, 950, 1100, 1050];
$monthlyData = [3200, 3400, 3600, 3800, 4000, 4200];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>CoolCarters Admin Dashboard - Reports</title>

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>
    :root { --primary: #4e73df; --secondary: #2e59d9; --light: #f8f9fc; --dark: #343a40; --text: #858796; --bg: #fff; }
    *, *::before, *::after { box-sizing: border-box; margin:0; padding:0; }
    body { font-family: 'Poppins', sans-serif; background: var(--light); color: var(--dark); overflow-x: hidden; }
    /* Sidebar */
    #sidebar { position: fixed; top:0; left:0; bottom:0; width:240px; background: var(--dark); padding:1.5rem 1rem; }
    #sidebar h2 { color: var(--bg); text-align:center; margin-bottom:2rem; font-weight:600; }
    #sidebar a { display:flex; align-items:center; color:var(--text); text-decoration:none; padding:.75rem; border-radius:.5rem; margin-bottom:.5rem; transition:.2s; }
    #sidebar a i { width:20px; margin-right:.75rem; }
    #sidebar a:hover, #sidebar a.active { background: var(--primary); color: var(--bg); }
    /* Top nav */
    #topnav { position:fixed; top:0; left:240px; right:0; height:60px; background: var(--bg); display:flex; align-items:center; padding:0 2rem; box-shadow:0 2px 4px rgba(0,0,0,.1); z-index:10; }
    #topnav #toggleBtn { font-size:1.4rem; margin-right:1rem; cursor:pointer; color: var(--text); }
    #topnav h1 { flex:1; font-size:1.2rem; color: var(--dark); font-weight:500; }
    #topnav .user { font-size:.9rem; color: var(--text); }
    /* Main content */
    #main { margin:80px 2rem 60px 260px; }
    .section-header { background:#ccc; padding:.75rem 1rem; font-weight:500; font-size:1rem; }
    .controls { display:flex; align-items:center; margin:1rem 0; gap:1rem; }
    .controls button { padding:.5rem 1rem; background:var(--light); border:1px solid #bbb; border-radius:.25rem; cursor:pointer; }
    .controls button.active { background: var(--primary); color:#fff; border-color: var(--primary); }
    /* Chart container */
    .chart-container { background: var(--bg); padding:1.5rem; border-radius:.75rem; box-shadow:0 4px 6px rgba(0,0,0,.05); }
    /* Footer */
    #footer { position:fixed; bottom:0; left:240px; right:0; background: var(--bg); padding:.75rem 2rem; box-shadow:0 -2px 4px rgba(0,0,0,.05); display:flex; justify-content:space-between; align-items:center; }
    #footer p { color:var(--text); font-size:.85rem; }
    .socials a { color:var(--text); margin-left:1rem; font-size:1.2rem; transition:.2s; }
    .socials a:hover { color:var(--primary); }
  </style>
</head>
<body>
  <!-- Sidebar -->
  <nav id="sidebar">
    <h2>CoolCarters</h2>
    <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i>Dashboard</a>
    <a href="traders.php"><i class="fas fa-store"></i>Traders</a>
    <a href="customers.php"><i class="fas fa-users"></i>Customers</a>
    <a href="products.php"><i class="fas fa-box"></i>Products</a>
    <a href="reports.php" class="active"><i class="fas fa-chart-bar"></i>Reports</a>
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
    <div class="section-header">Reports</div>
    <div class="controls">
      <button id="btnDaily" class="active">Daily</button>
      <button id="btnWeekly">Weekly</button>
      <button id="btnMonthly">Monthly</button>
    </div>
    <div class="chart-container">
      <canvas id="reportChart"></canvas>
    </div>
  </section>

  <!-- Footer -->
  <footer id="footer">
    <p>&copy; 2025 CoolCarter. All rights reserved</p>
    <div class="socials">
      <a href="#"><i class="fab fa-instagram"></i></a>
      <a href="#"><i class="fab fa-facebook-f"></i></a>
      <a href="#"><i class="fas fa-times"></i></a>
    </div>
  </footer>

  <script>
    // Data placeholders
    const dailyData   = <?= json_encode($dailyData); ?>;
    const weeklyData  = <?= json_encode($weeklyData); ?>;
    const monthlyData = <?= json_encode($monthlyData); ?>;
    const ctx = document.getElementById('reportChart').getContext('2d');
    let chart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: ['1','2','3','4','5','6','7'],
        datasets: [{ label: 'Daily Sales', data: dailyData, borderColor: 'var(--primary)', backgroundColor: 'rgba(78,115,223,0.15)', tension: .4 }]
      },
      options: { scales: { y: { beginAtZero:true } } }
    });

    // Buttons
    document.getElementById('btnDaily').onclick = () => updateChart('Daily', dailyData, ['1','2','3','4','5','6','7'], 0);
    document.getElementById('btnWeekly').onclick = () => updateChart('Weekly', weeklyData, ['Week1','Week2','Week3','Week4'], 1);
    document.getElementById('btnMonthly').onclick = () => updateChart('Monthly', monthlyData, ['Jan','Feb','Mar','Apr','May','Jun'], 2);

    function updateChart(label, data, labels, idx) {
      // toggle active class
      document.querySelectorAll('.controls button').forEach(b => b.classList.remove('active'));
      ['btnDaily','btnWeekly','btnMonthly'][idx];
      document.getElementById(['btnDaily','btnWeekly','btnMonthly'][idx]).classList.add('active');

      chart.config.data.labels = labels;
      chart.config.data.datasets[0].label = label + ' Sales';
      chart.config.data.datasets[0].data = data;
      chart.update();
    }

    // Sidebar toggle
    document.getElementById('toggleBtn').onclick = () => {
      document.getElementById('sidebar').classList.toggle('collapsed');
    };
  </script>
</body>
</html>

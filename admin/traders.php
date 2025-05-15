<?php
// traders.php — show all errors
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>CoolCarters Admin Dashboard - Traders</title>

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- Chart.js (if needed) -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>
    :root { --primary: #4e73df; --secondary: #2e59d9; --light: #f8f9fc; --dark: #343a40; --text: #858796; --bg: #fff; }
    * { box-sizing: border-box; margin:0; padding:0; }
    body { font-family: 'Poppins', sans-serif; background: var(--light); color: var(--dark); overflow-x: hidden; }

    /* Sidebar */
    #sidebar { position: fixed; top:0; left:0; bottom:0; width: 240px; background: var(--dark); padding: 1.5rem 1rem; }
    #sidebar h2 { color: var(--bg); text-align: center; margin-bottom: 2rem; font-weight: 600; }
    #sidebar a { display: flex; align-items: center; color: var(--text); text-decoration: none; padding: .75rem; border-radius: .5rem; margin-bottom: .5rem; transition: background .2s, color .2s; }
    #sidebar a i { width: 20px; margin-right: .75rem; }
    #sidebar a.active, #sidebar a:hover { background: var(--primary); color: var(--bg); }

    /* Top nav */
    #topnav { position: fixed; top:0; left:240px; right:0; height: 60px; background: var(--bg); display: flex; align-items: center; padding: 0 2rem; box-shadow: 0 2px 4px rgba(0,0,0,.1); z-index: 10; }
    #topnav #toggleBtn { font-size: 1.4rem; margin-right: 1rem; cursor: pointer; color: var(--text); }
    #topnav h1 { flex: 1; font-size: 1.2rem; color: var(--dark); font-weight: 500; }
    #topnav .user { font-size: .9rem; color: var(--text); }

    /* Main content */
    #main { margin: 80px 2rem 60px 260px; }
    #main h2 { font-weight: 500; margin-bottom: 1rem; color: var(--dark); }

    /* Table */
    .table-container { background: var(--bg); padding: 1.5rem; border-radius: .75rem; box-shadow: 0 4px 6px rgba(0,0,0,.05); overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: .75rem; border: 1px solid #ddd; text-align: left; }
    th { background: #f1f1f1; color: var(--text); font-size: .9rem; text-transform: uppercase; }
    tbody tr:hover { background: #f9f9f9; }
    input[type=text] { padding: .5rem; border: 1px solid #ccc; border-radius: .25rem; width: 250px; }

    /* Footer */
    #footer { position: fixed; bottom:0; left:240px; right:0; background: var(--bg); padding: .75rem 2rem; box-shadow: 0 -2px 4px rgba(0,0,0,.05); display: flex; align-items: center; justify-content: space-between; }
    .socials a { font-size: 1.2rem; color: var(--text); margin-left: 1rem; transition: color .2s; }
    .socials a:hover { color: var(--primary); }

    @media (max-width: 768px) {
      #sidebar { width: 0; padding:0; }
      #topnav { left:0; }
      #main { margin-left:1rem; }
      #footer { left:0; }
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
    <h2>Add Traders</h2>
    <input id="searchInput" type="text" placeholder="Search">

    <div class="table-container">
      <table>
        <thead>
          <tr>
            <th>More</th>
            <th>Name</th>
            <th>Email</th>
            <th>Contact</th>
            <th>Address</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody id="tradersTable">
          <?php if (!empty($traders)): ?>
            <?php foreach ($traders as $t): ?>
            <tr>
              <td>&hellip;</td>
              <td><?= htmlspecialchars($t['name']); ?></td>
              <td><?= htmlspecialchars($t['email']); ?></td>
              <td><?= htmlspecialchars($t['contact']); ?></td>
              <td><?= htmlspecialchars($t['address']); ?></td>
              <td>
                <button>✔️</button>
                <button>❌</button>
              </td>
            </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="6">No traders found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
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
    // Search filter
    document.getElementById('searchInput').addEventListener('input', function() {
      const filter = this.value.toLowerCase();
      document.querySelectorAll('#tradersTable tr').forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(filter) ? '' : 'none';
      });
    });

    // Sidebar toggle
    document.getElementById('toggleBtn').onclick = () => {
      document.getElementById('sidebar').classList.toggle('collapsed');
      document.getElementById('topnav').classList.toggle('expanded');
      document.getElementById('main').classList.toggle('expanded');
      document.getElementById('footer').classList.toggle('expanded');
    };
  </script>
</body>
</html>

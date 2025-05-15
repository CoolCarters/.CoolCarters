<?php
// products.php — show all errors
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>CoolCarters Admin Dashboard - Products</title>

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
    .controls .search { position:relative; flex:1; }
    .controls .search input { width:100%; padding:.5rem 2.5rem .5rem .75rem; border:1px solid #bbb; border-radius:.25rem; }
    .controls .search i { position:absolute; top:50%; right:.75rem; transform:translateY(-50%); color:#666; }
    .controls .filter-btn { display:flex; align-items:center; padding:.5rem 1rem; background:#ddd; border:1px solid #bbb; border-radius:.25rem; cursor:pointer; }
    .controls .filter-btn select { background:none; border:none; outline:none; font:inherit; }
    .controls .filter-btn i { margin-left:.5rem; }
    /* Table */
    .table-wrap { overflow-x:auto; }
    table { width:100%; border-collapse:collapse; background: var(--bg); }
    th, td { border:1px solid #bbb; padding:.75rem; text-align:left; }
    th { background:#eee; color: var(--dark); }
    tbody tr:hover { background:#f0f0f0; }
    .action-btn { background:none; border:none; cursor:pointer; font-size:1rem; }
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
    <a href="products.php" class="active"><i class="fas fa-box"></i>Products</a>
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
    <div class="section-header">Product Details</div>
    <div class="controls">
      <div class="search">
        <input id="searchInput" type="text" placeholder="Search">
        <i class="fas fa-search"></i>
      </div>
      <div class="filter-btn">
        <select id="categoryFilter">
          <option value="">All Categories</option>
          <option>Butchers</option>
          <option>Greengrocer</option>
          <option>Fishmonger</option>
          <option>Bakery</option>
          <option>Delicatessen</option>
        </select>
        <i class="fas fa-filter"></i>
      </div>
    </div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>PID</th>
            <th>Name</th>
            <th>Category</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody id="productTable">
          <?php if (!empty($products)): ?>
            <?php foreach ($products as $p): ?>
            <tr>
              <td><?= htmlspecialchars($p['pid']); ?></td>
              <td><?= htmlspecialchars($p['name']); ?></td>
              <td><?= htmlspecialchars($p['category']); ?></td>
              <td><?= htmlspecialchars(number_format($p['price'],2)); ?></td>
              <td><?= htmlspecialchars($p['stock']); ?></td>
              <td>
                <button class="action-btn"><i class="fas fa-pen"></i></button>
                <button class="action-btn"><i class="fas fa-trash"></i></button>
              </td>
            </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="6" style="text-align:center; padding:1rem;">No products found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
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
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const rows = document.querySelectorAll('#productTable tr');
    function filterTable() {
      const text = searchInput.value.toLowerCase();
      const cat = categoryFilter.value;
      rows.forEach(r => {
        const cells = r.innerText.toLowerCase();
        const rowCat = r.children[2].innerText;
        r.style.display = (cells.includes(text) && (cat === '' || rowCat === cat)) ? '' : 'none';
      });
    }
    searchInput.addEventListener('input', filterTable);
    categoryFilter.addEventListener('change', filterTable);
    document.getElementById('toggleBtn').onclick = () => {
      document.getElementById('sidebar').classList.toggle('collapsed');
    };
  </script>
</body>
</html>


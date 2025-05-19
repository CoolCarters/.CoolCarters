<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Trader';
?>

<!-- Google Font -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- Sidebar -->
<nav id="sidebar">
    <a href="traderInterface.php" <?= basename($_SERVER['PHP_SELF']) == 'traderInterface.php' ? 'class="active"' : '' ?>>
        <i class="fas fa-tachometer-alt"></i>Dashboard
    </a>
    <a href="viewProduct.php" <?= basename($_SERVER['PHP_SELF']) == 'viewProduct.php' ? 'class="active"' : '' ?>>
        <i class="fas fa-box"></i>Products
    </a>
    <a href="traderReport.php" <?= basename($_SERVER['PHP_SELF']) == 'traderReport.php' ? 'class="active"' : '' ?>>
        <i class="fas fa-chart-bar"></i>Reports
    </a>
    <a href="traderFeedback.php" <?= basename($_SERVER['PHP_SELF']) == 'traderFeedback.php' ? 'class="active"' : '' ?>>
        <i class="fas fa-comments"></i>Feedbacks
    </a>
    <a href="traderCalendar.php" <?= basename($_SERVER['PHP_SELF']) == 'traderCalendar.php' ? 'class="active"' : '' ?>>
        <i class="fas fa-calendar-alt"></i>Calendar
    </a>
    <a href="traderAccount.php" <?= basename($_SERVER['PHP_SELF']) == 'traderAccount.php' ? 'class="active"' : '' ?>>
        <i class="fas fa-user-circle"></i>Account
    </a>
    <a href="traderShop.php" <?= basename($_SERVER['PHP_SELF']) == 'traderShop.php' ? 'class="active"' : '' ?>>
        <i class="fas fa-store"></i>Shop
    </a>
</nav>

<!-- Top nav -->
<header id="topnav">
    <div class="brand">
        <img src="../assets/images/logo.png" alt="CoolCarters Logo" class="logo">
        <h2>CoolCarters</h2>
    </div>
    <div class="user">
        <i class="fas fa-user-circle"></i>
        <?= htmlspecialchars($username) ?>
    </div>
</header>

<div id="toggleBtn"><i class="fas fa-bars"></i></div>

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
    padding-top: 80px;
    z-index: 20;
    transition: width 0.3s ease;
}

#sidebar.collapsed {
    width: 0;
    padding: 0;
    overflow: hidden;
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

#sidebar a:hover, #sidebar a.active { 
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
    background: var(--dark);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 2rem;
    box-shadow: 0 2px 4px rgba(0,0,0,.1);
    z-index: 10;
}

#toggleBtn {
    position: fixed;
    top: 0;
    left: 0;
    padding: 1.2rem;
    font-size: 1.4rem;
    cursor: pointer;
    color: var(--bg);
    z-index: 100;
    background: var(--dark);
}

#topnav .brand {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex: 1;
}

#topnav .brand .logo {
    height: 40px;
    width: auto;
}

#topnav .brand h2 {
    color: var(--bg);
    font-size: 1.2rem;
    font-weight: 600;
}

#topnav .user {
    color: var(--bg);
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

#topnav .user i {
    font-size: 1.2rem;
}

/* Responsive */
@media (max-width: 768px) {
    #sidebar { 
        width: 240px; 
        transform: translateX(-100%);
        transition: transform 0.3s ease;
    }
    #sidebar.collapsed {
        transform: translateX(0);
    }
    #topnav { 
        left: 0; 
    }
    #topnav.expanded {
        left: 240px;
    }
    #main.expanded {
        margin-left: 240px;
    }
}
</style>

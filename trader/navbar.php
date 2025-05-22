<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$username = $_SESSION['full_name'] ?? 'Guest';
?>
<a href="account.php"><i class="fas fa-user-circle"></i> <?= htmlspecialchars($username) ?></a>
<?php if ($_SESSION['role'] === 'trader'): ?>
    <br><small><?= htmlspecialchars($_SESSION['company_name'] ?? '') ?></small>
<?php endif; ?>

<!DOCTYPE html>
<html lang="en">
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Trader Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4e73df;
            --secondary: #2e59d9;
            --light: #f8f9fc;
            --dark: #343a40;
            --text: #858796;
            --bg: #fff;
            --sidebar-width: 240px;
            --collapsed-width: 70px;
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
            min-height: 100vh;
        }

        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: var(--sidebar-width);
            background: var(--dark);
            padding-top: 80px;
            padding-left: 1rem;
            padding-right: 1rem;
            z-index: 100;
            transition: transform 0.3s ease;
        }

        body.sidebar-collapsed #sidebar {
            transform: translateX(-100%);
        }

        #sidebar a {
            display: flex;
            align-items: center;
            color: var(--text);
            text-decoration: none;
            padding: 0.75rem;
            border-radius: 0.5rem;
            margin-bottom: 0.5rem;
            transition: background 0.2s, color 0.2s;
        }

        #sidebar a i {
            width: 20px;
            margin-right: 0.75rem;
        }

        #sidebar a:hover,
        #sidebar a.active {
            background: var(--primary);
            color: var(--bg);
        }

        #topnav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 60px;
            background: var(--dark);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            padding-left: var(--sidebar-width);
            box-shadow: 0 2px 4px rgba(0, 0, 0, .1);
            z-index: 90;
            transition: padding-left 0.3s ease;
        }

        body.sidebar-collapsed #topnav {
            padding-left: var(--collapsed-width);
        }

        #topnav .brand {
            display: flex;
            align-items: center;
            gap: 1rem;
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
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        #topnav .user .trader-name {
            font-size: 0.85rem;
            font-weight: 500;
        }

        #topnav .logout-btn {
            background: var(--primary);
            padding: 0.4rem 0.7rem;
            border-radius: 0.5rem;
            font-size: 0.8rem;
            transition: background 0.2s ease;
            color: #fff;
            text-decoration: none;
        }

        #topnav .logout-btn:hover {
            background: var(--secondary);
        }

        #toggleBtn {
            position: fixed;
            top: 0;
            left: 0;
            width: 60px;
            height: 60px;
            font-size: 1.4rem;
            cursor: pointer;
            color: var(--bg);
            background: var(--dark);
            z-index: 999;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @media (max-width: 768px) {
            #topnav {
                padding-left: var(--collapsed-width);
            }

            #sidebar {
                transform: translateX(-100%);
            }

            body.sidebar-open #sidebar {
                transform: translateX(0);
            }

            #topnav .user {
                flex-direction: column;
                align-items: flex-end;
                gap: 0.3rem;
            }

            #topnav .user .trader-name {
                font-size: 0.8rem;
            }

            #topnav .logout-btn {
                font-size: 0.75rem;
                padding: 0.3rem 0.5rem;
            }
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <nav id="sidebar">
        <a href="traderInterface.php" <?= basename($_SERVER['PHP_SELF']) == 'traderInterface.php' ? 'class="active"' : '' ?>><i class="fas fa-tachometer-alt"></i>Dashboard</a>
        <a href="viewProduct.php" <?= basename($_SERVER['PHP_SELF']) == 'viewProduct.php' ? 'class="active"' : '' ?>><i class="fas fa-box"></i>Products</a>
        <a href="traderReport.php" <?= basename($_SERVER['PHP_SELF']) == 'traderReport.php' ? 'class="active"' : '' ?>><i class="fas fa-chart-bar"></i>Reports</a>
        <a href="traderFeedback.php" <?= basename($_SERVER['PHP_SELF']) == 'traderFeedback.php' ? 'class="active"' : '' ?>><i class="fas fa-comments"></i>Feedbacks</a>
        <a href="traderCalendar.php" <?= basename($_SERVER['PHP_SELF']) == 'traderCalendar.php' ? 'class="active"' : '' ?>><i class="fas fa-calendar-alt"></i>Calendar</a>
        <a href="traderAccount.php" <?= basename($_SERVER['PHP_SELF']) == 'traderAccount.php' ? 'class="active"' : '' ?>><i class="fas fa-user-circle"></i>Account</a>
        <a href="traderShop.php" <?= basename($_SERVER['PHP_SELF']) == 'traderShop.php' ? 'class="active"' : '' ?>><i class="fas fa-store"></i>Shop</a>
    </nav>

    <!-- Top Navigation -->
    <header id="topnav">
        <div class="brand">
            <img src="../images/CoolCarters Sample 2.svg" alt="CoolCarters Logo" class="logo">
            <h2>CoolCarters</h2>
        </div>
        <div class="user">
            <span class="trader-name"><?= htmlspecialchars($username) ?></span>
            <a href="../login.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </header>

    <!-- Hamburger Toggle -->
    <button id="toggleBtn"><i class="fas fa-bars"></i></button>

    <script>
        document.getElementById('toggleBtn').addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                document.body.classList.toggle('sidebar-open');
            } else {
                document.body.classList.toggle('sidebar-collapsed');
            }
        });

        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.getElementById('toggleBtn');
            if (window.innerWidth <= 768 && !sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
                document.body.classList.remove('sidebar-open');
            }
        });
    </script>

</body>

</html>
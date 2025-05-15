<?php
// calendar.php — show all errors
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

// Determine month and year from query params or default to current
$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
$month = isset($_GET['month']) ? intval($_GET['month']) : date('n');
// Normalize month/year
if ($month < 1) { $month = 12; $year--; }
if ($month > 12) { $month = 1; $year++; }

// // Fetch events from DB
// try {
//     $pdo = new PDO('mysql:host=localhost;dbname=coolcarters;charset=utf8mb4', 'dbuser', 'dbpass');
//     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//     $stmt = $pdo->prepare('SELECT event_id, title, DATE_FORMAT(event_date, "%Y-%m-%d") AS event_date FROM calendar_events WHERE YEAR(event_date)=? AND MONTH(event_date)=?');
//     $stmt->execute([$year, $month]);
//     $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
// } catch (PDOException $e) {
//     $events = [];
// }

// Calendar calculations
$firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
$daysInMonth = date('t', $firstDayOfMonth);
$startDay = date('w', $firstDayOfMonth); // 0 (Sun) - 6 (Sat)

// Prev/next month links
$prevMonth = $month - 1;
$prevYear = $year;
if ($prevMonth < 1) { $prevMonth = 12; $prevYear--; }
$nextMonth = $month + 1;
$nextYear = $year;
if ($nextMonth > 12) { $nextMonth = 1; $nextYear++; }

// Month names
$monthName = date('F', $firstDayOfMonth);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>CoolCarters Admin Dashboard - Calendar</title>
  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root { --primary: #4e73df; --light: #f8f9fc; --dark: #343a40; --text: #858796; --bg: #fff; }
    *, *::before, *::after { box-sizing: border-box; margin:0; padding:0; }
    body { font-family: 'Poppins', sans-serif; background: var(--light); color: var(--dark); overflow-x: hidden; }
    /* Sidebar */
    #sidebar { position: fixed; top:0; left:0; bottom:0; width:240px; background: var(--dark); padding:1.5rem 1rem; }
    #sidebar h2, #sidebar a { color: var(--bg); }
    #sidebar a { display:flex; align-items:center; text-decoration:none; padding:.75rem; border-radius:.5rem; margin-bottom:.5rem; color: var(--text); transition:.2s; }
    #sidebar a:hover, #sidebar a.active { background: var(--primary); color: var(--bg); }
    #sidebar a i { width:20px; margin-right:.75rem; }
    /* Top nav */
    #topnav { position: fixed; top:0; left:240px; right:0; height:60px; background: var(--bg); display:flex; align-items:center; padding:0 2rem; box-shadow:0 2px 4px rgba(0,0,0,.1); z-index:10; }
    #toggleBtn { font-size:1.4rem; margin-right:1rem; cursor:pointer; color: var(--text); }
    #topnav h1 { flex:1; font-size:1.2rem; font-weight:500; }
    #topnav .user { font-size:.9rem; }
    /* Main content */
    #main { margin:80px 2rem 60px 260px; }
    .calendar-nav { display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; }
    .calendar-nav a { color: var(--primary); text-decoration:none; font-size:1.2rem; }
    .calendar-nav .title { font-size:1.5rem; font-weight:500; }
    .calendar { width:100%; border-collapse:collapse; }
    .calendar th, .calendar td { border:1px solid #ddd; width:14.28%; height:100px; vertical-align: top; padding:.5rem; background: var(--bg); }
    .calendar th { background: var(--primary); color: var(--bg); }
    .event { display:block; background:var(--primary); color:#fff; padding:2px 4px; margin-top:4px; border-radius:3px; font-size:.75rem; text-decoration:none; }
    /* Footer */
    #footer { position: fixed; bottom:0; left:240px; right:0; background: var(--bg); padding:.75rem 2rem; box-shadow:0 -2px 4px rgba(0,0,0,.05); display:flex; justify-content:space-between; align-items:center; }
    .socials a { color:var(--text); margin-left:1rem; font-size:1.2rem; }
  </style>
</head>
<body>
  <nav id="sidebar">
    <h2>CoolCarters</h2>
    <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i>Dashboard</a>
    <a href="traders.php"><i class="fas fa-store"></i>Traders</a>
    <a href="customers.php"><i class="fas fa-users"></i>Customers</a>
    <a href="products.php"><i class="fas fa-box"></i>Products</a>
    <a href="reports.php"><i class="fas fa-chart-bar"></i>Reports</a>
    <a href="feedbacks.php"><i class="fas fa-comments"></i>Feedbacks</a>
    <a href="calendar.php?month=<?=$month?>&year=<?=$year?>" class="active"><i class="fas fa-calendar-alt"></i>Calendar</a>
    <a href="administration.php"><i class="fas fa-cogs"></i>Administration</a>
  </nav>
  <div id="topnav"><div id="toggleBtn"><i class="fas fa-bars"></i></div><h1>Admin Dashboard</h1><div class="user"><i class="fas fa-user-circle"></i>Administrator</div></div>
  <section id="main">
    <div class="section-header">Calendar</div>
    <div class="calendar-nav">
      <a href="?month=<?=$prevMonth?>&year=<?=$prevYear?>"><i class="fas fa-chevron-left"></i></a>
      <span class="title"><?php echo "$monthName $year"; ?></span>
      <a href="?month=<?=$nextMonth?>&year=<?=$nextYear?>"><i class="fas fa-chevron-right"></i></a>
    </div>
    <table class="calendar">
      <thead>
        <tr><th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th></tr>
      </thead>
      <tbody>
        <?php
        $day = 1;
        for ($row=0; $row<6; $row++) {
            echo '<tr>';
            for ($col=0; $col<7; $col++) {
                if ($row === 0 && $col < $startDay) {
                    echo '<td></td>';
                } elseif ($day > $daysInMonth) {
                    echo '<td></td>';
                } else {
                    $date = sprintf('%04d-%02d-%02d', $year, $month, $day);
                    echo "<td><strong>$day</strong>";
                    if (isset($events) && is_array($events)) {
                        foreach ($events as $ev) {
                            if ($ev['event_date'] === $date) {
                                echo '<a href="#" class="event">' . htmlspecialchars($ev['title']) . '</a>';
                            }
                        }
                    }
                    echo '</td>';
                    $day++;
                }
            }
            echo '</tr>';
            if ($day > $daysInMonth) break;
        }
        ?>
      </tbody>
    </table>
  </section>
  <footer id="footer"><p>&copy; 2025 CoolCarter. All rights reserved</p><div class="socials"><a href="#"><i class="fab fa-instagram"></i></a><a href="#"><i class="fab fa-facebook-f"></i></a><a href="#"><i class="fas fa-times"></i></a></div></footer>
  <script>
    document.getElementById('toggleBtn').onclick = function(){ document.getElementById('sidebar').classList.toggle('collapsed'); };
  </script>
</body>
</html>

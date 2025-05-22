<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// require_once '../connection.php';

// $trader_id = $_SESSION['trader_id'] ?? 0;
$slots = [];

if ($trader_id) {
    $sql = "
        SELECT cs.Slot_Date, cs.Slot_Time
        FROM COLLECTION_SLOT cs
        INNER JOIN ORDER_TABLE o ON o.fk1_Slot_ID = cs.Slot_ID
        INNER JOIN SHOP s ON s.Trader_ID = :trader_id
        INNER JOIN PRODUCT p ON p.fk1_Shop_ID = s.Shop_ID
        INNER JOIN PRODUCT_ORDER po ON po.Product_ID = p.Product_ID AND po.Order_ID = o.Order_ID
        WHERE ROWNUM <= 100
    ";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":trader_id", $trader_id);
    oci_execute($stmt);

    while ($row = oci_fetch_assoc($stmt)) {
        $dateKey = $row['SLOT_DATE'];
        $slots[$dateKey][] = $row['SLOT_TIME'];
    }
    oci_free_statement($stmt);
}

$year = $_GET['year'] ?? date('Y');
$month = $_GET['month'] ?? date('n');
$year = (int)$year;
$month = (int)$month;

if ($month < 1) { $month = 12; $year--; }
if ($month > 12) { $month = 1; $year++; }

$firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
$daysInMonth = date('t', $firstDayOfMonth);
$startDay = date('w', $firstDayOfMonth);

$prevMonth = $month - 1;
$prevYear = $year;
if ($prevMonth < 1) { $prevMonth = 12; $prevYear--; }
$nextMonth = $month + 1;
$nextYear = $year;
if ($nextMonth > 12) { $nextMonth = 1; $nextYear++; }

$monthName = date('F', $firstDayOfMonth);
?>
<?php include 'navbar.php'; ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('toggleBtn');
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function () {
            document.body.classList.toggle('sidebar-collapsed');
        });
    }
});
</script>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Trader Calendar</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    :root {
      --primary: #4e73df;
      --secondary: #2e59d9;
      --light: #f8f9fc;
      --dark: #343a40;
      --text: #858796;
      --bg: #fff;
    }
    body {
      font-family: 'Poppins', sans-serif;
      background: var(--light);
      color: var(--dark);
      margin: 0;
      padding: 0;
    }
    main#main {
      margin-left: 240px;
      padding: 80px 1rem 100px 1rem;
      transition: margin-left 0.3s ease;
    }
    body.sidebar-collapsed main#main {
      margin-left: 70px;
    }
    .calendar-container {
      background: var(--bg);
      padding: 1.5rem;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .calendar-nav {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem;
    }
    .calendar-nav a {
      color: var(--primary);
      text-decoration: none;
      font-size: 1.2rem;
      padding: 0.5rem 1rem;
      border-radius: 4px;
      background-color: #eef2ff;
    }
    .calendar-nav .title {
      font-size: 1.5rem;
      font-weight: 500;
      color: var(--dark);
    }
    .calendar {
      width: 100%;
      border-collapse: collapse;
    }
    .calendar th,
    .calendar td {
      padding: 0.75rem;
      text-align: center;
      border: 1px solid #e0e0e0;
      height: 100px;
      vertical-align: top;
      background: var(--bg);
    }
    .calendar th {
      background: var(--primary);
      color: white;
    }
    .today {
      background-color: #e6f7ff !important;
      position: relative;
    }
    .today .day-number::after {
      content: '';
      position: absolute;
      top: 5px;
      right: 5px;
      width: 8px;
      height: 8px;
      background-color: var(--primary);
      border-radius: 50%;
    }
    .slots {
      font-size: 0.75rem;
      margin-top: 0.5rem;
      color: var(--text);
      text-align: left;
    }
    @media (max-width: 768px) {
      main#main {
        margin-left: 0;
        padding: 70px 1rem 100px 1rem;
      }
      .calendar th,
      .calendar td {
        font-size: 0.7rem;
        padding: 0.5rem;
        height: 80px;
      }
      .calendar-nav {
        flex-direction: column;
        gap: 1rem;
      }
      .calendar-nav .title {
        font-size: 1.25rem;
      }
    }
  </style>
</head>
<body>
<main id="main">
  <div class="calendar-container">
    <div class="calendar-nav">
      <a href="?month=<?= $prevMonth ?>&year=<?= $prevYear ?>"><i class="fas fa-chevron-left"></i> Previous</a>
      <span class="title"><?= "$monthName $year" ?></span>
      <a href="?month=<?= $nextMonth ?>&year=<?= $nextYear ?>">Next <i class="fas fa-chevron-right"></i></a>
    </div>
    <div class="overflow-x-auto">
      <table class="calendar">
        <thead>
          <tr><th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th></tr>
        </thead>
        <tbody>
          <?php
          $day = 1;
          for ($row = 0; $row < 6; $row++) {
              echo '<tr>';
              for ($col = 0; $col < 7; $col++) {
                  if ($row === 0 && $col < $startDay) {
                      echo '<td></td>';
                  } elseif ($day > $daysInMonth) {
                      echo '<td></td>';
                  } else {
                      $dateStr = sprintf('%04d-%02d-%02d', $year, $month, $day);
                      $timestamp = strtotime($dateStr);
                      $todayClass = ($dateStr == date('Y-m-d')) ? 'today' : '';
                      $isValidSlotDay = $timestamp >= strtotime('+1 day') && in_array(date('w', $timestamp), [3, 4, 5]);
                      echo "<td class='$todayClass'>";
                      echo "<div class='day-number'>$day</div>";
                      if ($isValidSlotDay) {
                          if (isset($slots[$dateStr])) {
                              echo "<div class='slots text-green-700 font-medium'>" . implode('<br>', $slots[$dateStr]) . "</div>";
                          } else {
                              echo "<div class='slots text-green-600 text-xs'>Valid Pickup Day</div>";
                          }
                      } else {
                          echo "<div class='slots text-red-400 text-xs italic'>Unavailable</div>";
                      }
                      echo "</td>";
                      $day++;
                  }
              }
              echo '</tr>';
              if ($day > $daysInMonth) break;
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</main>
<?php include 'traderFooter.php'; ?>
</body>
</html>

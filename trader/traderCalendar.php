<?php
// traderCalendar.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Determine month and year from query params or default to current
$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
$month = isset($_GET['month']) ? intval($_GET['month']) : date('n');
// Normalize month/year
if ($month < 1) { $month = 12; $year--; }
if ($month > 12) { $month = 1; $year++; }

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
    <style>
        :root {
            --primary: #4e73df;
            --secondary: #2e59d9;
            --light: #f8f9fc;
            --dark: #343a40;
            --text: #858796;
            --bg: #fff;
        }
        
        /* Main content area */
        #main {
            margin: 80px 2rem 60px 260px;
            transition: margin-left 0.3s ease;
        }
        #main.expanded {
            margin-left: 260px;
        }
        
        /* Calendar styles */
        .calendar-container {
            background: var(--bg);
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 1.5rem;
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
            transition: background-color 0.2s;
        }
        .calendar-nav a:hover {
            background-color: rgba(78, 115, 223, 0.1);
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
        .calendar th {
            background: var(--primary);
            color: white;
            padding: 0.75rem;
            text-align: center;
            font-weight: 500;
        }
        .calendar td {
            border: 1px solid #e0e0e0;
            height: 100px;
            vertical-align: top;
            padding: 0.5rem;
            background: var(--bg);
        }
        .calendar td:hover {
            background-color: #f9f9f9;
        }
        .day-number {
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        .today {
            background-color: #e6f7ff !important;
            position: relative;
        }
        .today .day-number:after {
            content: '';
            position: absolute;
            top: 5px;
            right: 5px;
            width: 8px;
            height: 8px;
            background-color: var(--primary);
            border-radius: 50%;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            #main {
                margin: 80px 1rem 60px 1rem;
            }
            #main.expanded {
                margin-left: 240px;
            }
            .calendar td {
                height: 70px;
                font-size: 0.9rem;
            }
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
    <?php include 'navbar.php'; ?>

    <!-- Main content -->
    <main id="main">
        <div class="calendar-container">
            <h1>Trader Calendar</h1>
            
            <div class="calendar-nav">
                <a href="?month=<?= $prevMonth ?>&year=<?= $prevYear ?>">
                    <i class="fas fa-chevron-left"></i> Previous
                </a>
                <span class="title"><?= "$monthName $year" ?></span>
                <a href="?month=<?= $nextMonth ?>&year=<?= $nextYear ?>">
                    Next <i class="fas fa-chevron-right"></i>
                </a>
            </div>
            
            <!-- Calendar -->
            <table class="calendar">
                <thead>
                    <tr>
                        <th>Sun</th>
                        <th>Mon</th>
                        <th>Tue</th>
                        <th>Wed</th>
                        <th>Thu</th>
                        <th>Fri</th>
                        <th>Sat</th>
                    </tr>
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
                                $date = sprintf('%04d-%02d-%02d', $year, $month, $day);
                                $todayClass = ($date == date('Y-m-d')) ? 'today' : '';
                                echo "<td class='$todayClass'>";
                                echo "<div class='day-number'>$day</div>";
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
        </div>
    </main>

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
<?php
session_start();

// Sample feedback data for trader
$feedbacks = [
    [
        'fid' => 101,
        'customer_name' => 'John Doe',
        'email' => 'john@example.com',
        'message' => 'Great products! The quality was excellent and delivery was fast.',
        'submitted_at' => '2023-05-15 14:30',
        'rating' => 5
    ],
    [
        'fid' => 102,
        'customer_name' => 'Jane Smith',
        'email' => 'jane@example.com',
        'message' => "The item didn't match the description. Please improve quality control.",
        'submitted_at' => '2023-05-10 09:15',
        'rating' => 2
    ],
    [
        'fid' => 103,
        'customer_name' => 'Mike Johnson',
        'email' => 'mike@example.com',
        'message' => 'Good service overall, but shipping took longer than expected.',
        'submitted_at' => '2023-05-05 16:45',
        'rating' => 4
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CoolCarters Trader Dashboard - Feedbacks</title>
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        .controls .search {
            position: relative;
            flex: 1;
        }
        .controls .search input {
            width: 100%;
            padding: 0.5rem 2.5rem 0.5rem 0.75rem;
            border: 1px solid #ddd;
            border-radius: 0.25rem;
            transition: border-color 0.2s;
        }
        .controls .search input:focus {
            outline: none;
            border-color: var(--primary);
        }
        .controls .search i {
            position: absolute;
            top: 50%;
            right: 0.75rem;
            transform: translateY(-50%);
            color: var(--text);
        }
        /* Table */
        .table-wrap {
            overflow-x: auto;
            margin-top: 1rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: var(--bg);
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
        }
        th, td {
            border: 1px solid #e3e6f0;
            padding: 0.75rem;
            text-align: left;
        }
        th {
            background: #f8f9fc;
            color: var(--dark);
            font-weight: 600;
        }
        tbody tr:hover {
            background: #f8f9fc;
        }
        .rating {
            display: flex;
            gap: 0.25rem;
        }
        .rating .star {
            color: #ffc107;
        }
        .rating .empty {
            color: #e3e6f0;
        }
        .action-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            margin-right: 0.5rem;
            color: var(--text);
            transition: color 0.2s;
        }
        .action-btn:hover {
            color: var(--primary);
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
                margin: 80px 1rem 60px 1rem;
            }
            #main.expanded {
                margin-left: 260px;
            }
            footer { 
                left: 0;
            }
            footer.expanded {
                left: 240px;
            }
        }
    </style>
</head>
<body>
    <!-- Include the navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Main content -->
    <section id="main">
        <div class="section-header">Customer Feedbacks</div>
        <div class="controls">
            <div class="search">
                <input id="searchInput" type="text" placeholder="Search feedback...">
                <i class="fas fa-search"></i>
            </div>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Rating</th>
                        <th>Message</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="feedbackTable">
                    <?php if (!empty($feedbacks)): ?>
                        <?php foreach ($feedbacks as $f): ?>
                        <tr>
                            <td><?= htmlspecialchars($f['fid']); ?></td>
                            <td>
                                <div><?= htmlspecialchars($f['customer_name']); ?></div>
                                <small class="text-gray-500"><?= htmlspecialchars($f['email']); ?></small>
                            </td>
                            <td>
                                <div class="rating">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star <?= $i <= $f['rating'] ? 'star' : 'empty' ?>"></i>
                                    <?php endfor; ?>
                                </div>
                            </td>
                            <td><?= nl2br(htmlspecialchars($f['message'])); ?></td>
                            <td><?= htmlspecialchars($f['submitted_at']); ?></td>
                            <td>
                                <button class="action-btn" title="Reply"><i class="fas fa-reply"></i></button>
                                <button class="action-btn" title="Mark as read"><i class="fas fa-check"></i></button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align:center; padding:1rem;">
                                No feedback received yet.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 CoolCarter. All rights reserved</p>
        <div class="socials">
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Search functionality
            const searchInput = document.getElementById('searchInput');
            searchInput.addEventListener('input', function() {
                const filter = this.value.toLowerCase();
                document.querySelectorAll('#feedbackTable tr').forEach(row => {
                    if (row.cells) {
                        row.style.display = row.innerText.toLowerCase().includes(filter) ? '' : 'none';
                    }
                });
            });

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
</body>
</html>
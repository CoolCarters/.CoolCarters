<?php
session_start();

// Initialize variables
$shop_name = $shop_location = $shop_description = "";
$errors = [];
$success = false;

// Form submission handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize inputs
    $shop_name = trim($_POST["shop_name"] ?? "");
    $shop_location = trim($_POST["shop_location"] ?? "");
    $shop_description = trim($_POST["shop_description"] ?? "");

    // Validation
    if (empty($shop_name)) {
        $errors["shop_name"] = "Shop name is required";
    }

    if (empty($shop_location)) {
        $errors["shop_location"] = "Location is required";
    }

    // If no errors, process the form
    if (empty($errors)) {
        $success = true;
        // In a real application, you would save to database here
        // Then redirect to the shops list page
        // header("Location: traderShop.php");
        // exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Shop - CoolCarters</title>
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
        
        /* Main Content */
        .main-content {
            margin-left: 240px;
            padding: 5rem 2rem 2rem;
            transition: margin-left 0.3s ease;
        }
        .main-content.expanded {
            margin-left: 260px;
        }
        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        .content-header h1 {
            font-size: 1.5rem;
            color: var(--dark);
        }
        .back-button {
            padding: 0.5rem 1rem;
            background: var(--primary);
            color: var(--bg);
            text-decoration: none;
            border-radius: 4px;
            transition: 0.3s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .back-button:hover {
            background: var(--secondary);
        }

        /* Shop Form Styles */
        .shop-form-container {
            max-width: 800px;
            margin: 0 auto;
            background: var(--bg);
            padding: 2rem;
            border-radius: .75rem;
            box-shadow: 0 4px 6px rgba(0,0,0,.05);
        }
        
        .form-title {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            color: var(--primary);
            text-align: center;
        }
        
        .form-group {
            margin-bottom: 1.25rem;
        }
        
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--dark);
        }
        
        input[type="text"],
        textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 0.375rem;
            font-size: 1rem;
            transition: border-color 0.2s;
        }
        
        input[type="text"]:focus,
        textarea:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 2px rgba(78, 115, 223, 0.25);
        }
        
        textarea {
            min-height: 100px;
            resize: vertical;
        }
        
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 1.5rem;
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            font-weight: 500;
        }
        
        .btn-primary {
            background-color: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: var(--secondary);
        }
        
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        
        .error {
            color: #e74a3b;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        
        .success {
            color: #1cc88a;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            text-align: center;
            padding: 0.75rem;
            background-color: rgba(28, 200, 138, 0.1);
            border-radius: 0.375rem;
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
            .main-content { 
                margin-left: 0;
                padding: 5rem 1rem 2rem;
            }
            .main-content.expanded {
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

    <!-- Main Content -->
    <main class="main-content">
        <div class="content-header">
            <h1>Add New Shop</h1>
            <a href="traderShop.php" class="back-button">
                <i class="fas fa-arrow-left"></i>
                Back to Shops
            </a>
        </div>

        <div class="shop-form-container">
            <?php if ($success): ?>
                <div class="success">Shop added successfully! Redirecting...</div>
                <script>
                    setTimeout(function() {
                        window.location.href = 'traderShop.php';
                    }, 2000);
                </script>
            <?php endif; ?>

            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="shopForm" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="shop_name">Shop Name</label>
                    <input type="text" id="shop_name" name="shop_name" 
                           value="<?php echo htmlspecialchars($shop_name); ?>" required>
                    <?php if (isset($errors["shop_name"])): ?>
                        <span class="error"><?php echo $errors["shop_name"]; ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="shop_logo">Shop Logo</label>
                    <input type="file" id="shop_logo" name="shop_logo" accept="image/*">
                </div>

                <div class="form-group">
                    <label for="shop_location">Location</label>
                    <input type="text" id="shop_location" name="shop_location" 
                           value="<?php echo htmlspecialchars($shop_location); ?>" required>
                    <?php if (isset($errors["shop_location"])): ?>
                        <span class="error"><?php echo $errors["shop_location"]; ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="shop_description">Description</label>
                    <textarea id="shop_description" name="shop_description"><?php echo htmlspecialchars($shop_description); ?></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" name="submit" class="btn btn-primary">Add Shop</button>
                    <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
            </form>
        </div>
    </main>

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
            const toggleBtn = document.getElementById('toggleBtn');
            const sidebar = document.getElementById('sidebar');
            const main = document.querySelector('.main-content');
            const footer = document.querySelector('footer');
            
            toggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('collapsed');
                main.classList.toggle('expanded');
                footer.classList.toggle('expanded');
            });

            // Preview image before upload
            const shopLogoInput = document.getElementById('shop_logo');
            if (shopLogoInput) {
                shopLogoInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            // Create preview image if it doesn't exist
                            let preview = document.getElementById('logo-preview');
                            if (!preview) {
                                preview = document.createElement('img');
                                preview.id = 'logo-preview';
                                preview.style.maxWidth = '200px';
                                preview.style.maxHeight = '200px';
                                preview.style.marginTop = '10px';
                                preview.style.borderRadius = '4px';
                                shopLogoInput.parentNode.appendChild(preview);
                            }
                            preview.src = event.target.result;
                        }
                        reader.readAsDataURL(file);
                    }
                });
            }
        });
    </script>
</body>
</html>
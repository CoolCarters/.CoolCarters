<?php
session_start();

// Sample data
$shops = [
    ['id' => 1, 'name' => 'Shop1'],
    ['id' => 2, 'name' => 'Shop2']
];
$_SESSION['username'] = 'Username';

// Initialize variables
$product_name = $price = $min_order = $max_order = $category = $stock = $allergy_warnings = $description = "";
$errors = [];
$success = false;

// Form submission handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if this is a reset request
    if (isset($_POST['reset'])) {
        // Clear all fields
        $product_name = $price = $min_order = $max_order = $category = $stock = $allergy_warnings = $description = "";
        $errors = [];
    } else {
        // Validate and sanitize inputs
        $product_name = trim($_POST["product_name"] ?? "");
        $price = trim($_POST["price"] ?? "");
        $min_order = trim($_POST["min_order"] ?? "");
        $max_order = trim($_POST["max_order"] ?? "");
        $category = trim($_POST["category"] ?? "");
        $stock = trim($_POST["stock"] ?? "");
        $allergy_warnings = trim($_POST["allergy_warnings"] ?? "");
        $description = trim($_POST["description"] ?? "");

        // Validation
        if (empty($product_name)) {
            $errors["product_name"] = "Product name is required";
        }

        if (empty($price)) {
            $errors["price"] = "Price is required";
        } elseif (!is_numeric($price) || $price <= 0) {
            $errors["price"] = "Price must be a positive number";
        }

        if (empty($min_order)) {
            $errors["min_order"] = "Minimum order is required";
        } elseif (!ctype_digit($min_order) || $min_order <= 0) {
            $errors["min_order"] = "Minimum order must be a positive integer";
        }

        if (!empty($max_order) && (!ctype_digit($max_order) || $max_order <= 0)) {
            $errors["max_order"] = "Maximum order must be a positive integer";
        }

        if (!empty($stock) && (!ctype_digit($stock) || $stock < 0)) {
            $errors["stock"] = "Stock must be a non-negative integer";
        }

        // If no errors, process the form
        if (empty($errors)) {
            $success = true;
            // Clear all fields after successful submission
            $product_name = $price = $min_order = $max_order = $category = $stock = $allergy_warnings = $description = "";

            // In a real application, you would save to database here
            // Then redirect or clear the form as we're doing here
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>CoolCarters - Add Product</title>
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4e73df;
            --secondary: #2e59d9;
            --light: #f8f9fc;
            --dark: #343a40;
            --text: #858796;
            --bg: #fff;
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

        #sidebar a:hover,
        #sidebar a.active {
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
            background: var(--bg);
            display: flex;
            align-items: center;
            padding: 0 2rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, .1);
            z-index: 10;
        }

        #topnav #toggleBtn {
            font-size: 1.4rem;
            margin-right: 1rem;
            cursor: pointer;
            color: var(--text);
        }

        #topnav h1 {
            flex: 1;
            font-size: 1.2rem;
            color: var(--dark);
            font-weight: 500;
        }

        #topnav .user {
            font-size: .9rem;
            color: var(--text);
        }

        /* Main content */
        #main {
            margin: 80px 2rem 60px 260px;
        }

        .section-header {
            background: #ccc;
            padding: .75rem 1rem;
            font-weight: 500;
            font-size: 1rem;
        }

        /* Product Form Styles */
        .product-form-container {
            max-width: 800px;
            margin: 0 auto;
            background: var(--bg);
            padding: 2rem;
            border-radius: .75rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, .05);
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

        .form-row {
            display: flex;
            gap: 1.25rem;
        }

        .form-col {
            flex: 1;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--dark);
        }

        input[type="text"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 0.375rem;
            font-size: 1rem;
            transition: border-color 0.2s;
        }

        input[type="text"]:focus,
        input[type="number"]:focus,
        textarea:focus,
        select:focus {
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
        #footer {
            position: fixed;
            bottom: 0;
            left: 240px;
            right: 0;
            background: var(--bg);
            padding: .75rem 2rem;
            box-shadow: 0 -2px 4px rgba(0, 0, 0, .05);
            display: flex;
            align-items: center;
            justify-content: space-between;
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

        #footer p {
            font-size: .85rem;
            color: var(--text);
        }

        /* Responsive */
        @media (max-width: 768px) {
            #sidebar {
                width: 0;
                padding: 0;
            }

            #topnav {
                left: 0;
            }

            #main {
                margin-left: 1rem;
            }

            #footer {
                left: 0;
            }

            .form-row {
                flex-direction: column;
                gap: 0;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <nav id="sidebar">
        <h2>CoolCarters</h2>
        <a href="traderInterface.php"><i class="fas fa-tachometer-alt"></i>Dashboard</a>
        <a href="viewProduct.php" class="active"><i class="fas fa-box"></i>Products</a>
        <a href="traderReports.php"><i class="fas fa-chart-bar"></i>Reports</a>
        <a href="traderFeedbacks.php"><i class="fas fa-comments"></i>Feedbacks</a>
        <a href="traderCalendar.php"><i class="fas fa-calendar-alt"></i>Calendar</a>
        <a href="traderAccount.php"><i class="fas fa-user-circle"></i>Account</a>
        <a href="traderShop.php"><i class="fas fa-store"></i>Shop</a>
    </nav>

    <!-- Top nav -->
    <header id="topnav">
        <div id="toggleBtn"><i class="fas fa-bars"></i></div>
        <h1>Add Product</h1>
        <div class="user"><i class="fas fa-user-circle"></i> <?= $_SESSION['username'] ?></div>
    </header>

    <!-- Main content -->
    <section id="main">
        <div class="section-header">Add New Product</div>

        <div class="product-form-container">
            <?php if ($success): ?>
                <div class="success">Product added successfully!</div>
            <?php endif; ?>

            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="productForm">
                <div class="form-group">
                    <label for="product_name">Product Name</label>
                    <input type="text" id="product_name" name="product_name"
                        value="<?php echo htmlspecialchars($product_name); ?>" required>
                    <?php if (isset($errors["product_name"])): ?>
                        <span class="error"><?php echo $errors["product_name"]; ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="price">Price</label>
                            <input type="text" id="price" name="price"
                                value="<?php echo htmlspecialchars($price); ?>" required>
                            <?php if (isset($errors["price"])): ?>
                                <span class="error"><?php echo $errors["price"]; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="min_order">Minimum Order</label>
                            <input type="number" id="min_order" name="min_order"
                                value="<?php echo htmlspecialchars($min_order); ?>" required>
                            <?php if (isset($errors["min_order"])): ?>
                                <span class="error"><?php echo $errors["min_order"]; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="max_order">Maximum Order</label>
                            <input type="number" id="max_order" name="max_order"
                                value="<?php echo htmlspecialchars($max_order); ?>">
                            <?php if (isset($errors["max_order"])): ?>
                                <span class="error"><?php echo $errors["max_order"]; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="category">Category</label>
                            <select id="category" name="category">
                                <option value="">Select Category</option>
                                <option value="Food" <?php echo ($category === "Food") ? "selected" : ""; ?>>Food</option>
                                <option value="Beverages" <?php echo ($category === "Beverages") ? "selected" : ""; ?>>Beverages</option>
                                <option value="Snacks" <?php echo ($category === "Snacks") ? "selected" : ""; ?>>Snacks</option>
                                <option value="Other" <?php echo ($category === "Other") ? "selected" : ""; ?>>Other</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="stock">Stock</label>
                    <input type="number" id="stock" name="stock"
                        value="<?php echo htmlspecialchars($stock); ?>">
                    <?php if (isset($errors["stock"])): ?>
                        <span class="error"><?php echo $errors["stock"]; ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="allergy_warnings">Allergy Warnings</label>
                    <textarea id="allergy_warnings" name="allergy_warnings"><?php echo htmlspecialchars($allergy_warnings); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="description">Product Description</label>
                    <textarea id="description" name="description"><?php echo htmlspecialchars($description); ?></textarea>
                </div>

                <div class="form-group">
                    <label>Product Image</label>
                    <input type="file" id="product_image" name="product_image">
                </div>

                <div class="form-actions">
                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                    <button type="reset" name="reset" class="btn btn-secondary" onclick="resetForm()">Reset</button>
                </div>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer id="footer">
        <p>© 2025 CoolCarter. All rights reserved</p>
        <div class="socials">
            <span>Keep us connected:</span>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fa-brands fa-x"></i></a>
        </div>
    </footer>

    <script>
        // Toggle sidebar
        document.getElementById('toggleBtn').onclick = () => {
            document.getElementById('sidebar').classList.toggle('collapsed');
            document.getElementById('topnav').classList.toggle('expanded');
            document.getElementById('main').classList.toggle('expanded');
            document.getElementById('footer').classList.toggle('expanded');
        };

        // Reset form function
        function resetForm() {
            document.getElementById('productForm').reset();
            // Clear file input (which isn't cleared by standard reset)
            document.getElementById('product_image').value = '';
        }

        // If form was successfully submitted, reset it
        <?php if ($success): ?>
            document.addEventListener('DOMContentLoaded', function() {
                resetForm();
            });
        <?php endif; ?>
    </script>
</body>

</html>
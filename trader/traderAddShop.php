<?php
session_start();
require_once '../connection.php'; // Ensure DB connection

// Initialize variables
$shop_name = '';
$shop_location = '';
$shop_description = '';
$errors = [];
$success = false;

// Form submission handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $shop_name = trim($_POST["shop_name"] ?? '');
    $shop_location = trim($_POST["shop_location"] ?? '');
    $shop_description = trim($_POST["shop_description"] ?? '');

    if (empty($shop_name)) $errors["shop_name"] = "Shop name is required";
    if (empty($shop_location)) $errors["shop_location"] = "Location is required";

    if (empty($errors)) {
        $trader_id = $_SESSION['trader_id'] ?? 0;

        if ($trader_id == 0) {
            $errors["auth"] = "Trader session not set.";
        } else {
            $sql = "INSERT INTO SHOP (Shop_Name, Location, Description, Trader_ID)
                    VALUES (:shop_name, :shop_location, :shop_description, :trader_id)";
            
            $stmt = oci_parse($conn, $sql);
            oci_bind_by_name($stmt, ":shop_name", $shop_name);
            oci_bind_by_name($stmt, ":shop_location", $shop_location);
            oci_bind_by_name($stmt, ":shop_description", $shop_description);
            oci_bind_by_name($stmt, ":trader_id", $trader_id);

            if (oci_execute($stmt)) {
                $success = true;
                $shop_name = $shop_location = $shop_description = ''; // Clear fields after success
            } else {
                $e = oci_error($stmt);
                $errors["db"] = "❌ Error inserting shop: " . $e['message'];
            }

            oci_free_statement($stmt);
        }
    }
}
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
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Add New Shop - CoolCarters</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
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

    body {
      font-family: 'Poppins', sans-serif;
      background: var(--light);
      color: var(--dark);
      min-height: 100vh;
      padding-bottom: 80px;
      margin: 0;
    }

    .main-content {
      margin-left: 240px;
      padding: 5rem 2rem 2rem;
      transition: margin-left 0.3s ease;
    }

    .content-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 1rem;
      margin-bottom: 2rem;
    }

    .back-button {
      padding: 0.5rem 1rem;
      background: var(--primary);
      color: var(--bg);
      border-radius: 4px;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
    }

    .back-button:hover {
      background: var(--secondary);
    }

    .shop-form-container {
      max-width: 800px;
      margin: auto;
      background: var(--bg);
      padding: 2rem;
      border-radius: .75rem;
      box-shadow: 0 4px 6px rgba(0,0,0,.05);
    }

    .form-group {
      margin-bottom: 1.25rem;
    }

    label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 500;
    }

    input[type="text"],
    textarea {
      width: 100%;
      padding: 0.75rem;
      border: 1px solid #ddd;
      border-radius: 0.375rem;
      font-size: 1rem;
    }

    input[type="text"]:focus,
    textarea:focus {
      border-color: var(--primary);
      outline: none;
      box-shadow: 0 0 0 2px rgba(78, 115, 223, 0.25);
    }

    textarea {
      resize: vertical;
      min-height: 100px;
    }

    .form-actions {
      display: flex;
      flex-wrap: wrap;
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

    @media (max-width: 768px) {
      .main-content {
        margin-left: 0;
        padding: 4rem 1rem 2rem;
      }
    }

    @media (max-width: 480px) {
      .form-actions {
        flex-direction: column;
        align-items: stretch;
      }

      .btn {
        width: 100%;
        text-align: center;
      }

      .shop-form-container {
        padding: 1.25rem;
      }
    }
  </style>
</head>
<body>

<main class="main-content">
  <div class="content-header">
    <h1>Add New Shop</h1>
    <a href="traderShop.php" class="back-button">
      <i class="fas fa-arrow-left"></i> Back to Shops
    </a>
  </div>

  <div class="shop-form-container">
    <?php if ($success): ?>
      <div class="success">✅ Shop added successfully! Redirecting...</div>
      <script>
        setTimeout(() => window.location.href = 'traderShop.php', 2000);
      </script>
    <?php endif; ?>

    <?php if (isset($errors['db'])): ?>
      <div class="error"><?= $errors['db'] ?></div>
    <?php endif; ?>

    <form method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
      <div class="form-group">
        <label for="shop_name">Shop Name</label>
        <input type="text" id="shop_name" name="shop_name" value="<?= htmlspecialchars($shop_name) ?>" required>
        <?php if (isset($errors["shop_name"])): ?>
          <span class="error"><?= $errors["shop_name"] ?></span>
        <?php endif; ?>
      </div>

      <div class="form-group">
        <label for="shop_location">Location</label>
        <input type="text" id="shop_location" name="shop_location" value="<?= htmlspecialchars($shop_location) ?>" required>
        <?php if (isset($errors["shop_location"])): ?>
          <span class="error"><?= $errors["shop_location"] ?></span>
        <?php endif; ?>
      </div>

      <div class="form-group">
        <label for="shop_description">Description</label>
        <textarea id="shop_description" name="shop_description"><?= htmlspecialchars($shop_description) ?></textarea>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Add Shop</button>
        <button type="reset" class="btn btn-secondary">Reset</button>
      </div>
    </form>
  </div>
</main>

<?php include 'traderFooter.php'; ?>

</body>
</html>

<?php
session_start();
require_once '../connection.php';

$errors = [];
$success = false;
$product_name = $price = $min_order = $max_order = $category = $stock = $allergy_warnings = $shop_id = "";

$trader_id = $_SESSION['trader_id'] ?? 0;
$shops = [];

// Fetch shops for the trader
if ($trader_id) {
  $shop_stmt = oci_parse($conn, "SELECT SHOP_ID, SHOP_NAME FROM SHOP WHERE TRADER_ID = :trader_id");
  oci_bind_by_name($shop_stmt, ":trader_id", $trader_id);
  if (oci_execute($shop_stmt)) {
    while ($row = oci_fetch_assoc($shop_stmt)) {
      $shops[] = ['id' => $row['SHOP_ID'], 'name' => $row['SHOP_NAME']];
    }
  } else {
    $e = oci_error($shop_stmt);
    $errors['shop_id'] = "❌ Failed to load shops: " . $e['message'];
  }
  oci_free_statement($shop_stmt);
}

if ($_SERVER["REQUEST_METHOD"] === 'POST') {
  $product_name     = trim($_POST['product_name'] ?? '');
  $price            = trim($_POST['price'] ?? '');
  $min_order        = trim($_POST['min_order'] ?? '');
  $max_order        = trim($_POST['max_order'] ?? '');
  $category         = trim($_POST['category'] ?? '');
  $stock            = trim($_POST['stock'] ?? '');
  $allergy_warnings = trim($_POST['allergy_warnings'] ?? '');
  $shop_id          = trim($_POST['shop_id'] ?? '');
  $category_id      = 0;

  // Validation
  if (empty($product_name))   $errors['product_name']   = 'Product name is required.';
  if (!is_numeric($price) || $price <= 0) $errors['price'] = 'Price must be a positive number.';
  if (!ctype_digit($min_order)) $errors['min_order'] = 'Min order must be a positive integer.';
  if (!ctype_digit($max_order)) $errors['max_order'] = 'Max order must be a positive integer.';
  if (!ctype_digit($stock))     $errors['stock']     = 'Stock must be a positive integer.';
  if (empty($shop_id))          $errors['shop_id']   = 'Please select a shop.';
  if (empty($category))         $errors['category']  = 'Please select a category.';

  // Lookup category ID
  if (empty($errors['category'])) {
    $cat_stmt = oci_parse($conn, "SELECT CATEGORY_ID FROM CATEGORY WHERE CATEGORY_NAME = :cat_name");
    oci_bind_by_name($cat_stmt, ":cat_name", $category);
    oci_execute($cat_stmt);
    if ($cat_row = oci_fetch_assoc($cat_stmt)) {
      $category_id = $cat_row['CATEGORY_ID'];
    } else {
      $errors['category'] = 'Invalid category. Make sure your CATEGORY table has this entry.';
    }
    oci_free_statement($cat_stmt);
  }

  // Handle image upload
  $image_blob = null;
  if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
    $tmp  = $_FILES['product_image']['tmp_name'];
    $mime = mime_content_type($tmp);
    $allowed = ['image/jpeg', 'image/png', 'image/gif'];
    if (in_array($mime, $allowed)) {
      $image_blob = file_get_contents($tmp);
    }
  }

  if (empty($errors)) {
    // Insert with BLOB
    $sql = "INSERT INTO PRODUCT (PRODUCT_ID, PRODUCT_NAME, PRICE, PRODUCT_IMAGE, MINIMUM_ORDER, MAXIMUM_ORDER, CATEGORY, STOCK, ALLERGY_WARNING, FK1_SHOP_ID, FK2_CATEGORY_ID) 
                VALUES (seq_product.NEXTVAL, :name, :price, EMPTY_BLOB(), :min, :max, :cat, :stk, :allw, :shop, :cid)
                RETURNING PRODUCT_IMAGE INTO :blob";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ':name', $product_name);
    oci_bind_by_name($stmt, ':price', $price);

    $lob = oci_new_descriptor($conn, OCI_D_LOB);
    // Bind LOB using oci_bind_by_name
    oci_bind_by_name($stmt, ':blob', $lob, -1, OCI_B_BLOB);

    oci_bind_by_name($stmt, ':min', $min_order);
    oci_bind_by_name($stmt, ':max', $max_order);
    oci_bind_by_name($stmt, ':cat', $category);
    oci_bind_by_name($stmt, ':stk', $stock);
    oci_bind_by_name($stmt, ':allw', $allergy_warnings);
    oci_bind_by_name($stmt, ':shop', $shop_id);
    oci_bind_by_name($stmt, ':cid', $category_id);

    $ok = oci_execute($stmt, OCI_DEFAULT);
    if ($ok && $lob && $image_blob) {
      $lob->save($image_blob);
      oci_commit($conn);
      $success = true;
      // reset form
      $product_name = $price = $min_order = $max_order = $category = $stock = $allergy_warnings = $shop_id = "";
    } else {
      $e = oci_error($stmt);
      $errors['database'] = 'DB Error: ' . $e['message'];
    }
    if ($lob) $lob->free();
    oci_free_statement($stmt);
  }
}
?>

<?php include 'navbar.php'; ?>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('toggleBtn');
    if (toggleBtn) {
      toggleBtn.addEventListener('click', function() {
        document.body.classList.toggle('sidebar-collapsed');
      });
    }
  });
</script>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Add Product - CoolCarters</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    :root {
      --primary: #4e73df;
      --text: #858796;
      --light: #f8f9fc;
      --dark: #343a40;
    }

    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background-color: var(--light);
      transition: margin-left 0.3s ease;
      padding-bottom: 80px;
    }

    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      width: 240px;
      height: 100%;
      background-color: var(--primary);
      color: #fff;
      padding: 1rem;
      box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
      transition: width 0.3s ease;
      z-index: 999;
    }

    .sidebar button {
      background: none;
      border: none;
      font-size: 1.5rem;
      color: white;
      cursor: pointer;
      margin-bottom: 1rem;
    }

    .sidebar ul {
      list-style: none;
      padding: 0;
    }

    .sidebar ul li a {
      display: block;
      color: #fff;
      text-decoration: none;
      padding: 10px 0;
    }

    body.sidebar-collapsed .sidebar {
      width: 80px;
    }

    body.sidebar-collapsed .main-content {
      margin-left: 80px;
    }

    .main-content {
      margin-left: 240px;
      padding: 5rem 2rem 2rem;
      padding-top: 6rem;
      transition: margin-left 0.3s ease;
    }

    .main-content h2 {
      margin-top: 1rem;
    }

    form {
      background: white;
      padding: 2rem;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, .1);
    }

    .form-group {
      margin-bottom: 1rem;
    }

    label {
      font-weight: 600;
      display: block;
      margin-bottom: 0.5rem;
    }

    input,
    select,
    textarea {
      width: 100%;
      padding: 0.5rem;
      border-radius: 4px;
      border: 1px solid #ccc;
    }

    .form-actions {
      margin-top: 1.5rem;
      display: flex;
      gap: 1rem;
    }

    .btn {
      padding: 0.5rem 1rem;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-weight: bold;
    }

    .btn-primary {
      background: var(--primary);
      color: white;
    }

    .btn-secondary {
      background: var(--text);
      color: white;
    }

    .error {
      color: red;
      font-size: 0.9rem;
    }

    @media (max-width: 768px) {
      .sidebar {
        left: -240px;
      }

      body.sidebar-collapsed .sidebar {
        left: 0;
        width: 240px;
      }

      .main-content {
        margin-left: 0;
      }

      body.sidebar-collapsed .main-content {
        margin-left: 240px;
      }

      .form-actions {
        flex-direction: column;
      }

      .btn {
        width: 100%;
      }
    }
  </style>
</head>

<body>
  <main class="main-content">
    <h2>Add Product</h2>
    <?php if (!empty($errors['database'])): ?><p class="error"><?= $errors['database'] ?></p><?php endif; ?>
    <?php if ($success): ?><div class="alert-success">Product added successfully!</div><?php endif; ?>
    <form method="post" enctype="multipart/form-data">
      <form method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="productForm">
        <div class="form-group">
          <label for="product_name">Product Name</label>
          <input type="text" id="product_name" name="product_name" value="<?php echo htmlspecialchars($product_name); ?>" required>
          <?php if (isset($errors["product_name"])): ?>
            <span class="error"><?php echo $errors["product_name"]; ?></span>
          <?php endif; ?>
        </div>

        <div class="form-row">
          <div class="form-col">
            <div class="form-group">
              <label for="price">Price</label>
              <input type="text" id="price" name="price" value="<?php echo htmlspecialchars($price); ?>" required>
              <?php if (isset($errors["price"])): ?>
                <span class="error"><?php echo $errors["price"]; ?></span>
              <?php endif; ?>
            </div>
          </div>
          <div class="form-col">
            <div class="form-group">
              <label for="min_order">Minimum Order</label>
              <input type="number" id="min_order" name="min_order" value="<?php echo htmlspecialchars($min_order); ?>" required>
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
              <input type="number" id="max_order" name="max_order" value="<?php echo htmlspecialchars($max_order); ?>">
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
                <option value="Butchers" <?php echo ($category === "Butchers") ? "selected" : ""; ?>>Butchers</option>
                <option value="Greengrocer" <?php echo ($category === "Greengrocer") ? "selected" : ""; ?>>Greengrocer</option>
                <option value="Fishmonger" <?php echo ($category === "Fishmonger") ? "selected" : ""; ?>>Fishmonger</option>
                <option value="Bakery" <?php echo ($category === "Bakery") ? "selected" : ""; ?>>Bakery</option>
                <option value="Delicatessen" <?php echo ($category === "Delicatessen") ? "selected" : ""; ?>>Delicatessen</option>
              </select>
              <?php if (isset($errors["category"])): ?>
                <span class="error"><?php echo $errors["category"]; ?></span>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label for="stock">Stock</label>
          <input type="number" id="stock" name="stock" value="<?php echo htmlspecialchars($stock); ?>">
          <?php if (isset($errors["stock"])): ?>
            <span class="error"><?php echo $errors["stock"]; ?></span>
          <?php endif; ?>
        </div>

        <div class="form-group">
          <label for="allergy_warnings">Allergy Warnings</label>
          <textarea id="allergy_warnings" name="allergy_warnings"><?php echo htmlspecialchars($allergy_warnings); ?></textarea>
        </div>

        <div class="form-group">
          <label for="shop_id">Select Shop</label>
          <select id="shop_id" name="shop_id" required>
            <option value="">-- Select a Shop --</option>
            <?php foreach ($shops as $shop): ?>
              <option value="<?php echo $shop['id']; ?>" <?php echo ($shop_id == $shop['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($shop['name']); ?></option>
            <?php endforeach; ?>
          </select>
          <?php if (isset($errors["shop_id"])): ?>
            <span class="error"><?php echo $errors["shop_id"]; ?></span>
          <?php endif; ?>
        </div>

        <div class="form-group">
          <label>Product Image</label>
          <input type="file" id="product_image" name="product_image">
        </div>

        <div class="form-actions">
          <button type="submit" name="submit" class="btn btn-primary">Submit</button>
          <button type="reset" name="reset" class="btn btn-secondary">Reset</button>
        </div>
      </form>
  </main>

  <?php include 'traderFooter.php'; ?>

</body>

</html>
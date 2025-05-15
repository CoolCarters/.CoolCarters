<?php
// add_admin.php — show all errors
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Administrator</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body { font-family:'Poppins',sans-serif; background:#f8f9fc; color:#343a40; margin:0; }
    form { max-width:400px; margin:100px auto; background:#fff; padding:2rem; border-radius:.5rem; box-shadow:0 4px 6px rgba(0,0,0,.1); }
    h2 { margin-bottom:1rem; }
    .field { margin-bottom:1rem; }
    .field label { display:block; margin-bottom:.5rem; font-weight:500; }
    .field input, .field select { width:100%; padding:.5rem; border:1px solid #ccc; border-radius:.25rem; }
    .actions { text-align:right; }
    .actions button { padding:.5rem 1rem; background:#4e73df; color:#fff; border:none; border-radius:.25rem; cursor:pointer; }
    .errors { background:#ffe6e6; border:1px solid #ffcccc; padding:1rem; margin-bottom:1rem; border-radius:.25rem; }
    .errors li { margin-bottom:.5rem; }
  </style>
</head>
<body>
  <form method="post">
    <h2>Add Administrator</h2>
    <?php if (!empty($errors)): ?>
      <div class="errors">
        <ul>
          <?php foreach ($errors as $e): ?>
            <li><?= htmlspecialchars($e) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>
    <div class="field">
      <label for="username">Username</label>
      <input type="text" id="username" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
    </div>
    <div class="field">
      <label for="email">Email</label>
      <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
    </div>
    <div class="field">
      <label for="password">Password</label>
      <input type="password" id="password" name="password">
    </div>
    <div class="field">
      <label for="role">Role</label>
      <select id="role" name="role">
        <option value="admin" <?= (($_POST['role'] ?? '')==='admin')?'selected':'' ?>>Admin</option>
        <option value="superadmin" <?= (($_POST['role'] ?? '')==='superadmin')?'selected':'' ?>>Super Admin</option>
      </select>
    </div>
    <div class="field">
      <label for="status">Status</label>
      <select id="status" name="status">
        <option value="active" <?= (($_POST['status'] ?? '')==='active')?'selected':'' ?>>Active</option>
        <option value="inactive" <?= (($_POST['status'] ?? '')==='inactive')?'selected':'' ?>>Inactive</option>
      </select>
    </div>
    <div class="actions">
      <button type="submit">Create</button>
    </div>
  </form>
</body>
</html>

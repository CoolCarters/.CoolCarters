<?php
session_start();
require_once "../connection.php";

if (!isset($_SESSION['trader_id'])) {
    header("Location: ../login.php");
    exit;
}

$traderId = $_SESSION['trader_id'];
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $current = $_POST['current_password'] ?? '';
    $new     = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    $sql = "SELECT PASSWORD FROM USER_TABLE WHERE USER_ID = :uid";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":uid", $traderId);
    oci_execute($stmt);
    $row = oci_fetch_assoc($stmt);
    oci_free_statement($stmt);

    if (!$row || $row['PASSWORD'] !== $current) {
        $message = "❌ Incorrect current password.";
    } elseif ($new !== $confirm) {
        $message = "❌ New password and confirm password do not match.";
    } else {
        $update = oci_parse($conn, "UPDATE USER_TABLE SET PASSWORD = :new WHERE USER_ID = :uid");
        oci_bind_by_name($update, ":new", $new);
        oci_bind_by_name($update, ":uid", $traderId);
        if (oci_execute($update)) {
            $message = "✅ Password updated successfully.";
        } else {
            $message = "❌ Failed to update password.";
        }
        oci_free_statement($update);
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
  <title>Change Password - CoolCarters</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans">


<div class="max-w-2xl mx-auto bg-white shadow rounded-xl mt-20 mb-24 p-8">
  <h2 class="text-2xl font-bold text-blue-600 mb-6 text-center">Change Password</h2>

  <?php if ($message): ?>
    <div class="mb-4 text-center font-medium text-sm <?= str_starts_with($message, '✅') ? 'text-green-600' : 'text-red-600' ?>">
      <?= htmlspecialchars($message) ?>
    </div>
  <?php endif; ?>

  <form method="POST" class="space-y-5">
    <div>
      <label for="current_password" class="block font-medium text-gray-700">Current Password</label>
      <input type="password" name="current_password" id="current_password"
             class="w-full mt-1 px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
    </div>
    <div>
      <label for="new_password" class="block font-medium text-gray-700">New Password</label>
      <input type="password" name="new_password" id="new_password"
             class="w-full mt-1 px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
    </div>
    <div>
      <label for="confirm_password" class="block font-medium text-gray-700">Confirm New Password</label>
      <input type="password" name="confirm_password" id="confirm_password"
             class="w-full mt-1 px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
    </div>
    <div class="text-center">
      <button type="submit"
              class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded shadow">
        Update Password
      </button>
    </div>
  </form>
</div>

<?php include 'traderFooter.php'; ?>

</body>
</html>

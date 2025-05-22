<?php
session_start();
require_once "../connection.php";

if (!isset($_SESSION['trader_id'])) {
    header("Location: ../login.php");
    exit;
}

$traderId = $_SESSION['trader_id'];
$errors = [];
$success = false;

// Fetch trader and user info
$sql = "SELECT U.FIRST_NAME, U.LAST_NAME, U.EMAIL, U.ADDRESS, T.COMPANY_NAME, T.ACTION
        FROM USER_TABLE U
        JOIN TRADER T ON U.USER_ID = T.USER_ID
        WHERE U.USER_ID = :id";
$stmt = oci_parse($conn, $sql);
oci_bind_by_name($stmt, ":id", $traderId);
oci_execute($stmt);
$trader = oci_fetch_assoc($stmt);
oci_free_statement($stmt);

if (!$trader) {
    // Invalid session/user deleted
    session_destroy();
    header("Location: ../login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $first   = trim($_POST['first_name'] ?? '');
    $last    = trim($_POST['last_name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $company = trim($_POST['company_name'] ?? '');

    // Very basic validation
    if ($first === "" || $last === "" || $email === "" || $company === "") {
        $errors[] = "Please fill in all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    } else {
        // Update USER_TABLE
        $userSql = "UPDATE USER_TABLE SET FIRST_NAME = :first, LAST_NAME = :last, EMAIL = :email, ADDRESS = :addr WHERE USER_ID = :id";
        $userStmt = oci_parse($conn, $userSql);
        oci_bind_by_name($userStmt, ":first", $first);
        oci_bind_by_name($userStmt, ":last", $last);
        oci_bind_by_name($userStmt, ":email", $email);
        oci_bind_by_name($userStmt, ":addr", $address);
        oci_bind_by_name($userStmt, ":id", $traderId);
        $userExec = oci_execute($userStmt);
        oci_free_statement($userStmt);

        // Update TRADER
        $traderSql = "UPDATE TRADER SET COMPANY_NAME = :company WHERE USER_ID = :id";
        $traderStmt = oci_parse($conn, $traderSql);
        oci_bind_by_name($traderStmt, ":company", $company);
        oci_bind_by_name($traderStmt, ":id", $traderId);
        $traderExec = oci_execute($traderStmt);
        oci_free_statement($traderStmt);

        if ($userExec && $traderExec) {
            $success = true;
            // Refresh info
            $sql = "SELECT U.FIRST_NAME, U.LAST_NAME, U.EMAIL, U.ADDRESS, T.COMPANY_NAME, T.ACTION
                    FROM USER_TABLE U
                    JOIN TRADER T ON U.USER_ID = T.USER_ID
                    WHERE U.USER_ID = :id";
            $stmt = oci_parse($conn, $sql);
            oci_bind_by_name($stmt, ":id", $traderId);
            oci_execute($stmt);
            $trader = oci_fetch_assoc($stmt);
            oci_free_statement($stmt);
        } else {
            $errors[] = "❌ Error updating profile. Please try again.";
        }
    }
}

oci_close($conn);
?>

<?php include 'navbar.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Trader Profile — CoolCarters</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body.sidebar-collapsed #layoutWrapper {
      margin-left: 4rem;
    }
    @media (min-width: 1024px) {
      body:not(.sidebar-collapsed) #layoutWrapper {
        margin-left: 16rem;
      }
    }
  </style>
</head>
<body class="bg-gray-100 font-sans">

<div id="layoutWrapper" class="transition-all duration-300">

  <main class="max-w-5xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8 px-6 pt-12 pb-24 transition-all duration-300">

    <!-- Sidebar -->
    <aside class="bg-white rounded-xl p-6 shadow self-start">
      <h2 class="text-xl font-bold text-blue-600 text-center mb-4">Trader Detail</h2>
      <ul class="text-gray-700 text-sm space-y-2">
        <li><strong>Name:</strong> <?= htmlspecialchars($trader['FIRST_NAME'] . ' ' . $trader['LAST_NAME']) ?></li>
        <li><strong>Email:</strong> <?= htmlspecialchars($trader['EMAIL']) ?></li>
        <li><strong>Password:</strong> <span class="text-gray-400">*********</span></li>
        <li><strong>Address:</strong> <?= htmlspecialchars($trader['ADDRESS']) ?></li>
        <li><strong>Company:</strong> <?= htmlspecialchars($trader['COMPANY_NAME']) ?></li>
        <li><strong>Status:</strong> <?= htmlspecialchars($trader['ACTION']) ?></li>
      </ul>
    </aside>

    <!-- Editable Form -->
    <section class="lg:col-span-2 bg-white rounded-xl p-8 shadow self-start">
      <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Update Your Information</h2>
      </div>

      <!-- Change Password Button -->
      <div class="text-center mb-4">
        <button onclick="location.href='traderChangePassword.php'"
                class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded shadow">
          Change Password
        </button>
      </div>

      <?php if (!empty($errors)): ?>
        <div class="bg-red-100 text-red-700 rounded px-4 py-2 mb-4">
          <?php foreach ($errors as $err): ?>
            <div><?= htmlspecialchars($err) ?></div>
          <?php endforeach; ?>
        </div>
      <?php elseif ($success): ?>
        <div class="bg-green-100 text-green-700 rounded px-4 py-2 mb-4">
          Profile updated successfully!
        </div>
      <?php endif; ?>

      <form method="POST" class="space-y-5">
        <div class="flex items-center gap-2">
          <label class="w-32 font-medium" for="first_name">First name:</label>
          <input name="first_name" id="first_name" type="text"
                 value="<?= htmlspecialchars($trader['FIRST_NAME']) ?>"
                 class="flex-1 px-4 py-2 border rounded focus:ring-2 focus:ring-blue-500" required>
        </div>
        <div class="flex items-center gap-2">
          <label class="w-32 font-medium" for="last_name">Last name:</label>
          <input name="last_name" id="last_name" type="text"
                 value="<?= htmlspecialchars($trader['LAST_NAME']) ?>"
                 class="flex-1 px-4 py-2 border rounded focus:ring-2 focus:ring-blue-500" required>
        </div>
        <div class="flex items-center gap-2">
          <label class="w-32 font-medium" for="email">Email:</label>
          <input name="email" id="email" type="email"
                 value="<?= htmlspecialchars($trader['EMAIL']) ?>"
                 class="flex-1 px-4 py-2 border rounded focus:ring-2 focus:ring-blue-500" required>
        </div>
        <div class="flex items-center gap-2">
          <label class="w-32 font-medium" for="address">Address:</label>
          <input name="address" id="address" type="text"
                 value="<?= htmlspecialchars($trader['ADDRESS']) ?>"
                 class="flex-1 px-4 py-2 border rounded focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="flex items-center gap-2">
          <label class="w-32 font-medium" for="company_name">Company:</label>
          <input name="company_name" id="company_name" type="text"
                 value="<?= htmlspecialchars($trader['COMPANY_NAME']) ?>"
                 class="flex-1 px-4 py-2 border rounded focus:ring-2 focus:ring-blue-500" required>
        </div>
        <div class="text-center">
          <button type="submit"
                  class="mt-4 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">
            Save Changes
          </button>
        </div>
      </form>
    </section>

  </main>
</div>

<?php include 'traderFooter.php'; ?>

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

</body>
</html>

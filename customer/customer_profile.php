<?php
session_start();
require_once __DIR__ . '/../connection.php';

if (!isset($_SESSION['user_id'], $_SESSION['role']) || $_SESSION['role'] !== 'customer') {
    header('Location: ../login.php');
    exit;
}

$userId = $_SESSION['user_id'];

$sql = "
    SELECT FIRST_NAME, LAST_NAME, EMAIL, GENDER, PHONE_NUMBER, ADDRESS, PROFILE_PIC
    FROM USER_TABLE
    WHERE USER_ID = :b_userid
";
$stmt = oci_parse($conn, $sql);
oci_bind_by_name($stmt, 'b_userid', $userId);
oci_execute($stmt);
$user = oci_fetch_assoc($stmt) ?: [
    'FIRST_NAME' => '', 'LAST_NAME' => '', 'EMAIL' => '',
    'GENDER' => '', 'PHONE_NUMBER' => '', 'ADDRESS' => '', 'PROFILE_PIC' => null
];

$base64Image = '';
if (!empty($user['PROFILE_PIC'])) {
    $base64Image = 'data:image/png;base64,' . base64_encode($user['PROFILE_PIC']->load());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Your Profile — CoolCarters</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
  <?php
    include "../homeNavbar.php";
  ?>

  <main class="max-w-5xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8 p-6">
    <!-- Sidebar -->
    <aside class="bg-white rounded-xl p-6 shadow">
      <div class="text-center mb-4">
        <div class="bg-gray-300 rounded-full w-20 h-20 mx-auto"></div>
        <p class="mt-2 font-semibold">
          <?= htmlspecialchars(
               ($user['FIRST_NAME']  ?? '') .
               ' ' .
               ($user['LAST_NAME']   ?? '')
             ) ?>
        </p>
        <p class="text-gray-500 text-sm">
          <?= htmlspecialchars($user['EMAIL'] ?? '') ?>
        </p>
      </div>
      <nav class="space-y-2 text-gray-700">
        <a href="index.php"    class="block hover:text-blue-600">My Products</a>
        <a href="cart.php"     class="block hover:text-blue-600">My Cart</a>
        <a href="wishlist.php" class="block hover:text-blue-600">Wishlist</a>
        <a href="#"            class="block hover:text-blue-600">Settings</a>
      </nav>
    </aside>

    <!-- Profile Content -->
    <section class="lg:col-span-2 bg-white rounded-xl p-8 shadow">
      <div class="text-center mb-6">
        <h2 class="text-2xl font-bold">
          Hello, <?= htmlspecialchars($user['FIRST_NAME'] ?? '') ?>!
        </h2>
        <p class="text-gray-500">
          <?= htmlspecialchars($user['EMAIL']        ?? '') ?> |
          <?= htmlspecialchars($user['PHONE_NUMBER'] ?? '') ?> |
          <?= htmlspecialchars($user['GENDER']       ?? '') ?>
        </p>
        <button onclick="location.href='change_password.php'"
                class="mt-4 bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">
          Change password
        </button>
      </div>

      <form action="update_profile.php" method="post" class="grid grid-cols-1 gap-4">
        <div class="flex items-center gap-2">
          <label class="w-32 font-medium">First name:</label>
          <input name="first_name" type="text"
                 value="<?= htmlspecialchars($user['FIRST_NAME'] ?? '') ?>"
                 class="flex-1 px-4 py-2 border rounded focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="flex items-center gap-2">
          <label class="w-32 font-medium">Last name:</label>
          <input name="last_name" type="text"
                 value="<?= htmlspecialchars($user['LAST_NAME'] ?? '') ?>"
                 class="flex-1 px-4 py-2 border rounded focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="flex items-center gap-2">
          <label class="w-32 font-medium">Email:</label>
          <input name="email" type="email"
                 value="<?= htmlspecialchars($user['EMAIL'] ?? '') ?>"
                 class="flex-1 px-4 py-2 border rounded focus:ring-2 focus:ring-blue-500">
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
</body>
</html>

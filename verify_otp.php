<?php
session_start();
require_once 'connection.php';

if (!isset($_SESSION['signup'])) {
    echo "❌ Session expired. Please sign up again.";
    exit;
}

$signup = $_SESSION['signup'];
$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $otpEntered = implode('', array_map('trim', [
        $_POST['otp1'] ?? '', $_POST['otp2'] ?? '', $_POST['otp3'] ?? '',
        $_POST['otp4'] ?? '', $_POST['otp5'] ?? '', $_POST['otp6'] ?? ''
    ]));

    if ($otpEntered === (string)$signup['otp']) {
        // Insert into Oracle DB
        $stmt = null;
        if ($signup['role'] === "customer") {
            $sql = "
              INSERT INTO CUSTOMER_DETAILS (FIRST_NAME, LAST_NAME, EMAIL, PASSWORD, GENDER)
              VALUES (:firstName, :lastName, :email, :password, :gender)";
            $stmt = oci_parse($conn, $sql);
            oci_bind_by_name($stmt, ":firstName", $signup['firstName']);
            oci_bind_by_name($stmt, ":lastName",  $signup['lastName']);
            oci_bind_by_name($stmt, ":email",     $signup['email']);
            oci_bind_by_name($stmt, ":password",  $signup['password']);
            oci_bind_by_name($stmt, ":gender",    $signup['gender']);

        } elseif ($signup['role'] === "trader") {
            $sql = "
              INSERT INTO TRADER_DETAILS (FIRST_NAME, LAST_NAME, EMAIL, PASSWORD, COMPANY_NAME)
              VALUES (:firstName, :lastName, :email, :password, :companyName)";
            $stmt = oci_parse($conn, $sql);
            oci_bind_by_name($stmt, ":firstName",    $signup['firstName']);
            oci_bind_by_name($stmt, ":lastName",     $signup['lastName']);
            oci_bind_by_name($stmt, ":email",        $signup['email']);
            oci_bind_by_name($stmt, ":password",     $signup['password']);
            oci_bind_by_name($stmt, ":companyName",  $signup['companyName']);
        }

        if ($stmt && oci_execute($stmt, OCI_COMMIT_ON_SUCCESS)) {
            unset($_SESSION['signup']);
            echo "✅ Registration complete. You can now <a href='login.php'>log in</a>.";
        } else {
            $e = oci_error($stmt);
            $error = "❌ Error inserting user: " . htmlentities($e['message']);
        }
    } else {
        $error = "❌ Invalid OTP. Please try again.";
    }
}
?>

<!-- OTP Form -->
<!DOCTYPE html>
<html>
<head>
  <title>Verify OTP</title>
  <link rel="stylesheet" href="./css/output.css">
  <style>
    .otp-input {
      width: 3rem;
      height: 3rem;
      text-align: center;
      font-size: 1.25rem;
      border: 1px solid #ccc;
      border-radius: 0.5rem;
    }
    .otp-input:focus {
      outline: none;
      border-color: #2563eb;
      box-shadow: 0 0 0 1px #2563eb;
    }
  </style>
</head>
<body class="bg-gray-100 text-center p-10">
  <div class="bg-white max-w-md mx-auto p-6 rounded shadow">
    <h2 class="text-2xl font-semibold mb-4">Email Verification</h2>
    <p class="mb-2">Enter the OTP sent to <b><?= htmlspecialchars($signup['email']) ?></b></p>

    <?php if (!empty($error)): ?>
      <p class="text-red-600 mb-4"><?= $error ?></p>
    <?php endif; ?>

    <form method="post">
      <div class="flex justify-center gap-2 mb-6">
        <?php for ($i = 1; $i <= 6; $i++): ?>
          <input type="text" name="otp<?= $i ?>" maxlength="1" class="otp-input" required oninput="moveNext(this, <?= $i ?>)">
        <?php endfor; ?>
      </div>
      <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Verify</button>
    </form>
  </div>

  <script>
    function moveNext(input, index) {
      const next = document.getElementsByName('otp' + (index + 1))[0];
      if (input.value.length === 1 && next) {
        next.focus();
      }
    }

    // Auto-focus on first input
    window.onload = () => document.getElementsByName('otp1')[0].focus();
  </script>
</body>
</html>

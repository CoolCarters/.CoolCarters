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
        $gender = ucfirst(strtolower(trim($signup['gender'] ?? 'Other')));
        $role = ucfirst(strtolower(trim($signup['role'] ?? '')));
        $user_id = null;

        // Step 1: Insert into USER_TABLE
        $sql_user = "
            INSERT INTO USER_TABLE (
                First_Name, Last_Name, Gender, Email, Password, Phone_Number, Address, Role, CreatedAt, UpdatedAt
            ) VALUES (
                :firstName, :lastName, :gender, :email, :password, :phone, :address, :role, SYSTIMESTAMP, SYSTIMESTAMP
            )
            RETURNING User_ID INTO :user_id
        ";
        $stmt_user = oci_parse($conn, $sql_user);
        oci_bind_by_name($stmt_user, ":firstName", $signup['firstName']);
        oci_bind_by_name($stmt_user, ":lastName", $signup['lastName']);
        oci_bind_by_name($stmt_user, ":gender", $gender);
        oci_bind_by_name($stmt_user, ":email", $signup['email']);
        oci_bind_by_name($stmt_user, ":password", $signup['password']);
        oci_bind_by_name($stmt_user, ":phone", $signup['phone']);
        oci_bind_by_name($stmt_user, ":address", $signup['address']);
        oci_bind_by_name($stmt_user, ":role", $role);
        oci_bind_by_name($stmt_user, ":user_id", $user_id, -1, OCI_B_INT);

        if (oci_execute($stmt_user)) {
            // Step 2: Insert into Subtype table
            if ($role === "Customer") {
                $sql_cust = "INSERT INTO CUSTOMER (User_ID, Loyalty_Points) VALUES (:user_id, 0)";
                $stmt_cust = oci_parse($conn, $sql_cust);
                oci_bind_by_name($stmt_cust, ":user_id", $user_id);

                if (oci_execute($stmt_cust)) {
                    unset($_SESSION['signup']);
                    header("Location: login.php");
                    exit;
                } else {
                    $e = oci_error($stmt_cust);
                    $error = "❌ Error inserting customer: " . htmlentities($e['message']);
                }
            } elseif ($role === "Trader") {
                $sql_trader = "INSERT INTO TRADER (User_ID, Company_Name) VALUES (:user_id, :companyName)";
                $stmt_trader = oci_parse($conn, $sql_trader);
                oci_bind_by_name($stmt_trader, ":user_id", $user_id);
                oci_bind_by_name($stmt_trader, ":companyName", $signup['companyName']);

                if (oci_execute($stmt_trader)) {
                    unset($_SESSION['signup']);
                    header("Location: login.php");
                    exit;
                } else {
                    $e = oci_error($stmt_trader);
                    $error = "❌ Error inserting trader: " . htmlentities($e['message']);
                }
            }
        } else {
            $e = oci_error($stmt_user);
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

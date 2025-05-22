<?php
session_start();
require_once 'connection.php';
require_once 'mailer.php'; // handles sendEmail()

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $role      = trim($_POST["role"]);
    $firstName = trim($_POST["firstName"]);
    $lastName  = trim($_POST["lastName"]);
    $email     = trim($_POST["email"]);
    $password  = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $gender    = trim($_POST["gender"] ?? 'Other');
    $phone     = trim($_POST["phone"] ?? '');
    $address   = trim($_POST["address"] ?? '');
    $company   = trim($_POST["companyName"] ?? '');

    // Validate role
    $role = ucfirst(strtolower($role));
    if (!in_array($role, ['Customer', 'Trader'])) {
        echo "❌ Error: Invalid role.";
        exit;
    }

    // 1) Check for duplicate email in USER_TABLE
    $sqlCheck = "SELECT COUNT(*) AS CNT FROM USER_TABLE WHERE EMAIL = :email";
    $chkStmt = oci_parse($conn, $sqlCheck);
    oci_bind_by_name($chkStmt, ":email", $email);
    oci_execute($chkStmt);
    $row = oci_fetch_assoc($chkStmt);
    oci_free_statement($chkStmt);

    if ($row['CNT'] > 0) {
        echo "❌ Error: That email address is already registered.";
        oci_close($conn);
        exit;
    }

    // 2) Generate 6-digit OTP
    $otp = rand(100000, 999999);

    // 3) Store signup data in session
    $_SESSION['signup'] = [
        'role'         => $role,
        'firstName'    => $firstName,
        'lastName'     => $lastName,
        'email'        => $email,
        'password'     => $password,
        'gender'       => ucfirst(strtolower($gender)),
        'phone'        => $phone,
        'address'      => $address,
        'otp'          => $otp
    ];

    if ($role === "Trader") {
        $_SESSION['signup']['companyName'] = $company;
    }

    // 4) Send OTP email
    $subject = "Your CoolCarters OTP Code";
    $body = "Hello $firstName,\n\nYour OTP code is: $otp\n\nPlease enter this code to verify your account.\n\n— CoolCarters Team";

    if (sendEmail($email, $subject, $body)) {
        header("Location: verify_otp.php");
        exit;
    } else {
        echo "❌ Failed to send OTP. Please try again.";
    }

    oci_close($conn);
}
?>

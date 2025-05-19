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

    // 1) Check duplicate email in CUSTOMER_DETAILS + TRADER_DETAILS
    $sqlCheck = "
        SELECT COUNT(*) AS CNT
        FROM (
            SELECT EMAIL FROM CUSTOMER_DETAILS WHERE EMAIL = :email
            UNION ALL
            SELECT EMAIL FROM TRADER_DETAILS WHERE EMAIL = :email
        )
    ";
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

    // 3) Store all signup data in session (to be used in verify_otp.php)
    $_SESSION['signup'] = [
        'role'      => $role,
        'firstName' => $firstName,
        'lastName'  => $lastName,
        'email'     => $email,
        'password'  => $password,
        'otp'       => $otp
    ];

    // Additional fields for each role
    if ($role === "customer") {
        $_SESSION['signup']['gender'] = $_POST["gender"];
    } elseif ($role === "trader") {
        $_SESSION['signup']['companyName'] = trim($_POST["companyName"]);
    } else {
        echo "❌ Error: Invalid role.";
        oci_close($conn);
        exit;
    }

    // 4) Send OTP email
    $subject = "Your CoolCarters OTP Code";
    $body = "Hello $firstName,\n\nYour OTP code is: $otp\n\nPlease enter this code to verify your account.\n\n— CoolCarters Team";

    if (sendEmail($email, $subject, $body)) {
        // Redirect to OTP verification page
        header("Location: verify_otp.php");
        exit;
    } else {
        echo "❌ Failed to send OTP. Please try again.";
    }

    oci_close($conn);
}
?>

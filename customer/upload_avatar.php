<?php
// upload_avatar.php
session_start();
require_once __DIR__ . '/connection.php';

if (!isset($_SESSION['user_id'], $_SESSION['role']) || $_SESSION['role'] !== 'customer') {
    header('Location: login.php');
    exit;
}
$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['avatar'])) {
    $fp   = fopen($_FILES['avatar']['tmp_name'], 'rb');
    $size = filesize($_FILES['avatar']['tmp_name']);

    $sql  = "UPDATE USER_TABLE SET PROFILE_PIC = :blob WHERE USER_ID = :uid";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, 'blob', $fp, -1, OCI_B_BLOB);
    oci_bind_by_name($stmt, 'uid',  $userId);
    $ok = oci_execute($stmt, OCI_DEFAULT);
    if ($ok) {
        oci_commit($conn);
        header('Location: customer_profile.php');
        exit;
    } else {
        $e = oci_error($stmt);
        die("Upload failed: " . htmlspecialchars($e['message']));
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Upload Avatar</title></head>
<body>
  <form method="post" enctype="multipart/form-data">
    <label>Choose avatar (JPEG/PNG):</label>
    <input type="file" name="avatar" accept="image/*" required>
    <button type="submit">Upload</button>
  </form>
</body>
</html>

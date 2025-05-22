<?php
session_start();
require_once '../connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$imagePath = __DIR__ . '/../images/profile.png'; // Put image here
$blob = file_get_contents($imagePath);

$sql = "UPDATE USER_TABLE SET PROFILE_PIC = :pic WHERE USER_ID = :id";
$stmt = oci_parse($conn, $sql);
$lob = oci_new_descriptor($conn, OCI_D_LOB);

oci_bind_by_name($stmt, ':pic', $lob, -1, OCI_B_BLOB);
oci_bind_by_name($stmt, ':id', $userId);

if ($stmt && $lob->writeTemporary($blob)) {
    if (oci_execute($stmt, OCI_DEFAULT)) {
        oci_commit($conn);
        echo "✅ Profile picture uploaded successfully.";
    } else {
        echo "❌ Execution failed: " . htmlspecialchars(oci_error($stmt)['message']);
    }
} else {
    echo "❌ Failed to bind or write image data.";
}

$lob->free();
oci_free_statement($stmt);
oci_close($conn);
?>

<?php
session_start();
header('Content-Type: application/json');
require_once './connection.php';

$response = [];
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}
$userId = $_SESSION['user_id'];

$sql = "
    SELECT p.PRODUCT_ID, p.PRODUCT_NAME, p.PRICE, p.CATEGORY, pw.WISHLIST_ID
    FROM PRODUCT p
    JOIN PRODUCT_WISHLIST pw ON pw.PRODUCT_ID = p.PRODUCT_ID
    JOIN WISHLIST w ON w.WISHLIST_ID = pw.WISHLIST_ID
    WHERE w.fk1_User_ID = :USER_ID
";
$st = oci_parse($conn, $sql);
oci_bind_by_name($st, ":USER_ID", $userId);
oci_execute($st);
$wishlist = [];
while ($row = oci_fetch_assoc($st)) {
    $wishlist[] = [
        'id' => $row['PRODUCT_ID'],
        'name' => $row['PRODUCT_NAME'],
        'price' => $row['PRICE'],
        'category' => $row['CATEGORY'],
        'image' => 'getImage.php?id=' . $row['PRODUCT_ID'],
    ];
}
oci_free_statement($st);
oci_close($conn);
echo json_encode(['success' => true, 'items' => $wishlist]);

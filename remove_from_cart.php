<?php
session_start();
require_once 'connection.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role']) !== 'customer') {
    echo json_encode(['success' => false, 'msg' => 'Not authorized']);
    exit;
}

$userId = (int)$_SESSION['user_id'];
$productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
if ($productId <= 0) {
    echo json_encode(['success' => false, 'msg' => 'Invalid Product ID']);
    exit;
}

// Get Cart/Wishlist ID for current user
if (strpos($_SERVER['SCRIPT_NAME'], 'cart') !== false) {
    $sql = "SELECT CART_ID FROM CART WHERE FK1_USER_ID = :user_id_var";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":user_id_var", $userId);
    oci_execute($stmt);
    $row = oci_fetch_assoc($stmt);
    oci_free_statement($stmt);
    if (!$row) {
        echo json_encode(['success' => false, 'msg' => 'No cart found']);
        exit;
    }
    $cartId = $row['CART_ID'];
    $sqlDel = "DELETE FROM PRODUCT_CART WHERE CART_ID = :cart_id_var AND PRODUCT_ID = :product_id_var";
    $stmtDel = oci_parse($conn, $sqlDel);
    oci_bind_by_name($stmtDel, ":cart_id_var", $cartId);
    oci_bind_by_name($stmtDel, ":product_id_var", $productId);
} else {
    $sql = "SELECT WISHLIST_ID FROM WISHLIST WHERE FK1_USER_ID = :user_id_var";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":user_id_var", $userId);
    oci_execute($stmt);
    $row = oci_fetch_assoc($stmt);
    oci_free_statement($stmt);
    if (!$row) {
        echo json_encode(['success' => false, 'msg' => 'No wishlist found']);
        exit;
    }
    $wishlistId = $row['WISHLIST_ID'];
    $sqlDel = "DELETE FROM PRODUCT_WISHLIST WHERE WISHLIST_ID = :wishlist_id_var AND PRODUCT_ID = :product_id_var";
    $stmtDel = oci_parse($conn, $sqlDel);
    oci_bind_by_name($stmtDel, ":wishlist_id_var", $wishlistId);
    oci_bind_by_name($stmtDel, ":product_id_var", $productId);
}
$success = oci_execute($stmtDel);
oci_free_statement($stmtDel);
oci_close($conn);

echo json_encode([
    'success' => $success,
    'msg' => $success ? 'Removed successfully' : 'Could not remove item'
]);

<?php
session_start();
require_once 'connection.php';

if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role']) !== 'customer') {
    die(json_encode(['success' => false, 'msg' => 'Not authorized']));
}

$userId = (int)$_SESSION['user_id'];
$productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;

if ($productId <= 0) {
    die(json_encode(['success' => false, 'msg' => 'Invalid Product ID']));
}

// Find this user's wishlist
$sqlWishlist = "SELECT WISHLIST_ID FROM WISHLIST WHERE FK1_USER_ID = :user_id_var";
$stmtWishlist = oci_parse($conn, $sqlWishlist);
oci_bind_by_name($stmtWishlist, ":user_id_var", $userId);
oci_execute($stmtWishlist);
$wishlistRow = oci_fetch_assoc($stmtWishlist);
oci_free_statement($stmtWishlist);

if (!$wishlistRow) {
    die(json_encode(['success' => false, 'msg' => 'No wishlist found']));
}
$wishlistId = $wishlistRow['WISHLIST_ID'];

// Remove product from wishlist
$sqlDel = "DELETE FROM PRODUCT_WISHLIST WHERE WISHLIST_ID = :wishlist_id_var AND PRODUCT_ID = :product_id_var";
$stmtDel = oci_parse($conn, $sqlDel);
oci_bind_by_name($stmtDel, ":wishlist_id_var", $wishlistId);
oci_bind_by_name($stmtDel, ":product_id_var", $productId);
$success = oci_execute($stmtDel);
oci_free_statement($stmtDel);
oci_close($conn);

if ($success) {
    echo json_encode(['success' => true, 'msg' => 'Removed from wishlist']);
} else {
    echo json_encode(['success' => false, 'msg' => 'Could not remove item']);
}
?>

<?php
if (!isset($_GET['id'])) {
    http_response_code(400);
    exit("Missing product ID");
}

$conn = oci_connect("bibash", "bibash", "localhost/xe");
if (!$conn) {
    http_response_code(500);
    exit("Failed to connect to the database.");
}

$id = intval($_GET['id']);
$sql = "SELECT PRODUCT_IMAGE FROM PRODUCT WHERE PRODUCT_ID = :id";
$stid = oci_parse($conn, $sql);
oci_bind_by_name($stid, ':id', $id);
oci_execute($stid);

$row = oci_fetch_assoc($stid);
if ($row && $row['PRODUCT_IMAGE']) {
    header("Content-Type: image/jpeg");
    echo $row['PRODUCT_IMAGE']->load();
} else {
    header("Location: https://via.placeholder.com/300x200?text=No+Image");
}

oci_free_statement($stid);
oci_close($conn);
?>

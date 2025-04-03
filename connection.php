<?php 

$conn = oci_connect('SYSTEM', 'Bimala321', '//localhost/xe'); if (!$conn) {
    $m = oci_error($conn);
    echo $m['message'], "\n";
    exit; } else {
 } 
 
 $conn = oci_connect('SYSTEM','Bimala321', '//localhost/xe') or die("Unable to connect to database");
     oci_close($conn);

?>

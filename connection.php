<?php 

$conn = oci_connect('SYSTEM', '********', '//localhost/xe'); if (!$conn) {
    $m = oci_error($conn);
    echo $m['message'], "\n";
    exit; } else {
    print "Connected to Oracle!"; 
 } 
 
 $conn = oci_connect('SYSTEM','********', '//localhost/xe') or die("Unable to connect to database");
     oci_close($conn);

?>

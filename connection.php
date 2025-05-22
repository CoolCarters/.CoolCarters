<?php
// connection.php — universal Oracle DB connection for CoolCarters

if (!isset($GLOBALS['conn']) || !$GLOBALS['conn']) {
    $db_user = '1234';        // Change to your DB user
    $db_pass = 'coolcarters';        // Change to your DB password
    $db_host = 'localhost/XE';  // XE, or 'host:port/servicename'

    $conn = oci_connect($db_user, $db_pass, $db_host, 'AL32UTF8');
    if (!$conn) {
        $e = oci_error();
        die("<b>❌ DB connection failed:</b> " . htmlspecialchars($e['message']));
    }
    $GLOBALS['conn'] = $conn;
}
?>

<?php
require_once('connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST["role"];
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    if ($role === "customer") {
        $gender = $_POST["gender"];

        $sql = "INSERT INTO CUSTOMER_DETAILS (FIRST_NAME, LAST_NAME, EMAIL, PASSWORD, GENDER)
                VALUES (:firstName, :lastName, :email, :password, :gender)";
        $stmt = oci_parse($conn, $sql);

        oci_bind_by_name($stmt, ":firstName", $firstName);
        oci_bind_by_name($stmt, ":lastName", $lastName);
        oci_bind_by_name($stmt, ":email", $email);
        oci_bind_by_name($stmt, ":password", $password);
        oci_bind_by_name($stmt, ":gender", $gender);
    } elseif ($role === "trader") {
        $companyName = $_POST["companyName"];

        $sql = "INSERT INTO TRADER_DETAILS 
        (FIRST_NAME, LAST_NAME, EMAIL, PASSWORD, COMPANY_NAME, ACTION)
        VALUES (:firstName, :lastName, :email, :password, :companyName, 'Pending')";

        $stmt = oci_parse($conn, $sql);

        oci_bind_by_name($stmt, ":firstName", $firstName);
        oci_bind_by_name($stmt, ":lastName", $lastName);
        oci_bind_by_name($stmt, ":email", $email);
        oci_bind_by_name($stmt, ":password", $password);
        oci_bind_by_name($stmt, ":companyName", $companyName);
    }

    if (oci_execute($stmt)) {
        echo "✅ Successfully registered!";
    } else {
        $e = oci_error($stmt);
        echo "❌ Error: " . htmlentities($e['message']);
    }

    oci_free_statement($stmt);
    oci_close($conn);
}

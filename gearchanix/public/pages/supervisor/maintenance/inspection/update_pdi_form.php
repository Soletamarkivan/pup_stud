<?php

$host = '127.0.0.1';
$dbname = 'gearchanix';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if (isset($_POST['pdi_ID'])) {
    $pdi_ID = $_POST['pdi_ID'];
    $driver = $_POST['driver'];
    $mechanic = $_POST['mechanic'];
    $pdi_date = $_POST['pdi_date'];
    $remarks = $_POST['remarks'];

    // Prepare the SQL query to update the record
    $stmt = $conn->prepare("UPDATE pdi_form SET driver = ?, mechanic = ?, pdi_date = ?, remarks = ? WHERE pdi_ID = ?");
    $stmt->bind_param("ssssi", $driver, $mechanic, $pdi_date, $remarks, $pdi_ID);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }

    $stmt->close();
}


?>

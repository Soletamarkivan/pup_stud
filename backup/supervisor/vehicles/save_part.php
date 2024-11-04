<?php
// Database connection
$host = '127.0.0.1'; // or your database host
$dbname = 'gearchanix';
$username = 'root'; // or your username
$password = ''; // or your password

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare the insert statement
    $stmt = $conn->prepare("INSERT INTO vehicle_parts (vehicle_ID, part_name, part_mtbf, part_ornum, part_date_procurred, part_date_inspected, part_date_accomplished, part_num_of_days, part_remarks, part_description) 
                            VALUES (:vehicle_ID, :part_name, :part_mtbf, :part_ornum, :part_date_procurred, :part_date_inspected, :part_date_accomplished, :part_num_of_days, :part_remarks, :part_description)");

    // Bind parameters
    $stmt->bindParam(':vehicle_ID', $_POST['vehicle_ID']);
    $stmt->bindParam(':part_name', $_POST['part_name']);
    $stmt->bindParam(':part_mtbf', $_POST['part_mtbf']);
    $stmt->bindParam(':part_ornum', $_POST['part_ornum']);
    $stmt->bindParam(':part_date_procurred', $_POST['part_date_procurred']);
    $stmt->bindParam(':part_date_inspected', $_POST['part_date_inspected']);
    $stmt->bindParam(':part_date_accomplished', $_POST['part_date_accomplished']);
    $stmt->bindParam(':part_num_of_days', $_POST['part_num_of_days']);
    $stmt->bindParam(':part_remarks', $_POST['part_remarks']);
    $stmt->bindParam(':part_description', $_POST['part_description']);

    // Execute the statement
    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
} catch (PDOException $e) {
    echo 'Database error: ' . $e->getMessage();
}
?>

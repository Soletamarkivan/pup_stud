<?php
header('Content-Type: application/json');

$servername = "127.0.0.1"; // Update as necessary
$username = "root";
$password = "";
$dbname = "gearchanix"; // Ensure this matches your database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

$sql = "SELECT * FROM service_reminder";
$result = $conn->query($sql);

$serviceReminders = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $serviceReminders[] = $row;
    }
}

echo json_encode($serviceReminders);

$conn->close();
?>

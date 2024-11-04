<?php
// Database connection details
$host = '127.0.0.1';
$db = 'gearchanix';
$user = 'root';
$pass = '';

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$vehicleparts_ID = isset($_GET['vehicleparts_ID']) ? $_GET['vehicleparts_ID'] : null;

if ($vehicleparts_ID) {
    // Fetch part data
    $sql = "SELECT * FROM vehicle_parts WHERE vehicleparts_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $vehicleparts_ID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode(['error' => 'No part found.']); // Return an error message
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'Invalid request.']); // Return an error message for invalid ID
}

$conn->close();
?>

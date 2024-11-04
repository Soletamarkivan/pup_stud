<?php
// vehicleparts.php

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

// Get the vehicle_ID from the URL parameters
$vehicle_ID = isset($_GET['vehicle_ID']) ? intval($_GET['vehicle_ID']) : 0;

if ($vehicle_ID > 0) {
    // Fetch vehicle parts for the specific vehicle
    $query = "SELECT * FROM vehicle_parts WHERE vehicle_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $vehicle_ID);
    $stmt->execute();
    $result = $stmt->get_result();

    $vehicleParts = [];
    while ($row = $result->fetch_assoc()) {
        $vehicleParts[] = $row;
    }

    // Return the parts data as a JSON response
    header('Content-Type: application/json');
    echo json_encode($vehicleParts);

    $stmt->close();
} else {
    echo json_encode([]);
}

$conn->close(); // Close the database connection
?>

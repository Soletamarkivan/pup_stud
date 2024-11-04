<?php
header('Content-Type: application/json');

// Database connection
$servername = "127.0.0.1";
$username = "root";
$password = ""; 
$dbname = "gearchanix";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit();
}

// Get data from the POST request
$data = json_decode(file_get_contents("php://input"), true);
$plate_num = $data['plate_num'];
$service_task = $data['service_task'];

// Step 1: Fetch latest odometer value from `vehicle_trip_summary`
$query = "SELECT latest_odometer FROM vehicle_trip_summary WHERE plate_num = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $plate_num);
$stmt->execute();
$result = $stmt->get_result();
$latest_odometer = $result->fetch_assoc()['latest_odometer'] ?? null;
$stmt->close();

// Check if odometer data exists
if ($latest_odometer !== null) {
    // Get the current date
    $current_date = date("Y-m-d");

    // Step 2: Update `maintenance_logs` table with the latest odometer and current date
    $updateQuery = "
        UPDATE maintenance_logs
        SET odometer_last_service = ?, date_last_service = ?
        WHERE plate_num = ? AND task_name = ?
    ";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("isss", $latest_odometer, $current_date, $plate_num, $service_task);
    
    // Execute the update query and respond accordingly
    if ($updateStmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Maintenance log updated successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating maintenance log: ' . $updateStmt->error]);
    }

    $updateStmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'No odometer data found for this vehicle.']);
}

$conn->close();
?>

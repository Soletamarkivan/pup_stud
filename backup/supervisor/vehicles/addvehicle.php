<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$host = '127.0.0.1'; // Adjust as necessary
$db_name = 'gearchanix';
$username = 'root';
$password = ''; // If there's no password, keep it as an empty string

$conn = new mysqli($host, $username, $password, $db_name);

// Check the connection
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]));
}

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve POST data
    $vehicle_name = $_POST['vehicle_name'] ?? '';
    $vehicle_model = $_POST['vehicle_model'] ?? '';
    $vehicle_year = $_POST['vehicle_year'] ?? '';
    $vehicle_vin = $_POST['vehicle_vin'] ?? '';
    $vehicle_type = $_POST['vehicle_type'] ?? '';
    $plate_num = $_POST['plate_num'] ?? '';
    $lifespan = $_POST['lifespan'] ?? '';
    $current_meter = $_POST['current_meter'] ?? '';
    $vehicle_status = $_POST['vehicle_status'] ?? '';

    // Prepare and bind for vehicles
    $stmt = $conn->prepare("INSERT INTO vehicles (vehicle_name, vehicle_model, vehicle_year, vehicle_vin, vehicle_type, plate_num, lifespan, current_meter, vehicle_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    if ($stmt === false) {
        die(json_encode(["success" => false, "message" => "Prepare failed: " . $conn->error]));
    }

    $stmt->bind_param("sssssssss", $vehicle_name, $vehicle_model, $vehicle_year, $vehicle_vin, $vehicle_type, $plate_num, $lifespan, $current_meter, $vehicle_status);

    // Execute the statement
    if ($stmt->execute()) {
        // Get the ID of the newly inserted vehicle
        $vehicle_id = $stmt->insert_id;

        // Fetch all service tasks to insert into maintenance_logs
        $service_tasks = $conn->query("SELECT task_ID, task_name FROM service_tasks");

        if ($service_tasks) {
            // Prepare statement for maintenance_logs
            $log_stmt = $conn->prepare("INSERT INTO maintenance_logs (task_ID, vehicle_type, plate_num, task_name, vehicle_ID) VALUES (?, ?, ?, ?, ?)");
            if ($log_stmt === false) {
                die(json_encode(["success" => false, "message" => "Prepare failed for maintenance logs: " . $conn->error]));
            }

            // Bind parameters for maintenance_logs
            $log_stmt->bind_param("isssi", $task_id, $vehicle_type, $plate_num, $task_name, $vehicle_id); // Added vehicle_ID as the last parameter

            // Insert each service task into maintenance_logs
            while ($row = $service_tasks->fetch_assoc()) {
                $task_id = $row['task_ID'];
                $task_name = $row['task_name'];
                $log_stmt->execute();
            }

            // Close the maintenance_logs statement
            $log_stmt->close();
        }

        echo json_encode(["success" => true, "message" => "Vehicle added successfully, and maintenance logs updated."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error adding vehicle: " . $stmt->error]);
    }

    // Close the vehicles statement
    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}

$conn->close();
?>

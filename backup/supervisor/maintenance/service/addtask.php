<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$host = '127.0.0.1'; 
$db_name = 'gearchanix';
$username = 'root';
$password = ''; 

$conn = new mysqli($host, $username, $password, $db_name);

// Check the connection
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]));
}

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve POST data
    $task_name = $_POST['task_name'] ?? '';
    $description = $_POST['description'] ?? '';
    $MTBF = $_POST['MTBF'] ?? '';
    $parts_involved = $_POST['parts_involved'] ?? '';

    // Input validation (optional but recommended)
    if (empty($task_name) || empty($description) || empty($MTBF)) {
        die(json_encode(["success" => false, "message" => "Please fill in all required fields."]));
    }

    // Prepare and bind the statement for service_tasks
    $stmt = $conn->prepare("INSERT INTO service_tasks (task_name, description, MTBF, parts_involved) VALUES (?, ?, ?, ?)");
    
    if ($stmt === false) {
        die(json_encode(["success" => false, "message" => "Prepare failed: " . $conn->error]));
    }

    $stmt->bind_param("ssis", $task_name, $description, $MTBF, $parts_involved);

    // Execute the statement for service_tasks
    if ($stmt->execute()) {
        // Get the ID of the newly inserted task
        $task_id = $stmt->insert_id;

        // Fetch all vehicles to insert into maintenance_logs
        $vehicles = $conn->query("SELECT vehicle_ID, vehicle_type, plate_num FROM vehicles"); // Now also fetch vehicle_ID

        if ($vehicles) {
            // Prepare statement for maintenance_logs
            $log_stmt = $conn->prepare("INSERT INTO maintenance_logs (task_ID, vehicle_ID, vehicle_type, plate_num, task_name) VALUES (?, ?, ?, ?, ?)");
            if ($log_stmt === false) {
                die(json_encode(["success" => false, "message" => "Prepare failed for maintenance logs: " . $conn->error]));
            }

            // Bind parameters for maintenance_logs
            $log_stmt->bind_param("iisss", $task_id, $vehicle_id, $vehicle_type, $plate_num, $task_name); // Added vehicle_ID as a parameter

            // Insert each vehicle's log
            while ($row = $vehicles->fetch_assoc()) {
                $vehicle_id = $row['vehicle_ID']; // Fetch vehicle_ID
                $vehicle_type = $row['vehicle_type'];
                $plate_num = $row['plate_num'];
                $log_stmt->execute();
            }

            // Close the maintenance_logs statement
            $log_stmt->close();
        }

        echo json_encode(["success" => true, "message" => "Service task added successfully, and maintenance logs updated for each vehicle."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error adding service task: " . $stmt->error]);
    }

    // Close statement and connection
    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}

$conn->close();
?>

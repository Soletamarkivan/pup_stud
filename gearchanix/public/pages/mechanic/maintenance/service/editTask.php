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

// Handle GET request to fetch task data based on task ID
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $task_ID = $_GET['id'];

    // Prepare a statement to fetch task data
    $stmt = $conn->prepare("SELECT * FROM service_tasks WHERE task_ID = ?");
    $stmt->bind_param("i", $task_ID);
    $stmt->execute();
    $result = $stmt->get_result();
    $task = $result->fetch_assoc();

    if ($task) {
        echo json_encode($task); // Return task data as JSON
    } else {
        echo json_encode(null); // If no task is found
    }

    $stmt->close();
    $conn->close();
    exit();
}

// Handle POST request to update task data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_ID = $_POST['task_ID'] ?? '';
    $task_name = $_POST['task_name'] ?? '';
    $description = $_POST['description'] ?? '';
    $MTBF = $_POST['MTBF'] ?? '';
    $parts_involved = $_POST['parts_involved'] ?? '';

    // Check if the task_ID is valid
    if (!empty($task_ID) && is_numeric($task_ID)) { // Ensure task_ID is not empty and is numeric
        // Prepare a statement to update the task
        $stmt = $conn->prepare("UPDATE service_tasks SET task_name = ?, description = ?, MTBF = ?, parts_involved = ? WHERE task_ID = ?");
        $stmt->bind_param("ssisi", $task_name, $description, $MTBF, $parts_involved, $task_ID);

        // Execute the statement and check if the update was successful
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Task updated successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Error updating task: " . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Invalid task ID."]);
    }

    $conn->close();
    exit();
}

// If no valid request
echo json_encode(["success" => false, "message" => "Invalid request."]);
?>

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

// Handle GET request to delete a task
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $task_ID = $_GET['id'];

    // Prepare a statement to delete the task
    $stmt = $conn->prepare("DELETE FROM service_tasks WHERE task_ID = ?");
    $stmt->bind_param("i", $task_ID);

    // Execute the statement and check if the delete was successful
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Task deleted successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error deleting task: " . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method or task ID not provided."]);
}

$conn->close();
?>

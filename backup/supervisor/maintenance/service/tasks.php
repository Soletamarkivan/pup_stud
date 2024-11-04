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

// Fetch all service tasks from the database
$sql = "SELECT task_ID, task_name, description, MTBF, parts_involved FROM service_tasks";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $tasks = [];

    // Fetch each row as an associative array
    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }

    // Output data as JSON
    echo json_encode($tasks);
} else {
    echo json_encode([]);
}

// Close the database connection
$conn->close();
?>

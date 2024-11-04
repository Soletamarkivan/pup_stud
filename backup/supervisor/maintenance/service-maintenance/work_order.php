<?php
// Database connection
$host = '127.0.0.1';
$dbname = 'gearchanix';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit();
}

// SQL query to retrieve data from work_order table
$query = "SELECT * FROM work_order";
$result = $conn->query($query);

if ($result) {
    $records = [];
    while ($row = $result->fetch_assoc()) {
        $records[] = $row;
    }
    echo json_encode(['success' => true, 'records' => $records]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error fetching data: ' . $conn->error]);
}

// Close the database connection
$conn->close();
?>

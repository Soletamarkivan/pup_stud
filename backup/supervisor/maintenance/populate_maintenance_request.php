<?php
// populate_maintenance_request.php
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

// SQL query to fetch all maintenance requests, ordered by target_date
$query = "
    SELECT 
        queue_num, 
        vehicle_name, 
        vehicle_type, 
        plate_num, 
        driver, 
        service_task, 
        start_date, 
        target_date, 
        parts, 
        receiver, 
        date, 
        noted_by, 
        approval_chief, 
        approval_office 
    FROM 
        maintenance_request
    ORDER BY 
        queue_num ASC
";

$result = $conn->query($query);

// Check if query was successful
if ($result) {
    $maintenance_requests = [];

    // Fetch all rows as an associative array
    while ($row = $result->fetch_assoc()) {
        $maintenance_requests[] = $row;
    }

    // Return data as JSON
    echo json_encode(['success' => true, 'data' => $maintenance_requests]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error retrieving maintenance requests: ' . $conn->error]);
}

// Close the database connection
$conn->close();
?>

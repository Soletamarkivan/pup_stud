<?php


// Database connection 
    $host = '127.0.0.1';
    $db = 'gearchanix';
    $user = 'root';
    $pass = ''; 

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM history_triptix";
$result = $conn->query($sql);

$tripTickets = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $tripTickets[] = $row; 
    }
}

// Return the data as JSON
header('Content-Type: application/json');
echo json_encode($tripTickets);

$conn->close();
?>

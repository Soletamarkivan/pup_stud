<?php
// Database connection details
$host = '127.0.0.1';
$dbName = 'gearchanix';
$username = 'root';
$password = '';

// Create a connection
$conn = new mysqli($host, $username, $password, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle the delete request
$data = json_decode(file_get_contents("php://input"));
if (isset($data->trip_ticketID)) {
    $trip_ticketID = $data->trip_ticketID;

    $query = "DELETE FROM trip_ticket WHERE trip_ticketID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $trip_ticketID);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete trip ticket']);
    }
    $stmt->close();
    exit;
}

// Query to fetch data from history_triptix table
$sql = "SELECT SELECT trip_ticket_date, vehicle_type, plate_num, gas_tank, purchased_gas, total, start_odometer, end_odometer, KM_used, RFID_Easy, RFID_Auto, oil_used FROM trip_ticket";
$result = $conn->query($sql);


// Initialize an array to hold trip tickets
$triptix = [];

if ($result->num_rows > 0) {
    // Fetch data for each row
    while ($row = $result->fetch_assoc()) {
        $triptix[] = $row; // Add each row to the trip tickets array
    }
}

// Return the data as a JSON response
header('Content-Type: application/json');
echo json_encode($triptix);

$conn->close(); // Close the database connection
?>

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

// Get the JSON data from the request
$data = json_decode(file_get_contents('php://input'), true);
$trip_ticketID = $data['trip_ticketID'];

// Prepare to move the record to history_triptix
$sql = "INSERT INTO history_triptix (trip_ticketID, reservation_ID, trip_ticket_date, vehicle_type, plate_num, gas_tank, purchased_gas, total, start_odometer, end_odometer, KM_used, RFID_Easy, RFID_Auto, oil_used) 
        SELECT trip_ticketID, reservation_ID, trip_ticket_date, vehicle_type, plate_num, gas_tank, purchased_gas, total, start_odometer, end_odometer, KM_used, RFID_Easy, RFID_Auto, oil_used 
        FROM trip_ticket 
        WHERE trip_ticketID = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $trip_ticketID);

if ($stmt->execute()) {
    // Delete the record from trip_ticket
    $delete_sql = "DELETE FROM trip_ticket WHERE trip_ticketID = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param('i', $trip_ticketID);
    $delete_stmt->execute();

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to move ticket.']);
}

$stmt->close();
$conn->close();
?>

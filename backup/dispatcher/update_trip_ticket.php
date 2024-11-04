<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
$host = '127.0.0.1';
$dbname = 'gearchanix';
$username = 'root';
$password = '';

header('Content-Type: application/json');

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get POST data and set to NULL if not provided
    $trip_ticketID = $_POST['editTripTicketID'] ?? null; 
    $plate_num = $_POST['editPlateNum'] ?? null; 
    $gas_tank = $_POST['editGasTank'] ?? null; 
    $purchased_gas = $_POST['editPurchasedGas'] ?? null; 
    $total = $_POST['editTotalGas'] ?? null; 
    $start_odometer = $_POST['editStartOdometer'] ?? null; 
    $end_odometer = $_POST['editEndOdometer'] ?? null; 
    $RFID_Easy = $_POST['editRFIDEasy'] ?? null; 
    $RFID_Auto = $_POST['editRFIDAuto'] ?? null; 
    $oil_used = $_POST['editOilUsed'] ?? null; 

    // Calculate KM_used as the difference between end and start odometer
    $KM_used = (isset($start_odometer) && isset($end_odometer) && is_numeric($start_odometer) && is_numeric($end_odometer)) 
               ? $end_odometer - $start_odometer 
               : null; // Set to NULL if not provided or not numeric

    // Prepare the SQL update statement
    $sql = "UPDATE trip_ticket SET 
                plate_num = COALESCE(NULLIF(?, ''), NULL), 
                gas_tank = COALESCE(NULLIF(?, ''), NULL), 
                purchased_gas = COALESCE(NULLIF(?, ''), NULL), 
                total = COALESCE(NULLIF(?, ''), NULL), 
                start_odometer = COALESCE(NULLIF(?, ''), NULL), 
                end_odometer = COALESCE(NULLIF(?, ''), NULL), 
                KM_used = COALESCE(NULLIF(?, ''), NULL), 
                RFID_Easy = COALESCE(NULLIF(?, ''), NULL), 
                RFID_Auto = COALESCE(NULLIF(?, ''), NULL), 
                oil_used = COALESCE(NULLIF(?, ''), NULL) 
            WHERE trip_ticketID = ?";

    // Execute the update statement
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$plate_num, $gas_tank, $purchased_gas, $total, $start_odometer, $end_odometer, $KM_used, $RFID_Easy, $RFID_Auto, $oil_used, $trip_ticketID]);

    // Log the successful update
    error_log('Trip ticket updated: ' . json_encode([$trip_ticketID, $plate_num, $gas_tank, $purchased_gas, $total]));

    // Return success response
    echo json_encode(['success' => true, 'message' => 'Trip ticket updated successfully.']);
} catch (PDOException $e) {
    // Log and return error message
    error_log('Database Error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>

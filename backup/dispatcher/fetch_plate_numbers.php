<?php
// Database connection
$host = '127.0.0.1';
$dbname = 'gearchanix';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['vehicle_type'])) {
        $vehicleType = $_GET['vehicle_type'];
        error_log("Vehicle Type: " . $vehicleType); // Debugging output

        // Query to fetch plate numbers from the vehicles table based on vehicle_type
        $stmt = $pdo->prepare("
            SELECT plate_num
            FROM vehicles
            WHERE vehicle_type = :vehicle_type
        ");
        $stmt->bindParam(':vehicle_type', $vehicleType, PDO::PARAM_STR);
        $stmt->execute();

        $plateNumbers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($plateNumbers);
        error_log('Plate Numbers: ' . json_encode($plateNumbers)); // Debugging output
    } else {
        echo json_encode(['success' => false, 'message' => 'Vehicle type not specified.']);
    }
} catch (PDOException $e) {
    error_log('Database Error: ' . $e->getMessage()); // Log database errors
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>

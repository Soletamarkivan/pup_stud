<?php
// editvehicle.php
// Database credentials
$host = '127.0.0.1';
$db = 'gearchanix';
$user = 'root';
$pass = '';

// Establish connection
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed.']));
}

try {
    // Check for GET request to fetch vehicle details
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
        $vehicle_ID = intval($_GET['id']); // Ensuring ID is an integer

        $sql = "SELECT * FROM vehicles WHERE vehicle_ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $vehicle_ID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo json_encode($result->fetch_assoc());
        } else {
            echo json_encode(['message' => 'Vehicle not found']);
        }

        $stmt->close();

    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vehicle_ID'])) {
        // Update vehicle details
        $vehicle_ID = intval($_POST['vehicle_ID']);
        $query = "UPDATE vehicles SET vehicle_name=?, vehicle_model=?, vehicle_year=?, vehicle_vin=?, vehicle_type=?, plate_num=?, lifespan=?, current_meter=?, odometer_last_pms=?, vehicle_remarks=? WHERE vehicle_ID=?";
        $stmt = $conn->prepare($query);
        
        // Update the type string to match all 11 parameters
        $stmt->bind_param("ssissssssii", $_POST['vehicle_name'], $_POST['vehicle_model'], $_POST['vehicle_year'], $_POST['vehicle_vin'], $_POST['vehicle_type'], $_POST['plate_num'], $_POST['lifespan'], $_POST['current_meter'], $_POST['odometer_last_pms'], $_POST['vehicle_remarks'], $vehicle_ID);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update vehicle']);
        }

        $stmt->close();
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
}

// Close the connection
$conn->close();
?>

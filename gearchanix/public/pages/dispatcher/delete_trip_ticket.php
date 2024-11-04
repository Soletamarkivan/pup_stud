<?php
// Database connection
$host = '127.0.0.1';
$dbname = 'gearchanix';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the request method is DELETE
    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        // Decode the incoming JSON request body
        $data = json_decode(file_get_contents("php://input"), true);
        
        // Check if the 'id' is present in the request
        if (isset($data['id'])) {
            $tripTicketID = $data['id'];

            // Prepare the DELETE statement
            $stmt = $pdo->prepare("DELETE FROM trip_ticket WHERE trip_ticketID = :id");
            $stmt->bindParam(':id', $tripTicketID, PDO::PARAM_INT);

            // Execute the query
            if ($stmt->execute()) {
                // Success response
                echo json_encode(['success' => true]);
            } else {
                // Failure response
                echo json_encode(['success' => false, 'message' => 'Failed to delete trip ticket']);
            }
        } else {
            // Invalid input response
            echo json_encode(['success' => false, 'message' => 'Invalid trip ticket ID']);
        }
        exit;
    }
} catch (PDOException $e) {
    // Error response
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>

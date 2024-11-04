<?php
// Database connection details
$host = '127.0.0.1';
$dbname = 'gearchanix';
$username = 'root';
$password = ''; 

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if pdi_ID is set
    if (isset($_POST['pdi_ID'])) {
        $pdiID = $_POST['pdi_ID'];

        // Prepare and execute the delete statement
        $deleteQuery = "DELETE FROM pdi_form WHERE pdi_ID = :pdi_ID";
        $deleteStmt = $pdo->prepare($deleteQuery);
        $deleteStmt->bindParam(':pdi_ID', $pdiID);
        $deleteStmt->execute();

        echo json_encode(['success' => true]); // Return success response
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>

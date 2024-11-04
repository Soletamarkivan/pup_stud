<?php
// Database connection
$host = '127.0.0.1';
$dbname = 'gearchanix';
$username = 'root';
$password = ''; // Change this if you have a password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $pdiID = $_POST['pdi_ID'];
        $column = $_POST['column'];
        $value = $_POST['value'];

        $updateQuery = "UPDATE pdi_form SET $column = :value WHERE pdi_ID = :pdiID";
        $stmt = $pdo->prepare($updateQuery);
        $stmt->execute(['value' => $value, 'pdiID' => $pdiID]);

        echo json_encode(['status' => 'success']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>

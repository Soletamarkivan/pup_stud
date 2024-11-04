<?php
// Database connection
$host = '127.0.0.1';
$dbname = 'gearchanix';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT * FROM trip_ticket WHERE trip_ticketID = :id");
        $stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
        $stmt->execute();
        $tripTicket = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode($tripTicket);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>

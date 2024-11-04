<?php
// Database connection
$host = '127.0.0.1';
$dbname = 'gearchanix';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch all trip tickets
    $stmt = $pdo->query("SELECT * FROM trip_ticket");
    $tripTickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($tripTickets);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>

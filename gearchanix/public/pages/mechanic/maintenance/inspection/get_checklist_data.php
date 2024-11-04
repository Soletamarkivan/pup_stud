<?php
// Database connection
$host = '127.0.0.1';
$dbname = 'gearchanix';
$username = 'root';
$password = ''; // Change this if you have a password

$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset($_GET['pdi_id'])) {
    $pdi_id = $_GET['pdi_id'];
    $stmt = $conn->prepare("SELECT * FROM pdi_form WHERE pdi_ID = :pdi_id");
    $stmt->bindParam(':pdi_id', $pdi_id);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($data);
}
?>

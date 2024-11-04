<?php
$host = '127.0.0.1';
$dbname = 'gearchanix';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if (isset($_GET['pdi_ID'])) {
    $pdi_ID = $_GET['pdi_ID'];

    // Prepare and execute the query to fetch the record
    $stmt = $conn->prepare("SELECT * FROM pdi_form WHERE pdi_ID = ?");
    $stmt->bind_param("i", $pdi_ID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $record = $result->fetch_assoc();
        echo json_encode($record); // Send the data back as JSON
    } else {
        echo json_encode(['error' => 'No record found']);
    }

    $stmt->close();
}
?>

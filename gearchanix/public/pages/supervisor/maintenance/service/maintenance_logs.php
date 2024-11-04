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

    // Fetch all records from the maintenance_logs table
    $fetchQuery = "SELECT * FROM maintenance_logs"; // You can add any WHERE conditions if needed
    $fetchStmt = $pdo->prepare($fetchQuery);
    $fetchStmt->execute();
    $results = $fetchStmt->fetchAll(PDO::FETCH_ASSOC);

    // Generate HTML for the table rows
    foreach ($results as $row) {
        echo '<tr>';
        // Log ID
        echo '<td>' . htmlspecialchars($row['log_ID']) . '</td>';
        // Vehicle Type
        echo '<td>' . htmlspecialchars($row['vehicle_type']) . '</td>';
        // Plate Number
        echo '<td>' . htmlspecialchars($row['plate_num']) . '</td>';
        // Odometer Last Service
        echo '<td>' . htmlspecialchars($row['odometer_last_service']) . '</td>';
        // Date Last Service
        echo '<td>' . htmlspecialchars($row['date_last_service']) . '</td>';
        // Task Name
        echo '<td>' . htmlspecialchars($row['task_name']) . '</td>';
        // Delete button
        echo '<td>
                <button class="btn btn-light btn-sm mx-1 btn-delete" onclick="openDeleteModal(' . htmlspecialchars($row['log_ID']) . ', \'' . htmlspecialchars($row['plate_num']) . '\')">
                    <i class="fas fa-trash"></i>
                </button>
            </td>';
        echo '</tr>';
    }
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>

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

    // Fetch only approved records from the pdi_form table
    $fetchQuery = "SELECT * FROM pdi_form WHERE status = 'approved'"; // Add WHERE clause
    $fetchStmt = $pdo->prepare($fetchQuery);
    $fetchStmt->execute();
    $results = $fetchStmt->fetchAll(PDO::FETCH_ASSOC);

    // Generate HTML for the table rows
    foreach ($results as $row) {
        echo '<tr>';
        // Vehicle Plate Number
        echo '<td>' . htmlspecialchars($row['plate_num']) . '</td>';
        // Trip Ticket #
        echo '<td>' . htmlspecialchars($row['trip_ticketID']) . '</td>';
        // PDI Date
        echo '<td>' . htmlspecialchars($row['pdi_date']) . '</td>';
        // Performed By (Driver)
        echo '<td>' . htmlspecialchars($row['driver']) . '</td>';
        // Noted By (Mechanic)
        echo '<td>' . htmlspecialchars($row['mechanic']) . '</td>';
        // Status
        echo '<td><span id="status_' . htmlspecialchars($row['pdi_ID']) . '">' . htmlspecialchars($row['status']) . '</span></td>';
        // Remarks
        echo '<td>' . htmlspecialchars($row['remarks']) . '</td>';
        // Delete button
        echo '<td><button class="btn btn-light btn-sm mx-1 btn-delete" onclick="openDeleteModal(' . htmlspecialchars($row['pdi_ID']) . ', \'' . htmlspecialchars($row['plate_num']) . '\')">
                            <i class="fas fa-trash"></i>
                </button></td>';
        echo '</tr>';
    }
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>

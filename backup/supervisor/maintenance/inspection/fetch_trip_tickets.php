<?php
// Database connection
$host = '127.0.0.1';
$dbname = 'gearchanix';
$username = 'root';
$password = ''; // Change this if you have a password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch all trip tickets
    $query = "SELECT trip_ticketID, plate_num FROM trip_ticket";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $tripTickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($tripTickets as $ticket) {
        // Check if the trip_ticketID already exists in pdi_form
        $checkQuery = "SELECT COUNT(*) FROM pdi_form WHERE trip_ticketID = :trip_ticketID";
        $checkStmt = $pdo->prepare($checkQuery);
        $checkStmt->execute(['trip_ticketID' => $ticket['trip_ticketID']]);
        $exists = $checkStmt->fetchColumn();

        // If it doesn't exist, insert it into pdi_form
        if ($exists == 0) {
            $insertQuery = "INSERT INTO pdi_form (pdi_date, trip_ticketID, plate_num, status) 
                            VALUES (NULL, :trip_ticketID, :plate_num, 'pending')";
            $insertStmt = $pdo->prepare($insertQuery);
            $insertStmt->execute([
                'trip_ticketID' => $ticket['trip_ticketID'],
                'plate_num' => $ticket['plate_num']
            ]);
        }
    }

    // Fetch updated data for display, only those with status not approved
    $fetchQuery = "SELECT * FROM pdi_form WHERE status != 'approved'";
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
        // BLOWBAGETS button
        echo '<td><button class="btn btn-info" onclick="openChecklist(' . htmlspecialchars($row['pdi_ID']) . ')">BLOWBAGETS</button></td>';
        // Status
        echo '<td><span id="status_' . htmlspecialchars($row['pdi_ID']) . '">' . htmlspecialchars($row['status']) . '</span></td>';
        // Remarks
        echo '<td>' . htmlspecialchars($row['remarks']) . '</td>';
        // Action button (for future actions, such as edit or delete)
        echo '<td><button class="btn btn-warning" onclick="editPdiForm(' . htmlspecialchars($row['pdi_ID']) . ')">Edit</button></td>';
        echo '</tr>';
    }
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>

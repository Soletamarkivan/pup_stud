<?php
// Database connection
$host = '127.0.0.1';
$dbname = 'gearchanix';
$username = 'root';
$password = ''; // Change this if you have a password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check for existing entries in pdi_form and avoid duplicates
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

    // Fetch updated data for display
    $fetchQuery = "SELECT * FROM pdi_form";
    $fetchStmt = $pdo->prepare($fetchQuery);
    $fetchStmt->execute();
    $results = $fetchStmt->fetchAll(PDO::FETCH_ASSOC);

    // Generate HTML for the table rows
    foreach ($results as $row) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['plate_num']) . '</td>';
        echo '<td>' . htmlspecialchars($row['trip_ticketID']) . '</td>';
        echo '<td><input type="text" data-pdi-id="' . $row['pdi_ID'] . '" data-column="pdi_date" value="' . htmlspecialchars($row['pdi_date']) . '" class="form-control editable" /></td>';
        echo '<td><input type="text" data-pdi-id="' . $row['pdi_ID'] . '" data-column="driver" id="driver_' . $row['pdi_ID'] . '" class="form-control editable" /></td>';
        echo '<td><input type="text" data-pdi-id="' . $row['pdi_ID'] . '" data-column="mechanic" id="mechanic_' . $row['pdi_ID'] . '" class="form-control editable" /></td>';
        echo '<td><button class="btn btn-info" onclick="openChecklist(' . $row['pdi_ID'] . ')">BLOWBAGETS</button></td>';
        echo '<td><span id="status_' . $row['pdi_ID'] . '">' . htmlspecialchars($row['status']) . '</span></td>';
        echo '<td><span id="status_' . $row['pdi_ID'] . '">' . htmlspecialchars($row['remarks']) . '</span></td>';
        echo '<td><button class="btn btn-light btn-sm mx-1 btn-edit" title="Edit" onclick="editPdiForm(' . htmlspecialchars($row['pdi_ID']) . ')">
            <i class="fas fa-edit"></i>
        </button></td>';
        echo '</tr>';
    }
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
$host = '127.0.0.1';
$dbname = 'gearchanix';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $e->getMessage()]);
    exit();
}

// Check if necessary POST data is received
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['queue_num'], $_POST['approval_type'], $_POST['approval_status'])) {
    $queue_num = $_POST['queue_num'];
    $approval_type = $_POST['approval_type'];
    $approval_status = $_POST['approval_status'];

    // Only update the chief approval status
    if ($approval_type === 'chief') {
        // Fetch current approval status
        $fetchStmt = $pdo->prepare("SELECT approval_chief FROM maintenance_request WHERE queue_num = :queue_num");
        $fetchStmt->bindParam(':queue_num', $queue_num, PDO::PARAM_INT);
        $fetchStmt->execute();
        $currentApprovalStatus = $fetchStmt->fetchColumn();

        if ($currentApprovalStatus === false) {
            echo json_encode(['success' => false, 'message' => 'Queue number does not exist.']);
            exit();
        }

        if ($currentApprovalStatus === $approval_status) {
            echo json_encode(['success' => false, 'message' => 'No changes made; approval status is already: ' . $currentApprovalStatus]);
            exit();
        }

        // Prepare and execute the update query
        $stmt = $pdo->prepare("UPDATE maintenance_request SET approval_chief = :approval_status WHERE queue_num = :queue_num");
        $stmt->bindParam(':approval_status', $approval_status);
        $stmt->bindParam(':queue_num', $queue_num, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Check if any rows were affected
            if ($stmt->rowCount() > 0) {
                // If approved, generate the PDF
                if ($approval_status === 'Approved') {
                    // Fetch record details for the PDF document, including parts
                    $fetchStmt = $pdo->prepare("SELECT vehicle_name, vehicle_type, plate_num, driver, service_task, start_date, target_date, parts, receiver, date, noted_by FROM maintenance_request WHERE queue_num = :queue_num");
                    $fetchStmt->bindParam(':queue_num', $queue_num, PDO::PARAM_INT);
                    $fetchStmt->execute();
                    $record = $fetchStmt->fetch(PDO::FETCH_ASSOC);

                    if ($record) {
                        // Include TCPDF library
                        require_once 'C:/xampp/htdocs/Gearchanix/vendor/autoload.php'; // Ensure this path is correct

                        // Create new PDF document
                        $pdf = new TCPDF();
                        $pdf->SetCreator(PDF_CREATOR);
                        $pdf->SetAuthor('Your Organization');
                        $pdf->SetTitle('Nature of Schedule of Repair');
                        $pdf->SetMargins(15, 15, 15);
                        $pdf->AddPage();

                        // Add content to the PDF
                        $pdf->SetFont('helvetica', 'B', 14);
                        $pdf->Cell(0, 10, 'Republic of the Philippines', 0, 1, 'C');
                        $pdf->SetFont('helvetica', '', 10);
                        $pdf->Cell(0, 10, 'POLYTECHNIC UNIVERSITY OF THE PHILIPPINES', 0, 1, 'C');
                        $pdf->Cell(0, 10, 'Office of the Vice President for Administration', 0, 1, 'C');
                        $pdf->Cell(0, 10, 'General Services Office', 0, 1, 'C');
                        $pdf->Cell(0, 10, 'TRANSPORTATION AND MOTOR POOL SECTION', 0, 1, 'C');
                        $pdf->Cell(0, 10, 'Sta. Mesa, Manila', 0, 1, 'C');
                        $pdf->Ln(10);
                        $pdf->SetFont('helvetica', 'B', 12);
                        $pdf->Cell(0, 10, "Nature of Schedule of Repair", 0, 1, 'C');

                        // Add vehicle details
                        $pdf->SetFont('helvetica', 'B', 9);
                        $pdf->Cell(0, 10, "Vehicle: " . $record['vehicle_name'], 0, 1);
                        $pdf->Cell(0, 10, "Driver: " . $record['driver'], 0, 1);
                        $pdf->Cell(0, 10, "Nature of Repair: " . $record['service_task'], 0, 1);
                        $pdf->Cell(0, 10, "Parts Required: " . $record['parts'], 0, 1); // Added parts field
                        $pdf->Cell(0, 10, "Start Date of Repair: " . $record['start_date'], 0, 1);
                        $pdf->Cell(0, 10, "Target of Completion: " . $record['target_date'], 0, 1);
                        $pdf->Ln(10);
                        $pdf->Cell(0, 10, "Signature:", 0, 1);
                        $pdf->SetFont('helvetica', 'B', 9);
                        $pdf->Cell(0, 10, "Mechanic", 0, 1);
                        $pdf->Ln(10);
                        $pdf->Cell(0, 10, "Received by: " . $record['receiver'], 0, 1);
                        $pdf->Cell(0, 10, "Date: " . $record['date'], 0, 1);
                        $pdf->Cell(0, 10, "Noted by: " . $record['noted_by'], 0, 1);
                        $pdf->Ln(10);

                        // Output PDF
                        $filename = "Nature_of_Schedule_of_Repair_{$queue_num}.pdf";
                        $pdf->Output($filename, 'D'); // D for download

                        // Exit to prevent further output
                        exit();
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Record not found']);
                        exit();
                    }
                } else {
                    echo json_encode(['success' => true, 'message' => 'Approval status updated successfully']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'No rows updated. Check if the queue_num exists.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update approval status']);
        }
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>

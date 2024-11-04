<?php
// Place 'use' statements here, right after opening PHP tag
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


// Database connection
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "gearchanix";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the selected months, year, and format from the form
$months = $_GET['months']; // Array of selected months
$year = $_GET['year'];
$format = $_GET['format'];

// Prepare SQL statement for multiple months
$placeholders = implode(',', array_fill(0, count($months), '?'));
$sql = "SELECT trip_ticket_date, gas_tank, purchased_gas, total, start_odometer, end_odometer, KM_used, RFID_Easy, RFID_Auto, oil_used, vehicle_type, plate_num, trip_ticketID  
        FROM history_triptix
        WHERE MONTH(trip_ticket_date) IN ($placeholders) AND YEAR(trip_ticket_date) = ?";

$stmt = $conn->prepare($sql);

// Correct type binding for month (integers) and year
$types = str_repeat('i', count($months)) . 'i'; // 'i' for integers (month and year)
$params = array_merge($months, [$year]);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// Generate a filename based on selected months and year
$monthNames = array_map(function($month) {
    return date('F', mktime(0, 0, 0, $month, 10));
}, $months);
$monthStr = implode('_', $monthNames);
$filenameBase = "report_{$monthStr}_{$year}";

// Handle Excel format with separate sheets for each month
if ($format == 'excel') {
    // Use Composer's autoload
    require 'C:/xampp/htdocs/Gearchanix/vendor/autoload.php';
    
    $spreadsheet = new Spreadsheet();
    
    foreach ($months as $index => $month) {
        // Create a new sheet for each month and set the title
        if ($index == 0) {
            $sheet = $spreadsheet->getActiveSheet(); // Get the first sheet
        } else {
            $sheet = $spreadsheet->createSheet(); // Create new sheets for subsequent months
        }
        $sheet->setTitle(date('F', mktime(0, 0, 0, $month, 10))); // Set sheet title to month name
        
        // Populate headers
        $sheet->setCellValue('A1', 'Vehicle Type');
        $sheet->setCellValue('B1', 'Plate Number');
        $sheet->setCellValue('C1', 'Oil Used');
        $sheet->setCellValue('D1', 'Trip Ticket Date');
        $sheet->setCellValue('E1', 'Gas Tanks');
        $sheet->setCellValue('F1', 'Purchased Gas');
        $sheet->setCellValue('G1', 'Total');
        $sheet->setCellValue('H1', 'Start Odometer');
        $sheet->setCellValue('I1', 'End Odometer');
        $sheet->setCellValue('J1', 'KM Used');
        $sheet->setCellValue('K1', 'RFID Easy');
        $sheet->setCellValue('L1', 'RFID Auto');


        // Filter data for the current month
        $rowNum = 2;
        $stmtMonth = $conn->prepare("SELECT * FROM trip_ticket WHERE MONTH(trip_ticket_date) = ? AND YEAR(trip_ticket_date) = ?");
        $stmtMonth->bind_param('ii', $month, $year);
        $stmtMonth->execute();
        $resultMonth = $stmtMonth->get_result();
        
        while ($row = $resultMonth->fetch_assoc()) {
            $sheet->setCellValue('A' . $rowNum, $row['vehicle_type']);
            $sheet->setCellValue('B' . $rowNum, $row['plate_num']);
            $sheet->setCellValue('C' . $rowNum, $row['oil_used']);
            $sheet->setCellValue('D' . $rowNum, $row['trip_ticket_date']);
            $sheet->setCellValue('E' . $rowNum, $row['gas_tank']);
            $sheet->setCellValue('F' . $rowNum, $row['purchased_gas']);
            $sheet->setCellValue('G' . $rowNum, $row['total']);
            $sheet->setCellValue('H' . $rowNum, $row['start_odometer']);
            $sheet->setCellValue('I' . $rowNum, $row['end_odometer']);
            $sheet->setCellValue('J' . $rowNum, $row['KM_used']);
            $sheet->setCellValue('K' . $rowNum, $row['RFID_Easy']);
            $sheet->setCellValue('L' . $rowNum, $row['RFID_Auto']);
            $rowNum++;
        }
        $stmtMonth->close();
    }

    // Output the Excel file
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment;filename=\"{$filenameBase}.xlsx\"");
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;

}  elseif ($format == 'pdf') {
    // Use TCPDF for PDF generation
    require_once('C:\xamp\htdocs\Gearchanix\vendor\tecnickcom\tcpdf\tcpdf.php');
    
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Your Name');
    $pdf->SetTitle('Trip Ticket History Report');
    $pdf->SetSubject('Trip Ticket History Data');
    $pdf->SetKeywords('TCPDF, PDF, report, tripticket');

    // Set default header data
    $pdf->SetHeaderData('', 0, 'Trip Ticket History Report', "Generated on " . date('Y-m-d H:i:s'));

    // Set header and footer fonts
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    // Set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // Set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    // Set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    // Add a page in landscape orientation
    $pdf->AddPage('L'); // Specify 'L' for landscape orientation
    
    // Set font for the content
    $pdf->SetFont('helvetica', '', 9);

    foreach ($months as $month) {
        // Set font for the month title (larger and bold)
        $pdf->SetFont('helvetica', 'B', 14); 
    
        // Add month title
        $pdf->Cell(0, 10, date('F', mktime(0, 0, 0, $month, 10)), 0, 1, 'C');
    
        // Reset font back to normal size for the rest of the content
        $pdf->SetFont('helvetica', '', 10); 
    
        $pdf->Ln(5); // Add space after month title
    
    
        // Populate headers
        $html = '<table border="1" cellpadding="5">';
        $html .= '<thead><tr>
                    <th>Vehicle Type</th>
                    <th>Plate Number</th>
                    <th>Oil Used</th>
                    <th>Trip Ticket Date</th>
                    <th>Gas Tank</th>
                    <th>Purchased Gas</th>
                    <th>Total</th>
                    <th>Start Odometer</th>
                    <th>End Odometer</th>
                    <th>KM Used</th>
                    <th>RFID Easy</th>
                    <th>RFID Auto</th>
                    </tr></thead><tbody>';

        // Filter data for the current month
        $stmtMonth = $conn->prepare("SELECT * FROM history_triptix WHERE MONTH(trip_ticket_date) = ? AND YEAR(trip_ticket_date) = ?");
        $stmtMonth->bind_param('ii', $month, $year);
        $stmtMonth->execute();
        $resultMonth = $stmtMonth->get_result();

        while ($row = $resultMonth->fetch_assoc()) {
            $html .= '<tr>
                        <td>' . $row['vehicle_type'] . '</td>
                        <td>' . $row['plate_num'] . '</td>
                        <td>' . $row['oil_used'] . '</td>
                        <td>' . $row['trip_ticket_date'] . '</td>
                        <td>' . $row['gas_tank'] . '</td>
                        <td>' . $row['purchased_gas'] . '</td>
                        <td>' . $row['total'] . '</td>
                        <td>' . $row['start_odometer'] . '</td>
                        <td>' . $row['end_odometer'] . '</td>
                        <td>' . $row['KM_used'] . '</td>
                        <td>' . $row['RFID_Easy'] . '</td>
                        <td>' . $row['RFID_Auto'] . '</td>
                    </tr>';
        }
        $html .= '</tbody></table>';
        $pdf->writeHTML($html, true, false, true, false, '');

        $pdf->Ln(10); // Add space between months
        $stmtMonth->close();
    }

    // Output the PDF file
    $filenamePDF = "{$filenameBase}.pdf";
    $pdf->Output($filenamePDF, 'D');
    exit;

} elseif ($format == 'word') {
    // Set the content type and filename for the Word document
    header("Content-type: application/vnd.ms-word");
    
    // Generate filename using the first month from the months array
    $firstMonth = !empty($months) ? $months[0] : 1; 
    header("Content-Disposition: attachment;Filename=trip_ticket_history_report_" . date('F', mktime(0, 0, 0, $firstMonth, 10)) . "_" . $year . ".doc");

    // Start output buffering
    ob_start(); // Start capturing output

    // Add some basic styles for the Word document
    ?>
    <style>
        @page {
            size: landscape; /* Suggest landscape orientation */
            margin: 1in; /* Set margins */
        }
        table {
            width: 70%; /* Full width for the table */
            border-collapse: collapse; /* Collapse borders */
        }
        th, td {
            border: 1px solid black; /* Table borders */
            padding: 5px; /* Cell padding */
            text-align: left; /* Align text */
        }
        h3 {
            text-align: center; /* Center the header */
        }
    </style>
    <?php

    // Add the document title
    echo '<h3>Trip Ticket History Report for ' . implode(', ', array_map(function($month) {
        return date('F', mktime(0, 0, 0, $month, 10));
    }, $months)) . ' ' . $year . '</h3>';
    
    // Create the table structure
    echo '<table>';
    echo '<tr>
            <th>Vehicle Type</th>
            <th>Plate Number</th>
            <th>Oil Used</th>
            <th>Trip Ticket Date</th>
            <th>Gas Tank</th>
            <th>Purchased Gas</th>
            <th>Total</th>
            <th>Start Odometer</th>
            <th>End Odometer</th>
            <th>KM Used</th>
            <th>RFID Easy</th>
            <th>RFID Auto</th>
            <th>Purpose</th>
        </tr>';

    // Execute the initial query to fetch data if it hasn't been done already
    $result->data_seek(0); // Reset pointer if necessary for the loop

    foreach ($months as $month) {
        // Add a header for the current month
        echo '<tr><td colspan="12" style="text-align:center; font-weight:bold;">' . date('F', mktime(0, 0, 0, $month, 10)) . ' ' . $year . '</td></tr>';

        // Filter data for the current month
        $stmtMonth = $conn->prepare("SELECT * FROM history_triptix WHERE MONTH(trip_ticket_date) = ? AND YEAR(trip_ticket_date) = ?");
        $stmtMonth->bind_param('ii', $month, $year);
        $stmtMonth->execute();
        $resultMonth = $stmtMonth->get_result();

        while ($row = $resultMonth->fetch_assoc()) {
            echo '<tr>
                    <td>' . $row['vehicle_type'] . '</td>
                    <td>' . $row['plate_num'] . '</td>
                    <td>' . $row['oil_used'] . '</td>
                    <td>' . $row['trip_ticket_date'] . '</td>
                    <td>' . $row['gas_tank'] . '</td>
                    <td>' . $row['purchased_gas'] . '</td>
                    <td>' . $row['total'] . '</td>
                    <td>' . $row['start_odometer'] . '</td>
                    <td>' . $row['end_odometer'] . '</td>
                    <td>' . $row['KM_used'] . '</td>
                    <td>' . $row['RFID_easy'] . '</td>
                    <td>' . $row['RFID_auto'] . '</td>
                </tr>';
        }

        $stmtMonth->close();
    }

    echo '</table>'; // Close the table

    // Get the output and clean the buffer
    $content = ob_get_clean(); // Get the buffered content and clean the buffer

    // Output the content
    echo $content; // Send the content to the browser
}



// Close the database connection
$conn->close();
?>

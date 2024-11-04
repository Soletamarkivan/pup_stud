<?php
// Place 'use' statements here, right after opening PHP tag
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Start output buffering
ob_start(); 

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

// Get the selected year and format from the form
$year = $_GET['year']; // Assuming this is a single year
$format = $_GET['format']; // Output format (excel, pdf, word)

// Prepare SQL statement for the selected year
$sql = "SELECT vehicle_name, vehicle_model, vehicle_year, vehicle_vin, vehicle_type, plate_num, lifespan, current_meter, pms_date, ems_date, vehicle_remarks, vehicle_status, vehicle_ID 
        FROM vehicles 
        WHERE vehicle_year = ?";

$stmt = $conn->prepare($sql);

// Bind the year parameter
$stmt->bind_param("i", $year); // 'i' for integer binding

// Execute the statement
$stmt->execute();

// Get the result
$result = $stmt->get_result(); // Fetch the results of the query

// Generate a filename based on the year
$filenameBase = "vehicle_list_report_" . $year;

// Handle Excel format with one sheet for the selected year
if ($format == 'excel') {
    // Use Composer's autoload
    require 'C:/xamp/htdocs/Gearchanix/vendor/autoload.php';
    
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle($year); // Set sheet title to the year

    // Populate headers
    $sheet->setCellValue('A1', 'Vehicle Name');
    $sheet->setCellValue('B1', 'Vehicle Model');
    $sheet->setCellValue('C1', 'Vehicle Year');
    $sheet->setCellValue('D1', 'Vehicle VIN');
    $sheet->setCellValue('E1', 'Vehicle Type');
    $sheet->setCellValue('F1', 'Plate Number');
    $sheet->setCellValue('G1', 'Lifespan');
    $sheet->setCellValue('H1', 'Current Meter');
    $sheet->setCellValue('I1', 'PMS Date');
    $sheet->setCellValue('J1', 'EMS Date');
    $sheet->setCellValue('K1', 'Vehicle Remarks');
    $sheet->setCellValue('L1', 'Vehicle Status');

    // Populate the data
    $rowNum = 2; // Starting row for data
    while ($row = $result->fetch_assoc()) {
        $sheet->setCellValue('A' . $rowNum, $row['vehicle_name']);
        $sheet->setCellValue('B' . $rowNum, $row['vehicle_model']);
        $sheet->setCellValue('C' . $rowNum, $row['vehicle_year']);
        $sheet->setCellValue('D' . $rowNum, $row['vehicle_vin']);
        $sheet->setCellValue('E' . $rowNum, $row['vehicle_type']);
        $sheet->setCellValue('F' . $rowNum, $row['plate_num']);
        $sheet->setCellValue('G' . $rowNum, $row['lifespan']);
        $sheet->setCellValue('H' . $rowNum, $row['current_meter']);
        $sheet->setCellValue('I' . $rowNum, $row['pms_date']);
        $sheet->setCellValue('J' . $rowNum, $row['ems_date']);
        $sheet->setCellValue('K' . $rowNum, $row['vehicle_remarks']);
        $sheet->setCellValue('L' . $rowNum, $row['vehicle_status']);
        $rowNum++;
    }

    // Output the Excel file
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment;filename=\"{$filenameBase}.xlsx\"");
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;

} elseif ($format == 'pdf') {
    // Use TCPDF for PDF generation
    require_once('C:\xamp\htdocs\Gearchanix\vendor\tecnickcom\tcpdf\tcpdf.php');
    
    // Initialize PDF
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Your Name');
    $pdf->SetTitle('Vehicle List Report');
    $pdf->SetSubject('Vehicle List Data');
    $pdf->SetKeywords('TCPDF, PDF, report, vehiclelist');

    // Set default header data
    $pdf->SetHeaderData('', 0, 'Vehicle List Report', "Generated on " . date('Y-m-d H:i:s'));

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

    // Check if $result is valid
    if ($result) {
        if ($result->num_rows > 0) {
            // Table content
            $html = '<table border="1" cellpadding="5">
                        <thead>
                            <tr>
                                <th>Vehicle Name</th>
                                <th>Vehicle Model</th>
                                <th>Vehicle Year</th>
                                <th>Vehicle VIN</th>
                                <th>Vehicle Type</th>
                                <th>Plate Number</th>
                                <th>Lifespan</th>
                                <th>Current Meter</th>
                                <th>PMS Date</th>
                                <th>EMS Date</th>
                                <th>Vehicle Remarks</th>
                                <th>Vehicle Status</th>
                            </tr>
                        </thead>
                        <tbody>';
        
            // Populate the table rows with data
            while ($row = $result->fetch_assoc()) {
                $html .= '<tr>
                            <td>' . htmlspecialchars($row['vehicle_name']) . '</td>
                            <td>' . htmlspecialchars($row['vehicle_model']) . '</td>
                            <td>' . htmlspecialchars($row['vehicle_year']) . '</td>
                            <td>' . htmlspecialchars($row['vehicle_vin']) . '</td>
                            <td>' . htmlspecialchars($row['vehicle_type']) . '</td>
                            <td>' . htmlspecialchars($row['plate_num']) . '</td>
                            <td>' . htmlspecialchars($row['lifespan']) . '</td>
                            <td>' . htmlspecialchars($row['current_meter']) . '</td>
                            <td>' . htmlspecialchars($row['pms_date']) . '</td>
                            <td>' . htmlspecialchars($row['ems_date']) . '</td>
                            <td>' . htmlspecialchars($row['vehicle_remarks']) . '</td>
                            <td>' . htmlspecialchars($row['vehicle_status']) . '</td>
                        </tr>';
            }
            $html .= '</tbody></table>';
        
            // Output the HTML content as a PDF
            $pdf->writeHTML($html, true, false, true, false, '');
        } else {
            // No records found
            $pdf->writeHTML('<h2>No records found for the selected criteria.</h2>', true, false, true, false, '');
        }
    } else {
        // Query failed
        $pdf->writeHTML('<h2>Query failed: ' . $conn->error . '</h2>', true, false, true, false, '');
    }

    // Output the PDF file
    ob_end_clean(); // Clean the output buffer to prevent corruption
    $pdf->Output("{$filenameBase}.pdf", 'D'); // 'D' forces download
    exit; // Stop further execution
} elseif ($format == 'word') {
    // Set the content type and filename for the Word document
    header("Content-type: application/vnd.ms-word");
    header("Content-Disposition: attachment;Filename=vehicle_list_report_{$year}.doc");

    // Add some basic styles for the Word document
    ?>
    <style>
        @page {
            size: landscape;
            margin: 1in;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 5px;
            text-align: left;
        }
    </style>
    <?php

    // Add the document title
    echo "<h3>Vehicle List Report for {$year}</h3>";
    
    // Create the table structure
    echo '<table>';
    echo '<tr>
            <th>Vehicle Name</th>
            <th>Vehicle Model</th>
            <th>Vehicle Year</th>
            <th>Vehicle VIN</th>
            <th>Vehicle Type</th>
            <th>Plate Number</th>
            <th>Lifespan</th>
            <th>Current Meter</th>
            <th>PMS Date</th>
            <th>EMS Date</th>
            <th>Vehicle Remarks</th>
            <th>Vehicle Status</th>
        </tr>';

    // Populate the table rows with data
    while ($row = $result->fetch_assoc()) {
        echo '<tr>
                <td>' . $row['vehicle_name'] . '</td>
                <td>' . $row['vehicle_model'] . '</td>
                <td>' . $row['vehicle_year'] . '</td>
                <td>' . $row['vehicle_vin'] . '</td>
                <td>' . $row['vehicle_type'] . '</td>
                <td>' . $row['plate_num'] . '</td>
                <td>' . $row['lifespan'] . '</td>
                <td>' . $row['current_meter'] . '</td>
                <td>' . $row['pms_date'] . '</td>
                <td>' . $row['ems_date'] . '</td>
                <td>' . $row['vehicle_remarks'] . '</td>
                <td>' . $row['vehicle_status'] . '</td>
            </tr>';
    }
    echo '</table>';

    exit;
}

// Close statement and connection
$stmt->close();
$conn->close();

ob_end_flush();
?>

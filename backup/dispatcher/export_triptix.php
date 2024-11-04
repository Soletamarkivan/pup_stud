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
$sql = "SELECT trip_ticket_date, gas_tank, purchased_gas, total, start_odometer, end_odometer, KM_used, RFID_Easy, RFID_Auto, oil_used, vehicle_type, plate_num
        FROM trip_ticket
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
            $sheet = $spreadsheet->getActiveSheet(); 
        } else {
            $sheet = $spreadsheet->createSheet(); 
        }
        $sheet->setTitle(date('F', mktime(0, 0, 0, $month, 10))); 
        
        // Populate headers
        $sheet->setCellValue('A1', 'Trip Ticket Date');
        $sheet->setCellValue('B1', 'Gas Tank');
        $sheet->setCellValue('C1', 'Purchased Gas');
        $sheet->setCellValue('D1', 'Total');
        $sheet->setCellValue('E1', 'Start Odometer');
        $sheet->setCellValue('F1', 'End Odometer');
        $sheet->setCellValue('G1', 'KM Used');
        $sheet->setCellValue('H1', 'RFID Easy');
        $sheet->setCellValue('I1', 'RFID Auto');
        $sheet->setCellValue('J1', 'Oil Used');
        $sheet->setCellValue('K1', 'Vehicle Type');
        $sheet->setCellValue('L1', 'Plate Number');

        // Filter data for the current month
        $rowNum = 2;
        $stmtMonth = $conn->prepare("SELECT * FROM trip_ticket WHERE MONTH(trip_ticket_date) = ? AND YEAR(trip_ticket_date) = ?");
        $stmtMonth->bind_param('ii', $month, $year);
        $stmtMonth->execute();
        $resultMonth = $stmtMonth->get_result();
        
        while ($row = $resultMonth->fetch_assoc()) {
            $sheet->setCellValue('A' . $rowNum, $row['trip_ticket_date']);
            $sheet->setCellValue('B' . $rowNum, $row['gas_tank']);
            $sheet->setCellValue('C' . $rowNum, $row['purchased_gas']);
            $sheet->setCellValue('D' . $rowNum, $row['total']);
            $sheet->setCellValue('E' . $rowNum, $row['start_odometer']);
            $sheet->setCellValue('F' . $rowNum, $row['end_odometer']);
            $sheet->setCellValue('G' . $rowNum, $row['KM_used']);
            $sheet->setCellValue('H' . $rowNum, $row['RFID_Easy']);
            $sheet->setCellValue('I' . $rowNum, $row['RFID_Auto']);
            $sheet->setCellValue('J' . $rowNum, $row['oil_used']);
            $sheet->setCellValue('K' . $rowNum, $row['vehicle_type']);
            $sheet->setCellValue('L' . $rowNum, $row['plate_num']);
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
    require_once('C:\xampp\htdocs\Gearchanix\vendor\tecnickcom\tcpdf\tcpdf.php');
    
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Your Name');
    $pdf->SetTitle('Trip Ticket Report');
    $pdf->SetSubject('Trip Ticket Data');
    $pdf->SetKeywords('TCPDF, PDF, report, trip ticket');

    // Set default header data
    $pdf->SetHeaderData('', 0, 'Trip Ticket Report', "Generated on " . date('Y-m-d H:i:s'));

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
                    <th>Trip Ticket Date</th>
                    <th>Gas Tank</th>
                    <th>Purchased Gas</th>
                    <th>Total</th>
                    <th>Start Odometer</th>
                    <th>End Odometer</th>
                    <th>KM Used</th>
                    <th>RFID Easy</th>
                    <th>RFID Auto</th>
                    <th>Oil Used</th>
                    <th>Vehicle Type</th>
                    <th>Plate Number</th>
                    </tr></thead>
                    <tbody>';
    
        // Filter data for the current month
        $stmtMonth = $conn->prepare("SELECT * FROM trip_ticket WHERE MONTH(trip_ticket_date) = ? AND YEAR(trip_ticket_date) = ?");
        $stmtMonth->bind_param('ii', $month, $year);
        $stmtMonth->execute();
        $resultMonth = $stmtMonth->get_result();
        
        while ($row = $resultMonth->fetch_assoc()) {
            $html .= '<tr>
                        <td>' . $row['trip_ticket_date'] . '</td>
                        <td>' . $row['gas_tank'] . '</td>
                        <td>' . $row['purchased_gas'] . '</td>
                        <td>' . $row['total'] . '</td>
                        <td>' . $row['start_odometer'] . '</td>
                        <td>' . $row['end_odometer'] . '</td>
                        <td>' . $row['KM_used'] . '</td>
                        <td>' . $row['RFID_Easy'] . '</td>
                        <td>' . $row['RFID_Auto'] . '</td>
                        <td>' . $row['oil_used'] . '</td>
                        <td>' . $row['vehicle_type'] . '</td>
                        <td>' . $row['plate_num'] . '</td>
                    </tr>';
        }
    
        $html .= '</tbody></table>';
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->AddPage('L'); // Add a new page for the next month
    }

    // Close the PDF document and output to the browser
    $pdf->Output("{$filenameBase}.pdf", 'D');
    exit;

} elseif ($format == 'word') {
    // Use PHPWord for Word document generation
    require 'C:/xampp/htdocs/Gearchanix/vendor/phpoffice/phpword/bootstrap.php';
    
    $phpWord = new \PhpOffice\PhpWord\PhpWord();

    // Create a new section
    $section = $phpWord->addSection();

    // Add title
    $section->addTitle('Trip Ticket Report', 1);
    $section->addText("Generated on " . date('Y-m-d H:i:s'));

    foreach ($months as $month) {
        // Add month title
        $section->addTitle(date('F', mktime(0, 0, 0, $month, 10)), 2);
        
        // Create table
        $table = $section->addTable();

        // Add table headers
        $table->addRow();
        $table->addCell(2000)->addText('Trip Ticket Date');
        $table->addCell(2000)->addText('Gas Tank');
        $table->addCell(2000)->addText('Purchased Gas');
        $table->addCell(2000)->addText('Total');
        $table->addCell(2000)->addText('Start Odometer');
        $table->addCell(2000)->addText('End Odometer');
        $table->addCell(2000)->addText('KM Used');
        $table->addCell(2000)->addText('RFID Easy');
        $table->addCell(2000)->addText('RFID Auto');
        $table->addCell(2000)->addText('Oil Used');
        $table->addCell(2000)->addText('Vehicle Type');
        $table->addCell(2000)->addText('Plate Number');

        // Filter data for the current month
        $stmtMonth = $conn->prepare("SELECT * FROM trip_ticket WHERE MONTH(trip_ticket_date) = ? AND YEAR(trip_ticket_date) = ?");
        $stmtMonth->bind_param('ii', $month, $year);
        $stmtMonth->execute();
        $resultMonth = $stmtMonth->get_result();
        
        while ($row = $resultMonth->fetch_assoc()) {
            $table->addRow();
            $table->addCell(2000)->addText($row['trip_ticket_date']);
            $table->addCell(2000)->addText($row['gas_tank']);
            $table->addCell(2000)->addText($row['purchased_gas']);
            $table->addCell(2000)->addText($row['total']);
            $table->addCell(2000)->addText($row['start_odometer']);
            $table->addCell(2000)->addText($row['end_odometer']);
            $table->addCell(2000)->addText($row['KM_used']);
            $table->addCell(2000)->addText($row['RFID_Easy']);
            $table->addCell(2000)->addText($row['RFID_Auto']);
            $table->addCell(2000)->addText($row['oil_used']);
            $table->addCell(2000)->addText($row['vehicle_type']);
            $table->addCell(2000)->addText($row['plate_num']);
        }
    }

    // Output the Word document
    header('Content-Type: application/octet-stream');
    header("Content-Disposition: attachment;filename=\"{$filenameBase}.docx\"");
    header('Cache-Control: max-age=0');
    $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
    $objWriter->save('php://output');
    exit;
}
?>

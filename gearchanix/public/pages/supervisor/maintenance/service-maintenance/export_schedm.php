<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

require 'C:/xampp/htdocs/Gearchanix/vendor/autoload.php'; // Include PhpSpreadsheet

// Start output buffering
ob_start(); 

// Database connection
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "gearchanix";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get selected year, month, and format from form
$year = isset($_GET['year']) ? (int)$_GET['year'] : null;
$month = isset($_GET['month']) ? (int)$_GET['month'] : null;  // Optional month filter
$format = isset($_GET['format']) ? $_GET['format'] : 'excel';

// Prepare SQL query to fetch records from pms table
$sql = "
SELECT 
    YEAR(p.target_date) AS year, 
    v.vehicle_name, 
    v.vehicle_model, 
    p.plate_num, 
    p.acquisition_date, 
    p.target_date AS pms_date, 
    p.workorder_ID, 
    w.assigned_to, 
    w.replaced_parts, 
    w.activity
FROM 
    pms p
LEFT JOIN 
    work_order w ON p.workorder_ID = w.workorder_ID
LEFT JOIN 
    vehicles v ON p.vehicle_ID = v.vehicle_ID
WHERE 
    YEAR(p.target_date) = ?";
    
if (!is_null($month)) {
    $sql .= " AND MONTH(p.target_date) = ?";
}

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("SQL statement preparation failed: " . $conn->error);
}

// Bind the year and month parameters
if (!is_null($month)) {
    $stmt->bind_param("ii", $year, $month); // Bind year and month
} else {
    $stmt->bind_param("i", $year); // Bind only year if month is not provided
}

$stmt->execute();
$result = $stmt->get_result();

// Generate filename based on year and optional month
$filenameBase = "pms_report_" . $year . (is_null($month) ? "" : "_$month");

// Export to Excel
if ($format == 'excel') {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle($year . (is_null($month) ? "" : "-$month"));

    // Set headers for the Excel sheet
    $headers = [
        'Year', 'Vehicle Name', 'Vehicle Model', 'Plate Number', 
        'Acquisition Date', 'PMS Date', 'Work Order', 'Assigned To', 
        'Replaced Parts', 'Activity'
    ];
    foreach ($headers as $index => $header) {
        $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($index + 1);
        $sheet->setCellValue($columnLetter . '1', $header);
    }

    // Populate data rows
    $rowNum = 2;
    while ($row = $result->fetch_assoc()) {
        $sheet->setCellValue("A{$rowNum}", $row['year']);
        $sheet->setCellValue("B{$rowNum}", $row['vehicle_name']);
        $sheet->setCellValue("C{$rowNum}", $row['vehicle_model']);
        $sheet->setCellValue("D{$rowNum}", $row['plate_num']);
        $sheet->setCellValue("E{$rowNum}", $row['acquisition_date']);
        $sheet->setCellValue("F{$rowNum}", $row['pms_date']);
        $sheet->setCellValue("G{$rowNum}", $row['workorder_ID']);
        $sheet->setCellValue("H{$rowNum}", $row['assigned_to']);
        $sheet->setCellValue("I{$rowNum}", $row['replaced_parts']);
        $sheet->setCellValue("J{$rowNum}", $row['activity']);
        $rowNum++;
    }

    // Output Excel file
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment;filename=\"{$filenameBase}.xlsx\"");
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}

// Export to PDF
if ($format == 'pdf') {
    require_once('C:\xampp\htdocs\Gearchanix\vendor\tecnickcom\tcpdf\tcpdf.php');

    $pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetTitle("PMS Report");
    $pdf->AddPage('L');
    $pdf->SetFont('helvetica', '', 10);

    // Generate table headers in PDF
    $html = '<h3>PMS Report for ' . $year . (is_null($month) ? "" : " - $month") . '</h3><table border="1" cellpadding="4"><tr>';
    $headers = ['Year', 'Vehicle Name', 'Vehicle Model', 'Plate Number', 'Acquisition Date', 'PMS Date', 'Work Order', 'Assigned To', 'Replaced Parts', 'Activity'];
    foreach ($headers as $header) {
        $html .= "<th>" . htmlspecialchars($header) . "</th>";
    }
    $html .= '</tr>';

    // Populate rows
    while ($row = $result->fetch_assoc()) {
        $html .= '<tr>';
        $html .= '<td>' . htmlspecialchars($row['year']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['vehicle_name']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['vehicle_model']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['plate_num']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['acquisition_date']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['pms_date']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['workorder_ID']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['assigned_to']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['replaced_parts']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['activity']) . '</td>';
        $html .= '</tr>';
    }
    $html .= '</table>';

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output("{$filenameBase}.pdf", 'D');
    exit;
}

// Export to Word
if ($format == 'word') {
    header("Content-type: application/vnd.ms-word");
    header("Content-Disposition: attachment;Filename={$filenameBase}.doc");

    echo '<h3>PMS Report for ' . $year . (is_null($month) ? "" : " - $month") . '</h3><table border="1" cellpadding="4"><tr>';
    $headers = ['Year', 'Vehicle Name', 'Vehicle Model', 'Plate Number', 'Acquisition Date', 'PMS Date', 'Work Order', 'Assigned To', 'Replaced Parts', 'Activity'];
    foreach ($headers as $header) {
        echo "<th>" . htmlspecialchars($header) . "</th>";
    }
    echo '</tr>';

    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['year']) . '</td>';
        echo '<td>' . htmlspecialchars($row['vehicle_name']) . '</td>';
        echo '<td>' . htmlspecialchars($row['vehicle_model']) . '</td>';
        echo '<td>' . htmlspecialchars($row['plate_num']) . '</td>';
        echo '<td>' . htmlspecialchars($row['acquisition_date']) . '</td>';
        echo '<td>' . htmlspecialchars($row['pms_date']) . '</td>';
        echo '<td>' . htmlspecialchars($row['workorder_ID']) . '</td>';
        echo '<td>' . htmlspecialchars($row['assigned_to']) . '</td>';
        echo '<td>' . htmlspecialchars($row['replaced_parts']) . '</td>';
        echo '<td>' . htmlspecialchars($row['activity']) . '</td>';
        echo '</tr>';
    }
    echo '</table>';

    exit;
}

// Close statement and connection
$stmt->close();
$conn->close();

ob_end_flush();
?>

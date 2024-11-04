<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "gearchanix";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$months = $_GET['months']; 
$year = $_GET['year'];
$columns = $_GET['columns'];
$format = $_GET['format'];

if (empty($months) || empty($year) || empty($columns)) {
    die("Please select at least one month, year, and column.");
}

// SQL
$placeholders = implode(',', array_fill(0, count($months), '?'));
$selectedColumns = implode(',', $columns);
$sql = "SELECT $selectedColumns FROM pdi_form WHERE MONTH(pdi_date) IN ($placeholders) AND YEAR(pdi_date) = ?";

$stmt = $conn->prepare($sql);
$types = str_repeat('i', count($months)) . 'i'; 
$params = array_merge($months, [$year]);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// Generate a filename based on selected months and year
$monthNames = array_map(function($month) {
    return date('F', mktime(0, 0, 0, $month, 10));
}, $months);
$monthStr = implode('_', $monthNames);
$filenameBase = "pdi_report_{$monthStr}_{$year}";

// Excel 
if ($format == 'excel') {
    // Composer's autoload
    require 'C:/xampp/htdocs/Gearchanix/vendor/autoload.php';
    
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet(); 

    $headers = [
        'plate_num' => 'Vehicle Plate Number',
        'trip_ticket' => 'Trip Ticket #',
        'pdi_date' => 'PDI Date',
        'performed_by' => 'Performed By',
        'noted_by' => 'Noted By',
        'status' => 'Status',
        'remarks' => 'Remarks'
    ];

    $colIndex = 1; 
    foreach ($columns as $col) {
        $sheet->setCellValue($columnLetter . '1', ucfirst(str_replace('_', ' ', $header)));
    }

    $rowNum = 2;
    while ($row = $result->fetch_assoc()) {
        $colIndex = 1; 
        foreach ($columns as $col) {
            $sheet->setCellValue($colIndex++, $rowNum, $row[$col]);
        }
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

    require_once('C:\xampp\htdocs\Gearchanix\vendor\tecnickcom\tcpdf\tcpdf.php');
    
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Your Name');
    $pdf->SetTitle('PDI Report');
    $pdf->SetSubject('PDI Data');
    $pdf->SetKeywords('TCPDF, PDF, report, PDI');

    // Set default header data
    $pdf->SetHeaderData('', 0, 'PDI Report', "Generated on " . date('Y-m-d H:i:s'));

    // Add a page in landscape orientation
    $pdf->AddPage('L');

    // Add table headers
    $pdf->SetFont('helvetica', 'B', 12);
    foreach ($columns as $col) {
        $pdf->Cell(40, 10, $headers[$col], 1);
    }
    $pdf->Ln();

    // Add data for each selected column
    while ($row = $result->fetch_assoc()) {
        $pdf->SetFont('helvetica', '', 12);
        foreach ($columns as $col) {
            $pdf->Cell(40, 10, $row[$col], 1);
        }
        $pdf->Ln();
    }

    // Output the PDF file
    $pdf->Output('pdi_report.pdf', 'D');
    exit;
}

// Close the database connection
$conn->close();
?>

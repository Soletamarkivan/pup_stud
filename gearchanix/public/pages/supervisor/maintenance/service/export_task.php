<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

// Get selected columns and format from form
$selectedColumns = isset($_GET['columns']) ? $_GET['columns'] : ['task_ID', 'task_name', 'description', 'MTBF', 'parts_involved'];
$format = isset($_GET['format']) ? $_GET['format'] : 'excel';

// Define allowed columns
$allColumns = [
    'task_ID' => 'Task ID',
    'task_name' => 'Task Name',
    'description' => 'Description',
    'MTBF' => 'MTBF',
    'parts_involved' => 'Parts Involved'
];

// Filter selected columns to only include valid columns
$selectedColumns = array_filter($selectedColumns, function($col) use ($allColumns) {
    return array_key_exists($col, $allColumns);
});

// Prepare headers for selected columns
$headers = array_map(function($col) use ($allColumns) {
    return $allColumns[$col];
}, $selectedColumns);

// Build SQL query to fetch all data without any date filtering
$sql = "SELECT " . implode(", ", $selectedColumns) . " FROM service_tasks";
$result = $conn->query($sql);

// Generate filename based on selected columns
$filenameBase = "service_tasks_report";

// Export to Excel
if ($format == 'excel') {
    require 'C:/xampp/htdocs/Gearchanix/vendor/autoload.php';

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle("Service Tasks");

    // Set headers for the Excel sheet
    foreach ($headers as $index => $header) {
        $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($index + 1);
        $sheet->setCellValue($columnLetter . '1', $header);
    }

    // Populate data rows
    $rowNum = 2;
    while ($row = $result->fetch_assoc()) {
        $colIndex = 1;
        foreach ($selectedColumns as $col) {
            $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
            $sheet->setCellValue($columnLetter . $rowNum, $row[$col]);
            $colIndex++;
        }
        $rowNum++;
    }

    // Output Excel file
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment;filename=\"{$filenameBase}.xlsx\"");
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;

// Export to PDF
} elseif ($format == 'pdf') {
    require_once('C:/xampp/htdocs/Gearchanix/vendor/tecnickcom/tcpdf/tcpdf.php');

    $pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetTitle("Service Tasks Report");
    $pdf->AddPage('L');
    $pdf->SetFont('helvetica', '', 10);

    // Generate table headers in PDF
    $html = '<h3>Service Tasks Report</h3><table border="1" cellpadding="4"><tr>';
    foreach ($headers as $header) {
        $html .= "<th>" . htmlspecialchars($header) . "</th>";
    }
    $html .= '</tr>';

    // Populate rows
    while ($row = $result->fetch_assoc()) {
        $html .= '<tr>';
        foreach ($selectedColumns as $col) {
            $html .= '<td>' . htmlspecialchars($row[$col]) . '</td>';
        }
        $html .= '</tr>';
    }
    $html .= '</table>';

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output("{$filenameBase}.pdf", 'D');
    exit;

// Export to Word
} elseif ($format == 'word') {
    header("Content-type: application/vnd.ms-word");
    header("Content-Disposition: attachment;Filename={$filenameBase}.doc");

    echo '<h3>Service Tasks Report</h3><table border="1" cellpadding="4"><tr>';
    foreach ($headers as $header) {
        echo "<th>" . htmlspecialchars($header) . "</th>";
    }
    echo '</tr>';

    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        foreach ($selectedColumns as $col) {
            echo '<td>' . htmlspecialchars($row[$col]) . '</td>';
        }
        echo '</tr>';
    }
    echo '</table>';

    exit;
}

// Close connection
$conn->close();

ob_end_flush();
?>

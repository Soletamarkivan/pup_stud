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

// Get selected year, months, columns, and format from form
$year = isset($_GET['year']) ? (int)$_GET['year'] : null;
$months = isset($_GET['months']) ? $_GET['months'] : []; // Array of months
$selectedColumns = isset($_GET['columns']) ? $_GET['columns'] : ['plate_num', 'trip_ticketID', 'pdi_date', 'driver', 'mechanic', 'status', 'remarks']; // Selected columns
$format = isset($_GET['format']) ? $_GET['format'] : 'excel';

// Validate selected columns to ensure only valid columns are used
$allColumns = [
    'plate_num' => 'Vehicle Plate Number',
    'trip_ticketID' => 'Trip Ticket #',
    'pdi_date' => 'PDI Date',
    'driver' => 'Performed By',
    'mechanic' => 'Noted By',
    'status' => 'Status',
    'remarks' => 'Remarks'
];

// Filter selected columns to only those in the allowed list
$selectedColumns = array_filter($selectedColumns, function($col) use ($allColumns) {
    return array_key_exists($col, $allColumns);
});

// Prepare SQL query to fetch records for the specified year and selected months
$columnsToSelect = implode(', ', $selectedColumns);
$sql = "SELECT $columnsToSelect FROM pdi_form WHERE YEAR(pdi_date) = ?";

// If multiple months are selected, use an IN clause
if (!empty($months)) {
    $placeholders = implode(',', array_fill(0, count($months), '?'));
    $sql .= " AND MONTH(pdi_date) IN ($placeholders)";
}

// Prepare and bind parameters
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("SQL statement preparation failed: " . $conn->error);
}

// Bind parameters: first year, then selected months
$params = [$year];
$params = array_merge($params, $months);
$stmt->bind_param(str_repeat('i', count($params)), ...$params);
$stmt->execute();
$result = $stmt->get_result();

// Generate filename based on year and selected months
$filenameBase = "pdi_form_report_" . $year . (!empty($months) ? "_" . implode('_', $months) : "");

// Prepare headers for selected columns
$headers = array_map(function($col) use ($allColumns) {
    return $allColumns[$col];
}, $selectedColumns);

// Export to Excel
if ($format == 'excel') {
    require 'C:/xampp/htdocs/Gearchanix/vendor/autoload.php';

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle($year . (!empty($months) ? "-" . implode('-', $months) : ""));

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
    require_once('C:\xampp\htdocs\Gearchanix\vendor\tecnickcom\tcpdf\tcpdf.php');

    $pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetTitle("PDI Form Report");
    $pdf->AddPage('L');
    $pdf->SetFont('helvetica', '', 10);

    // Generate table headers in PDF
    $html = '<h3>PDI Form Report for ' . $year . (!empty($months) ? " - " . implode(', ', $months) : "") . '</h3><table border="1" cellpadding="4"><tr>';
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

    echo '<h3>PDI Form Report for ' . $year . (!empty($months) ? " - " . implode(', ', $months) : "") . '</h3><table border="1" cellpadding="4"><tr>';
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

// Close statement and connection
$stmt->close();
$conn->close();

ob_end_flush();
?>

<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

ob_start(); 

$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "gearchanix";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get format and selected columns
$format = isset($_GET['format']) ? $_GET['format'] : null;
$selectedColumns = isset($_GET['columns']) ? $_GET['columns'] : [];

// Define the selected columns for maintenance_request table
if (empty($selectedColumns)) {
    $selectedColumns = [
        'queue_num', 'vehicle_name', 'vehicle_type', 'plate_num',
        'driver', 'service_task', 'start_date', 'target_date',
        'parts', 'receiver', 'date', 'noted_by', 'approval_chief', 'approval_office'
    ];
}

$filenameBase = "maintenance_request_report";

// Prepare SQL query to fetch data based on target date of completion
$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
$months = isset($_GET['months']) ? $_GET['months'] : [];

// Construct the WHERE clause based on selected months and year
$whereClause = [];
if (!empty($months)) {
    $monthPlaceholders = implode(',', array_fill(0, count($months), '?'));
    $whereClause[] = "MONTH(target_date) IN ($monthPlaceholders)";
}
$whereClause[] = "YEAR(target_date) = ?";

// Combine the WHERE clause
$sql = "SELECT " . implode(',', $selectedColumns) . " FROM maintenance_request WHERE " . implode(' AND ', $whereClause);

// Prepare statement
$stmt = $conn->prepare($sql);

// Bind parameters
$bindParams = array_merge($months, [$year]);
$stmt->bind_param(str_repeat('i', count($months)) . 'i', ...$bindParams);

// Execute query
$stmt->execute();
$result = $stmt->get_result();

if ($format == 'excel') {
    require 'C:/xampp/htdocs/Gearchanix/vendor/autoload.php';
    $spreadsheet = new Spreadsheet();

    $headers = array_map('ucfirst', $selectedColumns);

    // Create a single sheet for all selected data
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Maintenance Request Report');

    // Set the headers for the Excel sheet
    foreach ($headers as $index => $header) {
        $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($index + 1);
        $sheet->setCellValue($columnLetter . '1', $header);
    }

    // Populate data rows
    $rowNum = 2;
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            foreach ($selectedColumns as $index => $column) {
                $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($index + 1);
                $sheet->setCellValue($columnLetter . $rowNum, $row[$column] ?? '');
            }
            $rowNum++;
        }
    } else {
        $sheet->setCellValue('A' . $rowNum, 'No records found for the selected criteria.');
    }

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment;filename=\"{$filenameBase}.xlsx\"");
    header('Cache-Control: max-age=0');
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;

} elseif ($format == 'pdf') {
    require_once('C:/xampp/htdocs/Gearchanix/vendor/tecnickcom/tcpdf/tcpdf.php');
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Your Name');
    $pdf->SetTitle('Maintenance Request Report');
    $pdf->SetHeaderData('', 0, 'Maintenance Request Report', "Generated on " . date('Y-m-d H:i:s'));
    $pdf->SetHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $pdf->AddPage('L');
    $pdf->SetFont('helvetica', '', 10);

    $html = '<h2 style="text-align:center;">Maintenance Request Report</h2>';
    $html .= '<table border="1" cellpadding="5" cellspacing="0" style="width:100%;">';
    $html .= '<thead><tr style="background-color:#f2f2f2;">';
    foreach ($selectedColumns as $header) {
        $html .= "<th style='text-align:center; font-weight:bold;'>" . ucfirst($header) . "</th>";
    }
    $html .= '</tr></thead><tbody>';

    // Populate PDF data
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $html .= '<tr>';
            foreach ($selectedColumns as $column) {
                $html .= '<td style="text-align:center;">' . htmlspecialchars($row[$column] ?? '') . '</td>';
            }
            $html .= '</tr>';
        }
    } else {
        $html .= '<tr><td colspan="' . count($selectedColumns) . '" style="text-align:center;">No records found for the selected criteria.</td></tr>';
    }
    $html .= '</tbody></table>';
    $pdf->writeHTML($html, true, false, true, false, '');
    ob_end_clean();
    $pdf->Output("{$filenameBase}.pdf", 'D');
    exit;

} elseif ($format == 'word') {
    header("Content-type: application/vnd.ms-word");
    header("Content-Disposition: attachment;Filename={$filenameBase}.doc");

    echo "<h3 style='text-align:center;'>Maintenance Request Report</h3>";
    echo '<table border="1" cellpadding="5" cellspacing="0" style="width:100%;">';
    echo '<thead><tr>';
    foreach ($selectedColumns as $header) {
        echo "<th style='text-align:center; font-weight:bold;'>" . ucfirst($header) . "</th>";
    }
    echo '</tr></thead><tbody>';

    // Populate Word data
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            foreach ($selectedColumns as $column) {
                echo '<td style="text-align:center;">' . htmlspecialchars($row[$column] ?? '') . '</td>';
            }
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="' . count($selectedColumns) . '" style="text-align:center;">No records found for the selected criteria.</td></tr>';
    }
    echo '</tbody></table>';
    exit;
}

$conn->close();
?>

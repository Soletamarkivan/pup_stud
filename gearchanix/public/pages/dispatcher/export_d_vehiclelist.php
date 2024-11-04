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

$year = isset($_GET['year']) ? (int)$_GET['year'] : null; 
$format = isset($_GET['format']) ? $_GET['format'] : null; 

// Get selected columns - initialize as an empty array
$selectedColumns = isset($_GET['columns']) ? $_GET['columns'] : [];

// Valid Columns
$validColumns = [
    'vehicle_name', 'vehicle_model', 'vehicle_year', 
    'vehicle_vin', 'vehicle_type', 'plate_num', 
    'lifespan', 'current_meter', 'pms_date', 
    'ems_date', 'vehicle_remarks', 'vehicle_status'
];

$selectedColumns = array_intersect($selectedColumns, $validColumns);

// SQL code
$columnsToSelect = empty($selectedColumns) ? '*' : implode(', ', $selectedColumns);
$sql = "SELECT $columnsToSelect FROM vehicles WHERE vehicle_year = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("SQL statement preparation failed: " . $conn->error);
}

$stmt->bind_param("i", $year); // 'i' for integer binding


$stmt->execute();
$result = $stmt->get_result();

// Generate a filename based on the year
$filenameBase = "vehicle_list_report_" . $year;

// Excel Format
if ($format == 'excel') {
    require 'C:/xampp/htdocs/Gearchanix/vendor/autoload.php';
    
    // Create new Spreadsheet 
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle($year); 

    // Populate headers based on valid selected columns
    $headerRow = $selectedColumns; 
    
    // Headers 
    foreach ($headerRow as $index => $header) {
        // Convert header names to a readable format 
        $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($index + 1);
        $sheet->setCellValue($columnLetter . '1', ucfirst(str_replace('_', ' ', $header)));
    }

    // Data
    $rowNum = 2; 
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            foreach ($headerRow as $index => $header) {
                // Cell Value - A1 notation
                $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($index + 1);
                $sheet->setCellValue($columnLetter . $rowNum, isset($row[$header]) ? $row[$header] : '');
            }
            $rowNum++;
        }
    } else {
        // No records found message
        $sheet->setCellValue('A' . $rowNum, 'No records found for the selected year and criteria.');
    }


    // Excel file output
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment;filename=\"{$filenameBase}.xlsx\"");
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
} elseif ($format == 'pdf') {
    // TCPDF - PDF generation
    require_once('C:\xampp\htdocs\Gearchanix\vendor\tecnickcom\tcpdf\tcpdf.php');

    // Initialize TCPDF
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Your Name');
    $pdf->SetTitle('Vehicle List Report');
    $pdf->SetSubject('Vehicle List Data');
    $pdf->SetKeywords('TCPDF, PDF, report, vehiclelist');

    // Header data
    $pdf->SetHeaderData('', 0, 'Vehicle List Report', "Generated on " . date('Y-m-d H:i:s'));

    // Header and footer fonts
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // Margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    // Page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    // Landscape orientation
    $pdf->AddPage('L'); 

    $pdf->SetFont('helvetica', '', 9);

    if ($result && $result->num_rows > 0) {
        $html = '<table border="1" cellpadding="5">
                    <thead>
                        <tr>';
        foreach ($selectedColumns as $column) {
            $html .= "<th>" . htmlspecialchars(ucfirst(str_replace('_', ' ', $column))) . "</th>";
        }
        $html .= '         </tr>
                    </thead>
                    <tbody>';

        // Data
        while ($row = $result->fetch_assoc()) {
            $html .= '<tr>';
            foreach ($selectedColumns as $column) {
                $html .= '<td>' . htmlspecialchars($row[$column] ?? '') . '</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</tbody></table>';

        $pdf->writeHTML($html, true, false, true, false, '');
    } else {
        // No records found message
        $pdf->writeHTML('<h2>No records found for the selected criteria.</h2>', true, false, true, false, '');
    }

    // PDF output
    ob_end_clean(); 
    $pdf->Output("{$filenameBase}.pdf", 'D');
    exit;
} elseif ($format == 'word') {
    header("Content-type: application/vnd.ms-word");
    header("Content-Disposition: attachment;Filename=vehicle_list_report_{$year}.doc");

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

    // Document title
    echo "<h3>Vehicle List Report for {$year}</h3>";
    
    // Table structure
    echo '<table>';
    echo '<tr>';
    foreach ($selectedColumns as $column) {
        echo "<th>" . ucfirst(str_replace('_', ' ', $column)) . "</th>"; // Capitalize headers
    }
    echo '</tr>';

    // Data
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        foreach ($selectedColumns as $column) {
            echo '<td>' . htmlspecialchars($row[$column]) . '</td>';
        }
        echo '</tr>';
    }
    echo '</table>';

    exit;
}

$stmt->close();
$conn->close();

ob_end_flush();
?>

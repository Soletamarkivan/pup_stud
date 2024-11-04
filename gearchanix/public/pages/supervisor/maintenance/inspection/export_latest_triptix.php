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
$selectedMonths = isset($_GET['months']) ? array_map('intval', $_GET['months']) : [];
$selectedColumns = isset($_GET['columns']) ? $_GET['columns'] : [];

if (empty($selectedColumns)) {
    $selectedColumns = [
        'trip_ticket_date', 'vehicle_type', 'plate_num', 'gas_tank',
        'purchased_gas', 'total', 'start_odometer', 'end_odometer',
        'KM_used', 'RFID_Easy', 'RFID_Auto', 'oil_used'
    ];
}

if (empty($selectedMonths)) {
    die("Please select at least one month.");
}

$filenameBase = "trip_ticket_report_" . $year;

if ($format == 'excel') {
    require 'C:/xampp/htdocs/Gearchanix/vendor/autoload.php';
    $spreadsheet = new Spreadsheet();

    $monthNames = [
        1 => "January", 2 => "February", 3 => "March", 4 => "April",
        5 => "May", 6 => "June", 7 => "July", 8 => "August",
        9 => "September", 10 => "October", 11 => "November", 12 => "December"
    ];

    foreach ($selectedMonths as $month) {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle($monthNames[$month]);

        $headers = array_map('ucfirst', $selectedColumns);

        foreach ($headers as $index => $header) {
            $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($index + 1);
            $sheet->setCellValue($columnLetter . '1', $header);
        }

        $sql = "
        SELECT " . implode(',', $selectedColumns) . "
        FROM history_triptix
        WHERE YEAR(trip_ticket_date) = ? 
        AND MONTH(trip_ticket_date) = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $year, $month);
        $stmt->execute();
        $result = $stmt->get_result();

        $rowNum = 2;
        if ($result->num_rows > 0) {
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
    }

    $spreadsheet->removeSheetByIndex(0);
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
    $pdf->SetTitle('Trip Ticket Report');
    $pdf->SetHeaderData('', 0, 'Trip Ticket Report', "Generated on " . date('Y-m-d H:i:s'));
    $pdf->SetHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $pdf->AddPage('L');
    $pdf->SetFont('helvetica', '', 10);

    $html = '<h2 style="text-align:center;">Trip Ticket Report for ' . $year . '</h2>';
    $html .= '<table border="1" cellpadding="5" cellspacing="0" style="width:100%;">';
    $html .= '<thead><tr style="background-color:#f2f2f2;">';
    foreach ($selectedColumns as $header) {
        $html .= "<th style='text-align:center; font-weight:bold;'>" . ucfirst($header) . "</th>";
    }
    $html .= '</tr></thead><tbody>';

    foreach ($selectedMonths as $month) {
        $sql = "
        SELECT " . implode(',', $selectedColumns) . "
        FROM history_triptix
        WHERE YEAR(trip_ticket_date) = ? 
        AND MONTH(trip_ticket_date) = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $year, $month);
        $stmt->execute();
        $result = $stmt->get_result();

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
    }
    $html .= '</tbody></table>';
    $pdf->writeHTML($html, true, false, true, false, '');
    ob_end_clean();
    $pdf->Output("{$filenameBase}.pdf", 'D');
    exit;
} elseif ($format == 'word') {
    header("Content-type: application/vnd.ms-word");
    header("Content-Disposition: attachment;Filename=trip_ticket_report_{$year}.doc");

    echo "<h3 style='text-align:center;'>Trip Ticket Report for {$year}</h3>";
    echo '<table border="1" cellpadding="5" cellspacing="0" style="width:100%;">';
    echo '<thead><tr>';
    foreach ($selectedColumns as $header) {
        echo "<th style='text-align:center; font-weight:bold;'>" . ucfirst($header) . "</th>";
    }
    echo '</tr></thead><tbody>';

    foreach ($selectedMonths as $month) {
        $sql = "
        SELECT " . implode(',', $selectedColumns) . "
        FROM history_triptix
        WHERE YEAR(trip_ticket_date) = ? 
        AND MONTH(trip_ticket_date) = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $year, $month);
        $stmt->execute();
        $result = $stmt->get_result();

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
    }
    echo '</tbody></table>';
    exit;
}

$conn->close();
?>

<?php
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
$year = isset($_GET['year']) ? (int)$_GET['year'] : null; // Ensure year is an integer
$format = isset($_GET['format']) ? $_GET['format'] : null; // Output format (excel, pdf, word)

// Capture selected months from the form
$selectedMonths = isset($_GET['months']) ? array_map('intval', $_GET['months']) : []; // Get months as an array of integers

// Capture selected columns from the form
$selectedColumns = isset($_GET['columns']) ? $_GET['columns'] : []; // Get selected columns

// Default columns if none selected (modify as necessary)
if (empty($selectedColumns)) {
    $selectedColumns = [
        'trip_ticket_date',
        'vehicle_type',
        'plate_num',
        'gas_tank',
        'purchased_gas',
        'total',
        'start_odometer',
        'end_odometer',
        'KM_used',
        'RFID_Easy',
        'RFID_Auto',
        'oil_used'
    ]; 
}

// Ensure at least one month is selected
if (empty($selectedMonths)) {
    die("Please select at least one month.");
}

// Prepare SQL statement to fetch the required fields from history_triptix
$sql = "
SELECT " . implode(',', $selectedColumns) . "
FROM history_triptix
WHERE YEAR(trip_ticket_date) = ? 
AND MONTH(trip_ticket_date) IN (" . implode(',', array_fill(0, count($selectedMonths), '?')) . ")"; // Filtering by year and selected months

$stmt = $conn->prepare($sql);

// Check if the statement preparation was successful
if (!$stmt) {
    die("SQL statement preparation failed: " . $conn->error);
}

// Bind the year parameter and month parameters
$stmt->bind_param("i" . str_repeat("i", count($selectedMonths)), $year, ...$selectedMonths); // 'i' for integer binding

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

// Generate a filename based on the year
$filenameBase = "trip_ticket_report_" . $year;

// Handle Excel format with one sheet for the selected year
if ($format == 'excel') {
    // Include PhpSpreadsheet
    require 'C:/xamp/htdocs/Gearchanix/vendor/autoload.php';
    
    // Create new Spreadsheet object
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle($year); // Set sheet title to the year

    // Define headers based on selected columns
    $headers = array_map('ucfirst', $selectedColumns); // Capitalize headers for display

    // Set headers in the Excel file
    foreach ($headers as $index => $header) {
        $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($index + 1);
        $sheet->setCellValue($columnLetter . '1', $header);
    }

    // Populate the data
    $rowNum = 2; // Starting row for data
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            foreach ($selectedColumns as $index => $column) {
                $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($index + 1);
                $sheet->setCellValue($columnLetter . $rowNum, isset($row[$column]) ? $row[$column] : '');
            }
            $rowNum++;
        }
    } else {
        // No records found message
        $sheet->setCellValue('A' . $rowNum, 'No records found for the selected year and criteria.');
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
    require_once('C:/xamp/htdocs/Gearchanix/vendor/tecnickcom/tcpdf/tcpdf.php');

    // Initialize TCPDF
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Your Name');
    $pdf->SetTitle('Trip Ticket Report');
    $pdf->SetSubject('Trip Ticket Data');
    $pdf->SetKeywords('TCPDF, PDF, report, tripticket');

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
    $pdf->SetFont('helvetica', '', 10);

    // Check if $result is valid
    if ($result && $result->num_rows > 0) {
        // Table content
        $html = '<h2 style="text-align:center;">Trip Ticket Report for ' . $year . '</h2>';
        $html .= '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse:collapse; width:100%;">
                    <thead>
                        <tr style="background-color:#f2f2f2;">';
        // Create headers based on selected columns
        foreach ($headers as $header) {
            $html .= "<th style='font-weight:bold; text-align:center;'>" . htmlspecialchars($header) . "</th>";
        }
        $html .= '         </tr>
                    </thead>
                    <tbody>';

        // Populate the table rows with data
        while ($row = $result->fetch_assoc()) {
            $html .= '<tr>';
            foreach ($selectedColumns as $column) {
                $html .= '<td style="text-align:center;">' . htmlspecialchars($row[$column] ?? '') . '</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</tbody></table>';
        
        // Output the HTML content as a PDF
        $pdf->writeHTML($html, true, false, true, false, '');
    } else {
        // No records found message
        $pdf->writeHTML('<h2>No records found for the selected criteria.</h2>', true, false, true, false, '');
    }

    // Output the PDF file
    ob_end_clean(); // Clear any output buffers
    $pdf->Output("{$filenameBase}.pdf", 'D');
    exit;
} elseif ($format == 'word') {
    // Set the content type and filename for the Word document
    header("Content-type: application/vnd.ms-word");
    header("Content-Disposition: attachment;Filename=trip_ticket_report_{$year}.doc");

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
    echo "<h3>Trip Ticket Report for {$year}</h3>";
    
    // Create the table structure
    echo '<table>';
    echo '<tr>';
    foreach ($headers as $header) {
        echo "<th>" . ucfirst(strtolower($header)) . "</th>"; // Capitalize headers
    }
    echo '</tr>';

    // Populate the table rows with data
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            foreach ($selectedColumns as $column) {
                echo '<td>' . htmlspecialchars($row[$column] ?? '') . '</td>';
            }
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="' . count($headers) . '">No records found for the selected criteria.</td></tr>';
    }
    echo '</table>';
    exit;
}

// Close the database connection
$conn->close();
?>

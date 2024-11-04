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
$year = isset($_GET['year']) ? (int)$_GET['year'] : null; // Ensure year is an integer
$format = isset($_GET['format']) ? $_GET['format'] : null; // Output format (excel, pdf, word)

// Get selected columns from the form, initialize as an empty array if not set
$selectedColumns = isset($_GET['columns']) ? $_GET['columns'] : [];

// Check if selected columns are valid and available in the database
$validColumns = [
    'pdi_date', 'battery', 'vehicle_year', 
    'vehicle_vin', 'vehicle_type', 'plate_num', 
    'lifespan', 'current_meter', 'pms_date', 
    'ems_date', 'vehicle_remarks', 'vehicle_status'
];

// Filter the selected columns to ensure they are valid
$selectedColumns = array_intersect($selectedColumns, $validColumns);

// Prepare SQL statement with selected columns
$columnsToSelect = empty($selectedColumns) ? '*' : implode(', ', $selectedColumns);
$sql = "SELECT $columnsToSelect FROM vehicles WHERE vehicle_year = ?";
$stmt = $conn->prepare($sql);

// Check if the statement preparation was successful
if (!$stmt) {
    die("SQL statement preparation failed: " . $conn->error);
}

// Bind the year parameter
$stmt->bind_param("i", $year); // 'i' for integer binding

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

// Generate a filename based on the year
$filenameBase = "vehicle_list_report_" . $year;

// Handle Excel format with one sheet for the selected year
if ($format == 'excel') {
    // Include PhpSpreadsheet
    require 'C:/xampp/htdocs/Gearchanix/vendor/autoload.php';
    
    // Create new Spreadsheet object
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle($year); // Set sheet title to the year

    // Populate headers based on valid selected columns
    $headerRow = $selectedColumns; // Use filtered selected columns directly
    // Set headers in the Excel file
    
    // Set headers in the Excel file
    foreach ($headerRow as $index => $header) {
        // Convert header names to a readable format (capitalized, with spaces)
        $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($index + 1);
        $sheet->setCellValue($columnLetter . '1', ucfirst(str_replace('_', ' ', $header)));
    }

    // Populate the data
    $rowNum = 2; // Starting row for data
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            foreach ($headerRow as $index => $header) {
                // Set cell value using A1 notation
                $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($index + 1);
                $sheet->setCellValue($columnLetter . $rowNum, isset($row[$header]) ? $row[$header] : '');
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
    require_once('C:\xampp\htdocs\Gearchanix\vendor\tecnickcom\tcpdf\tcpdf.php');

    // Initialize TCPDF
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
    if ($result && $result->num_rows > 0) {
        // Table content
        $html = '<table border="1" cellpadding="5">
                    <thead>
                        <tr>';
        // Create headers
        foreach ($selectedColumns as $column) {
            $html .= "<th>" . htmlspecialchars(ucfirst(str_replace('_', ' ', $column))) . "</th>";
        }
        $html .= '         </tr>
                    </thead>
                    <tbody>';

        // Populate the table rows with data
        while ($row = $result->fetch_assoc()) {
            $html .= '<tr>';
            foreach ($selectedColumns as $column) {
                $html .= '<td>' . htmlspecialchars($row[$column] ?? '') . '</td>';
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
    echo '<tr>';
    foreach ($selectedColumns as $column) {
        echo "<th>" . ucfirst(str_replace('_', ' ', $column)) . "</th>"; // Capitalize headers
    }
    echo '</tr>';

    // Populate the table rows with data
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

// Close statement and connection
$stmt->close();
$conn->close();

ob_end_flush();
?>

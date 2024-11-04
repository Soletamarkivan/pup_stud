<?php
// Place 'use' statements here, right after opening PHP tag
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

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

// Get the selected year, months, and format from the form
$year = isset($_GET['year']) ? (int)$_GET['year'] : null; // Ensure year is an integer
$months = isset($_GET['months']) ? $_GET['months'] : []; // Ensure months is an array
$format = isset($_GET['format']) ? $_GET['format'] : null; // Output format (excel, pdf, word)

// Get selected columns from the form, initialize as an empty array if not set
$selectedColumns = isset($_GET['columns']) ? $_GET['columns'] : [];

// Validate that the months are between 1 and 12
$validMonths = array_filter($months, function($month) {
    return $month >= 1 && $month <= 12;
});

// Check if selected columns are valid and available in the database
$validColumns = [
    'part_name', 'part_mtbf', 'part_ornum', 
    'part_date_procurred', 'part_date_inspected', 'part_date_accomplished', 
    'part_remarks', 'part_num_of_days', 'part_description'
];

// Filter the selected columns to ensure they are valid
$selectedColumns = array_intersect($selectedColumns, $validColumns);

// Prepare SQL statement with selected columns and filter by year and months
$columnsToSelect = empty($selectedColumns) ? '*' : implode(', ', $selectedColumns);
$sql = "SELECT $columnsToSelect FROM vehicle_parts WHERE YEAR(part_date_procurred) = ?";

// If valid months are provided, add a month filter
if (!empty($validMonths)) {
    $monthPlaceholders = implode(',', array_fill(0, count($validMonths), '?'));
    $sql .= " AND MONTH(part_date_procurred) IN ($monthPlaceholders)";
}

$stmt = $conn->prepare($sql);

// Bind the year and months to the prepared statement
$bindTypes = str_repeat('i', count($validMonths) + 1); // 'i' for integer binding
$params = array_merge([$year], $validMonths); // Combine year and months for binding

$stmt->bind_param($bindTypes, ...$params);

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

// Generate a filename based on the year
$filenameBase = "vehicle_parts_report_" . $year;

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
    foreach ($headerRow as $index => $header) {
        $columnLetter = Coordinate::stringFromColumnIndex($index + 1);
        $sheet->setCellValue($columnLetter . '1', ucfirst(str_replace('_', ' ', $header)));
    }

    // Populate the data, row-by-row
    $rowNum = 2; // Starting row for data
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            foreach ($headerRow as $index => $header) {
                $columnLetter = Coordinate::stringFromColumnIndex($index + 1);
                $sheet->setCellValue($columnLetter . $rowNum, isset($row[$header]) ? $row[$header] : '');
            }
            $rowNum++;
        }
    } else {
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
    $pdf->SetTitle('Vehicle Parts Report');
    $pdf->SetSubject('Vehicle Parts Data');
    $pdf->SetKeywords('TCPDF, PDF, report, vehicleparts');

    // Set default header data
    $pdf->SetHeaderData('', 0, 'Vehicle Parts Report', "Generated on " . date('Y-m-d H:i:s'));

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
    header("Content-Disposition: attachment;Filename=vehicle_parts_report_{$year}.doc");

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
    echo "<h3>Vehicle Parts Report for {$year}</h3>";
    
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
<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Database connection
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "gearchanix";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get selected columns and role filter
$selectedColumns = isset($_GET['columns']) ? $_GET['columns'] : [];
$role = isset($_GET['role']) ? $_GET['role'] : 'ALL';
$format = isset($_GET['format']) ? $_GET['format'] : 'excel'; // Default to Excel

// Define available columns with SQL-compatible names
$validColumns = [
    'name' => "CONCAT(first_name, ' ', last_name) AS name",
    'user_email' => 'user_email',
    'contact_num' => 'contact_num'
];

// Validate selected columns and build SQL selection
$selectedColumns = array_intersect_key($validColumns, array_flip($selectedColumns));
$columnsToSelect = implode(", ", $selectedColumns) ?: "CONCAT(first_name, ' ', last_name) AS name, user_email, contact_num";

// SQL query with role filtering
$sql = "SELECT user_role, $columnsToSelect FROM users";
if ($role != 'ALL') {
    $sql .= " WHERE user_role = ?";
}

$stmt = $conn->prepare($sql);
if ($role != 'ALL') {
    $stmt->bind_param("s", $role);
}
$stmt->execute();
$result = $stmt->get_result();

// Prepare filename
$filenameBase = "users_export_" . date("Ymd_His");

// Export to Excel
if ($format == 'excel') {
    require 'C:/xampp/htdocs/Gearchanix/vendor/autoload.php';

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle("User Data");

    // Add headers
    $headers = ['Role'] + array_keys($selectedColumns);
    foreach ($headers as $index => $header) {
        $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($index + 1);
        $sheet->setCellValue($columnLetter . '1', ucfirst(str_replace('_', ' ', $header)));
    }

    // Add data
    $rowNum = 2;
    while ($row = $result->fetch_assoc()) {
        $sheet->setCellValue("A{$rowNum}", $row['user_role']);
        foreach (array_keys($selectedColumns) as $index => $columnKey) {
            $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(array_search($columnKey, array_keys($selectedColumns)) + 2);
            $sheet->setCellValue($columnLetter . $rowNum, $row[$columnKey]);
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
    $pdf->SetTitle("User Data Export");
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 10);

    // Create table headers
    $html = '<h3>User Data Export</h3><table border="1" cellpadding="4"><tr><th>Role</th>';
    foreach (array_keys($selectedColumns) as $header) {
        $html .= "<th>" . ucfirst(str_replace('_', ' ', $header)) . "</th>";
    }
    $html .= '</tr>';

    // Populate table rows
    while ($row = $result->fetch_assoc()) {
        $html .= '<tr><td>' . $row['user_role'] . '</td>';
        foreach (array_keys($selectedColumns) as $columnKey) {
            $html .= '<td>' . htmlspecialchars($row[$columnKey]) . '</td>';
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

    echo '<h3>User Data Export</h3><table border="1" cellpadding="4"><tr><th>Role</th>';
    foreach (array_keys($selectedColumns) as $header) {
        echo "<th>" . ucfirst(str_replace('_', ' ', $header)) . "</th>";
    }
    echo '</tr>';

    while ($row = $result->fetch_assoc()) {
        echo '<tr><td>' . htmlspecialchars($row['user_role']) . '</td>';
        foreach (array_keys($selectedColumns) as $columnKey) {
            echo '<td>' . htmlspecialchars($row[$columnKey]) . '</td>';
        }
        echo '</tr>';
    }
    echo '</table>';
    exit;
}

// Close connection
$stmt->close();
$conn->close();
?>

<?php
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

// Get the selected month, year, and format from the form
$month = $_GET['month'];
$year = $_GET['year'];
$format = $_GET['format'];

// Fetching the records for the selected month and year
$sql = "SELECT reservation_status, vehicle_type, reservation_date, location, duration, time_departure, no_passengers, office_dept, email, contact_no, service_type, purpose, passenger_manifest 
        FROM client_reservation 
        WHERE MONTH(reservation_date) = ? AND YEAR(reservation_date) = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $month, $year);
$stmt->execute();
$result = $stmt->get_result();

// Check the export format
if ($format == 'pdf') {
    require_once('C:\xampp\htdocs\Gearchanix\vendor\tecnickcom\tcpdf\tcpdf.php');
    $pdf = new TCPDF();
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 12);

    $html = '<h3>Client Reservation Report for ' . $month . '/' . $year . '</h3><table border="1" cellpadding="5"><thead><tr><th>Status</th><th>Vehicle Type</th><th>Scheduled Date</th><th>Destination</th><th>Duration</th><th>Time of Departure</th><th>No. of Passengers</th><th>Office/Department</th><th>Email</th><th>Contact No.</th><th>Service Type</th><th>Purpose</th></tr></thead><tbody>';
    while ($row = $result->fetch_assoc()) {
        $html .= '<tr><td>' . $row['reservation_status'] . '</td><td>' . $row['vehicle_type'] . '</td><td>' . $row['reservation_date'] . '</td><td>' . $row['location'] . '</td><td>' . $row['duration'] . '</td><td>' . $row['time_departure'] . '</td><td>' . $row['no_passengers'] . '</td><td>' . $row['office_dept'] . '</td><td>' . $row['email'] . '</td><td>' . $row['contact_no'] . '</td><td>' . $row['service_type'] . '</td><td>' . $row['purpose'] . '</td></tr>';
    }
    $html .= '</tbody></table>';

    $pdf->writeHTML($html);
    $pdf->Output('client_reservation_report_' . $month . '_' . $year . '.pdf', 'D');
}

if ($format == 'excel') {
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=client_reservation_report_" . $month . "_" . $year . ".xls");

    echo '<table border="1">';
    echo '<tr><th>Status</th><th>Vehicle Type</th><th>Scheduled Date</th><th>Destination</th><th>Duration</th><th>Time of Departure</th><th>No. of Passengers</th><th>Office/Department</th><th>Email</th><th>Contact No.</th><th>Service Type</th><th>Purpose</th></tr>';

    while ($row = $result->fetch_assoc()) {
        echo '<tr><td>' . $row['reservation_status'] . '</td><td>' . $row['vehicle_type'] . '</td><td>' . $row['reservation_date'] . '</td><td>' . $row['location'] . '</td><td>' . $row['duration'] . '</td><td>' . $row['time_departure'] . '</td><td>' . $row['no_passengers'] . '</td><td>' . $row['office_dept'] . '</td><td>' . $row['email'] . '</td><td>' . $row['contact_no'] . '</td><td>' . $row['service_type'] . '</td><td>' . $row['purpose'] . '</td></tr>';
    }

    echo '</table>';
}

if ($format == 'word') {
    header("Content-type: application/vnd.ms-word");
    header("Content-Disposition: attachment;Filename=client_reservation_report_" . $month . "_" . $year . ".doc");

    echo '<h3>Client Reservation Report for ' . $month . '/' . $year . '</h3>';
    echo '<table border="1" cellpadding="5">';
    echo '<tr><th>Status</th><th>Vehicle Type</th><th>Scheduled Date</th><th>Destination</th><th>Duration</th><th>Time of Departure</th><th>No. of Passengers</th><th>Office/Department</th><th>Email</th><th>Contact No.</th><th>Service Type</th><th>Purpose</th></tr>';

    while ($row = $result->fetch_assoc()) {
        echo '<tr><td>' . $row['reservation_status'] . '</td><td>' . $row['vehicle_type'] . '</td><td>' . $row['reservation_date'] . '</td><td>' . $row['location'] . '</td><td>' . $row['duration'] . '</td><td>' . $row['time_departure'] . '</td><td>' . $row['no_passengers'] . '</td><td>' . $row['office_dept'] . '</td><td>' . $row['email'] . '</td><td>' . $row['contact_no'] . '</td><td>' . $row['service_type'] . '</td><td>' . $row['purpose'] . '</td></tr>';
    }

    echo '</table>';
}

$conn->close();
?>

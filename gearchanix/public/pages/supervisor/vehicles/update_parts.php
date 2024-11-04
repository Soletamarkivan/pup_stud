<?php
// Database connection details
$host = '127.0.0.1';
$db = 'gearchanix';
$user = 'root';
$pass = '';

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $vehicleparts_ID = $_POST['vehicleparts_ID'];
    $part_name = $_POST['part_name'];
    $part_mtbf = $_POST['part_mtbf'];

    // Handle NULL values for part_ornum and part_date_accomplished
    $part_ornum = !empty($_POST['part_ornum']) ? $_POST['part_ornum'] : NULL;
    $part_date_accomplished = !empty($_POST['part_date_accomplished']) ? $_POST['part_date_accomplished'] : NULL;

    $part_date_procurred = $_POST['part_date_procurred'];
    $part_date_inspected = $_POST['part_date_inspected'];
    $part_num_of_days = $_POST['part_num_of_days'];
    $part_remarks = $_POST['part_remarks'];
    $part_description = $_POST['part_description'];

    // Log the incoming data for debugging
    error_log("Updating part with ID: $vehicleparts_ID");
    error_log("Data: " . print_r($_POST, true)); // Log all POST data

    // Prepare the SQL statement
    $sql = "UPDATE vehicle_parts SET 
        part_name = ?, 
        part_mtbf = ?, 
        part_ornum = ?, 
        part_date_procurred = ?, 
        part_date_inspected = ?, 
        part_date_accomplished = ?, 
        part_num_of_days = ?, 
        part_remarks = ?, 
        part_description = ? 
        WHERE vehicleparts_ID = ?";

    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare($sql);

    // Bind parameters, use null handling for the date and ornum
    $stmt->bind_param(
        "siississsi", 
        $part_name, 
        $part_mtbf, 
        $part_ornum, 
        $part_date_procurred, 
        $part_date_inspected, 
        $part_date_accomplished, 
        $part_num_of_days, 
        $part_remarks, 
        $part_description, 
        $vehicleparts_ID
    );

    // Execute the statement and check for success
    if ($stmt->execute()) {
        error_log("Successfully updated part with ID: $vehicleparts_ID"); // Log success
        echo "success"; // Return success message
    } else {
        error_log("Error updating record: " . $stmt->error); // Log error message
        echo "Error updating record: " . $stmt->error; // Return error message
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    // Handle invalid request method
    error_log("Invalid request method: " . $_SERVER['REQUEST_METHOD']); // Log invalid method
    echo "Invalid request method.";
}
?>

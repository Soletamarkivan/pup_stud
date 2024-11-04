<?php
// Database connection
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "gearchanix";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit();
}

// Function to update target_date in service_reminder and update or insert into maintenance_request
function updateTargetDateAndInsertOrUpdateRequest($reminder_ID, $new_target_date, $conn) {
    // Update the target_date in service_reminder
    $update_query = "UPDATE service_reminder SET target_date = ? WHERE reminder_ID = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("si", $new_target_date, $reminder_ID);

    if ($update_stmt->execute()) {
        // Check if the reminder_ID exists in maintenance_request
        $check_query = "SELECT * FROM maintenance_request WHERE reminder_ID = ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param("i", $reminder_ID);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            // Reminder ID exists, so we update the target_date in maintenance_request
            $update_maintenance_query = "UPDATE maintenance_request SET target_date = ? WHERE reminder_ID = ?";
            $update_maintenance_stmt = $conn->prepare($update_maintenance_query);
            $update_maintenance_stmt->bind_param("si", $new_target_date, $reminder_ID);

            if ($update_maintenance_stmt->execute()) {
                return ['success' => true, 'message' => 'Maintenance request updated successfully.'];
            } else {
                return ['success' => false, 'message' => 'Error updating maintenance request: ' . $update_maintenance_stmt->error];
            }
        } else {
            // Get the updated record from service_reminder to insert a new maintenance_request
            $select_query = "
                SELECT sr.reminder_ID, sr.vehicle_type, sr.plate_num, sr.service_task, sr.target_date, sr.parts, v.vehicle_name 
                FROM service_reminder sr
                JOIN vehicles v ON sr.plate_num = v.plate_num
                WHERE sr.reminder_ID = ?
                LIMIT 1
            ";
            $select_stmt = $conn->prepare($select_query);
            $select_stmt->bind_param("i", $reminder_ID);
            $select_stmt->execute();
            $result = $select_stmt->get_result();

            if ($result->num_rows > 0) {
                // Fetch the data
                $row = $result->fetch_assoc();
                $vehicle_name = $row['vehicle_name'];
                $vehicle_type = $row['vehicle_type'];
                $plate_num = $row['plate_num'];
                $service_task = $row['service_task'];
                $target_date = $row['target_date'];
                $parts = $row['parts']; // Assuming parts are already a comma-separated string

                // Ensure parts are separated by commas and remove any extra spaces
                $parts = implode(',', array_map('trim', preg_split('/[,;]/', $parts)));

                // Insert into maintenance_request
                $insert_query = "
                    INSERT INTO maintenance_request (reminder_ID, vehicle_name, vehicle_type, plate_num, service_task, target_date, parts)
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                ";
                $insert_stmt = $conn->prepare($insert_query);
                $insert_stmt->bind_param("issssss", $reminder_ID, $vehicle_name, $vehicle_type, $plate_num, $service_task, $target_date, $parts);

                if ($insert_stmt->execute()) {
                    return ['success' => true, 'message' => 'Maintenance request created successfully.'];
                } else {
                    return ['success' => false, 'message' => 'Error inserting maintenance request: ' . $insert_stmt->error];
                }
            } else {
                return ['success' => false, 'message' => 'No matching record found in service_reminder or vehicles.'];
            }
        }
    } else {
        return ['success' => false, 'message' => 'Error updating target_date: ' . $update_stmt->error];
    }
}

// Get the JSON data from the request body
$data = json_decode(file_get_contents('php://input'), true);
if (isset($data['reminder_ID']) && isset($data['target_date'])) {
    $reminder_ID = $data['reminder_ID'];
    $new_target_date = $data['target_date'];

    // Call the function to update and insert or update
    $response = updateTargetDateAndInsertOrUpdateRequest($reminder_ID, $new_target_date, $conn);
    echo json_encode($response);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
}

// Close the database connection
$conn->close();
?>

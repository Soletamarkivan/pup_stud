<?php
// Database connection
$host = '127.0.0.1';
$dbname = 'gearchanix';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit();
}

// Get the JSON data from the request
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['queue_num']) && isset($data['approval_type']) && isset($data['new_status'])) {
    $queue_num = $data['queue_num'];
    $new_status = $data['new_status'];

    // Update the approval_office status in maintenance_request table
    $update_query = "UPDATE maintenance_request SET approval_office = ? WHERE queue_num = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("si", $new_status, $queue_num);

    if ($stmt->execute()) {
        if ($new_status === 'Approved') {
            // Retrieve maintenance_request details for the approved record
            $select_query = "SELECT plate_num, parts, service_task, target_date FROM maintenance_request WHERE queue_num = ?";
            $select_stmt = $conn->prepare($select_query);
            $select_stmt->bind_param("i", $queue_num);
            $select_stmt->execute();
            $result = $select_stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $plate_num = $row['plate_num'];
                $parts = $row['parts'];
                $service_task = $row['service_task'];
                $target_date = $row['target_date'];

                // Retrieve vehicle_ID and vehicle_model from vehicles based on plate_num
                $vehicle_query = "SELECT vehicle_ID, vehicle_model FROM vehicles WHERE plate_num = ?";
                $vehicle_stmt = $conn->prepare($vehicle_query);
                $vehicle_stmt->bind_param("s", $plate_num);
                $vehicle_stmt->execute();
                $vehicle_result = $vehicle_stmt->get_result();

                if ($vehicle_result->num_rows > 0) {
                    $vehicle_row = $vehicle_result->fetch_assoc();
                    $vehicle_ID = $vehicle_row['vehicle_ID'];
                    $vehicle_model = $vehicle_row['vehicle_model'];
                    $acquisition_date = date('Y-m-d'); // Set acquisition_date as current date

                    // Insert a new record into the work_order table to get the workorder_ID
                    $insert_workorder_query = "INSERT INTO work_order (assigned_to, replaced_parts, activity, remarks) VALUES (NULL, NULL, NULL, NULL)";
                    if ($conn->query($insert_workorder_query)) {
                        $workorder_ID = $conn->insert_id;

                        // Insert a new record into the pms table
                        $insert_pms_query = "
                            INSERT INTO pms (vehicle_ID, vehicle_model, plate_num, queue_num, acquisition_date, target_date, workorder_ID, parts, service_task)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                        ";
                        $insert_pms_stmt = $conn->prepare($insert_pms_query);
                        $insert_pms_stmt->bind_param("issississ", $vehicle_ID, $vehicle_model, $plate_num, $queue_num, $acquisition_date, $target_date, $workorder_ID, $parts, $service_task);

                        if ($insert_pms_stmt->execute()) {
                            echo json_encode(['success' => true, 'message' => 'Approval updated and record inserted into PMS and Work Order successfully.']);
                        } else {
                            echo json_encode(['success' => false, 'message' => 'Error inserting record into PMS: ' . $insert_pms_stmt->error]);
                        }
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Error inserting record into Work Order: ' . $conn->error]);
                    }
                } else {
                    echo json_encode(['success' => false, 'message' => 'Vehicle details not found for the given plate number.']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'No matching record found in maintenance_request.']);
            }
        } else {
            echo json_encode(['success' => true, 'message' => 'Status updated successfully.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating status: ' . $stmt->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
}

// Close the database connection
$conn->close();
?>

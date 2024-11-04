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

// Get all vehicles and their latest odometer readings
$query = "
    SELECT vts.vehicle_type, vts.plate_num, vts.latest_odometer, vts.AUPD
    FROM vehicle_trip_summary AS vts
";

$result = $conn->query($query);

$service_reminders = [];

while ($vehicle = $result->fetch_assoc()) {
    $vehicle_type = $vehicle['vehicle_type'];
    $plate_num = $vehicle['plate_num'];
    $latest_odometer = $vehicle['latest_odometer'];
    $AUPD = $vehicle['AUPD'];

    // Get parts for the vehicle and calculate next due for each part
    $parts_query = "
        SELECT vp.part_name, vp.part_mtbf, vp.odometer_last_service 
        FROM vehicle_parts AS vp
        JOIN vehicles AS v ON vp.vehicle_ID = v.vehicle_ID
        WHERE v.vehicle_type = '$vehicle_type' AND v.plate_num = '$plate_num'
    ";

    $parts_result = $conn->query($parts_query);
    $parts_due = [];

    while ($part = $parts_result->fetch_assoc()) {
        $part_name = $part['part_name'];
        $part_mtbf = $part['part_mtbf'];
        $odometer_last_service = $part['odometer_last_service'];

        $next_due = $odometer_last_service + $part_mtbf;
        $threshold = $next_due - 1500;

        if ($latest_odometer >= $threshold) {
            $parts_due[] = $part_name;
        }
    }

    if (!empty($parts_due)) {
        $parts_string = implode(", ", $parts_due);

        $service_tasks_query = "
            SELECT st.task_ID, st.mtbf, ml.odometer_last_service, ml.task_name
            FROM service_tasks AS st
            JOIN maintenance_logs AS ml ON st.task_ID = ml.task_ID
            WHERE ml.vehicle_type = '$vehicle_type' AND ml.plate_num = '$plate_num'
        ";

        $service_tasks_result = $conn->query($service_tasks_query);

        while ($task = $service_tasks_result->fetch_assoc()) {
            $task_ID = $task['task_ID'];
            $mtbf = $task['mtbf'];
            $odometer_last_service = $task['odometer_last_service'];
            $task_name = $task['task_name'];

            $next_due = $odometer_last_service + $mtbf;
            $threshold = $next_due - 1000;

            if ($latest_odometer >= $threshold) {
                $meter_until_due = $next_due - $latest_odometer;
                $est_days = ($AUPD > 0) ? (int)($meter_until_due / $AUPD) : null;
                   // Set pms_date to null if est_days is null
                    if ($est_days === null) {
                        $pms_date = null;
                    } else {
                        // If est_days is negative, subtract it from the current date
                        $pms_date = date('Y-m-d', strtotime(($est_days < 0 ? "$est_days days" : "+$est_days days")));
                    }
                $km_used = $latest_odometer - $odometer_last_service;
                $status = ($km_used <= $mtbf) ? 'Due Soon' : 'Overdue';

                // Insert new reminder into the database if it doesn't exist
                $check_query = "
                    SELECT reminder_ID FROM service_reminder
                    WHERE vehicle_type = ? AND plate_num = ? AND task_ID = ?
                ";
                $check_stmt = $conn->prepare($check_query);
                $check_stmt->bind_param("ssi", $vehicle_type, $plate_num, $task_ID);
                $check_stmt->execute();
                $check_result = $check_stmt->get_result();
                $existing_reminder = $check_result->fetch_assoc();

                if (!$existing_reminder) {
                    $insert_query = "
                        INSERT INTO service_reminder (task_ID, vehicle_type, plate_num, service_task, status, next_due, Meter_until_due, est_days, pms_date, latest_odometer, parts)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ";
                    $stmt = $conn->prepare($insert_query);
                    $stmt->bind_param("issssiiisis", $task_ID, $vehicle_type, $plate_num, $task_name, $status, $next_due, $meter_until_due, $est_days, $pms_date, $latest_odometer, $parts_string);
                    $stmt->execute();
                    $reminder_ID = $stmt->insert_id;
                    $stmt->close();
                } else {
                    $reminder_ID = $existing_reminder['reminder_ID'];
                }

                $service_reminders[] = [
                    'reminder_ID' => $reminder_ID,
                    'vehicle_type' => $vehicle_type,
                    'plate_num' => $plate_num,
                    'service_task' => $task_name,
                    'status' => $status,
                    'next_due' => $next_due,
                    'meter_until_due' => $meter_until_due,
                    'est_days' => $est_days,
                    'pms_date' => $pms_date,
                    'latest_odometer' => $latest_odometer,
                    'parts' => $parts_string,
                ];

                $check_stmt->close();
            }
        }
    }
}

$conn->close();

header('Content-Type: application/json');
echo json_encode(array_values($service_reminders));
?>

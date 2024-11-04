<?php

require 'C:\xampp\htdocs\Gearchanix\vendor\autoload.php'; // Include Composer's autoloader

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Set header for JSON response
header('Content-Type: application/json');

// Database connection details
$host = '127.0.0.1';
$db = 'gearchanix';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]));
}

// Get the JSON input
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['reservation_ID']) && isset($data['reservation_status'])) {
    $reservation_ID = $data['reservation_ID'];
    $reservation_status = $data['reservation_status'];
    $custom_message = isset($data['custom_message']) ? $data['custom_message'] : ''; // Get custom message if provided

    // Prepare and bind for updating reservation status
    $stmt = $conn->prepare("UPDATE client_reservation SET reservation_status = ? WHERE reservation_ID = ?");
    $stmt->bind_param("si", $reservation_status, $reservation_ID);

    if ($stmt->execute()) {
        // If status is approved, insert into trip_ticket table and send email
        if ($reservation_status === 'Approved') {
            // Fetch vehicle_type, reservation_date, and email for the given reservation_ID
            $fetchStmt = $conn->prepare("SELECT vehicle_type, reservation_date, email FROM client_reservation WHERE reservation_ID = ?");
            $fetchStmt->bind_param("i", $reservation_ID);
            $fetchStmt->execute();
            $fetchStmt->bind_result($vehicle_type, $reservation_date, $email);
            $fetchStmt->fetch();
            $fetchStmt->close();

            // Insert into trip_ticket table
            $insertStmt = $conn->prepare("INSERT INTO trip_ticket (reservation_ID, vehicle_type, trip_ticket_date) VALUES (?, ?, ?)");
            $insertStmt->bind_param("iss", $reservation_ID, $vehicle_type, $reservation_date);

            if ($insertStmt->execute()) {
                // Sending email notification
                $mail = new PHPMailer(true);
                try {
                    // SMTP configuration
                    $mail->isSMTP(); // Set mailer to use SMTP
                    $mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers
                    $mail->SMTPAuth = true; // Enable SMTP authentication
                    $mail->Username = 'gearchanixmotorpool@gmail.com'; // Your SMTP username
                    $mail->Password = 'vcfi mhvv qulf qtmw'; // Your SMTP password or app password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
                    $mail->Port = 587; // TCP port to connect to

                    // Recipients
                    $mail->setFrom('gearchanixmotorpool@gmail.com', 'MotorPool Section'); // Sender's email and name
                    $mail->addAddress($email); // Add the recipient's email

                    // Content
                    $mail->isHTML(true); // Set email format to HTML
                    $mail->Subject = 'Reservation Approved';
                    $mail->Body = "
                        <div style='font-family: Arial, sans-serif; padding: 20px; background-color: #f9f9f9; border: 1px solid #ddd; border-radius: 10px; max-width: 600px; margin: auto;'>
                            <h2 style='color: #5cb85c;'>Reservation Approved</h2>
                            <p style='font-size: 16px; color: #333;'>Dear Customer,</p>
                            <p style='font-size: 16px; color: #333;'>Your reservation (ID: <strong>$reservation_ID</strong>) has been approved.</p>
                            <p style='font-size: 16px; color: #333;'>Vehicle Type: <strong>$vehicle_type</strong></p>
                            <p style='font-size: 16px; color: #333;'>Reservation Date: <strong>$reservation_date</strong></p>
                            
                            <p style='font-size: 16px; color: #333;'>Thank you for choosing our service. We look forward to serving you!</p>
                            
                            <p style='font-size: 16px; color: #333;'>If you have any questions, feel free to reply to this email or contact us at <strong>09*********</strong>.</p>
                            
                            <p style='font-size: 16px; color: #333;'>Best regards,<br><strong>PUP Motorpool</strong></p>
                            
                            <div style='margin-top: 20px; text-align: center;'>
                                <a href='#' style='padding: 10px 20px; background-color: #5cb85c; color: white; text-decoration: none; border-radius: 5px;'>View Reservation</a>
                            </div>
                        </div>
                    ";

                    // Send the email
                    $mail->send();

                    
                    echo json_encode(['success' => true, 'message' => 'Reservation status updated and trip ticket created successfully. Email sent.']);
                } catch (Exception $e) {
                    echo json_encode(['success' => false, 'message' => 'Reservation status updated but email could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
                }

                $insertStmt->close();
            } else {
                echo json_encode(['success' => false, 'message' => 'Error creating trip ticket: ' . $insertStmt->error]);
            }
        } elseif ($reservation_status === 'Rejected') {
            // If status is rejected, send email with the custom message
            $fetchStmt = $conn->prepare("SELECT email FROM client_reservation WHERE reservation_ID = ?");
            $fetchStmt->bind_param("i", $reservation_ID);
            $fetchStmt->execute();
            $fetchStmt->bind_result($email);
            $fetchStmt->fetch();
            $fetchStmt->close();

            // Sending email notification
            $mail = new PHPMailer(true);
            try {
                // SMTP configuration
                $mail->isSMTP(); // Set mailer to use SMTP
                $mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers
                $mail->SMTPAuth = true; // Enable SMTP authentication
                $mail->Username = 'gearchanixmotorpool@gmail.com'; // Your SMTP username
                $mail->Password = 'vcfi mhvv qulf qtmw'; // Your SMTP password or app password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
                $mail->Port = 587; // TCP port to connect to

                // Recipients
                $mail->setFrom('gearchanixmotorpool@gmail.com', 'MotorPool Section');
                $mail->addAddress($email); // Add the recipient's email

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Reservation Rejected';
                $mail->Body = "
                    <div style='font-family: Arial, sans-serif; padding: 20px; background-color: #f9f9f9; border: 1px solid #ddd; border-radius: 10px; max-width: 600px; margin: auto;'>
                        <h2 style='color: #d9534f;'>Reservation Rejected</h2>
                        <p style='font-size: 16px; color: #333;'>Dear Customer,</p>
                        <p style='font-size: 16px; color: #333;'>Your reservation (ID: <strong>$reservation_ID</strong>) has been rejected.</p>
                        <p style='font-size: 16px; color: #333;'>Reason: <strong>$custom_message</strong></p>
                        
                        <p style='font-size: 16px; color: #333;'>Thank you for your understanding.</p>
                        <p style='font-size: 16px; color: #333;'>For more details, message us at <strong>09*********</strong> or reply to this email.</p>
                        
                        <p style='font-size: 16px; color: #333;'>Best regards,<br><strong>PUP Motorpool</strong></p>
                        
                        <div style='margin-top: 20px; text-align: center;'>
                            <a href='#' style='padding: 10px 20px; background-color: #d9534f; color: white; text-decoration: none; border-radius: 5px;'>Contact Support</a>
                        </div>
                    </div>
                ";

                // Send the email
                $mail->send();

                
                echo json_encode(['success' => true, 'message' => 'Reservation status updated and rejection email sent.']);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => 'Reservation status updated but email could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
            }
        } else {
            echo json_encode(['success' => true, 'message' => 'Reservation status updated successfully.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating reservation status: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input data.']);
}

// Close connection
$conn->close();
?>

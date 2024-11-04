<?php
// Include Composer's autoloader for PHPMailer
require 'C:\xampp\htdocs\Gearchanix\vendor\autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Database connection details
$host = '127.0.0.1';
$dbname = 'gearchanix';
$username = 'root';
$password = '';

header('Content-Type: application/json'); // Set content type to JSON

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if it's a DELETE request
    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        parse_str(file_get_contents("php://input"), $_DELETE);
        $userId = $_DELETE['id'] ?? null;
        if ($userId) {
            // Prepare and execute the delete query
            $stmt = $pdo->prepare("DELETE FROM users WHERE user_ID = ?");
            $stmt->execute([$userId]);

            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'User ID not provided']);
        }
        exit; // Stop script execution
    }

    // Check if it's a POST request for editing
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $userId = $_POST['user_ID'] ?? null;
        $role = $_POST['user_role'] ?? null;
        $name = $_POST['name'] ?? null;
        $email = $_POST['user_email'] ?? null;
        $contact = $_POST['contact_num'] ?? null;

        if ($userId) {
            // Fetch the current role from the database
            $stmt = $pdo->prepare("SELECT user_role FROM users WHERE user_ID = ?");
            $stmt->execute([$userId]);
            $currentUser = $stmt->fetch(PDO::FETCH_ASSOC);
            $currentRole = $currentUser['user_role'];

            // Prepare and execute the update query
            $stmt = $pdo->prepare("UPDATE users SET user_role = ?, user_email = ?, contact_num = ? WHERE user_ID = ?");
            $stmt->execute([$role, $email, $contact, $userId]);

            // Only send email if the role has changed
            if ($currentRole !== $role) {
                // Sending email notification after role change
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
                    $mail->setFrom('gearchanixmotorpool@gmail.com', 'PUP Motor Pool Admins'); // Sender's email and name
                    $mail->addAddress($email); // Add the user's email

                    // Content
                    $mail->isHTML(true); // Set email format to HTML
                    $mail->Subject = 'Role Confirmation';
                    $mail->Body = "<p>Hello $name,</p>
                                   <p>Your role as a <strong>$role</strong> has been confirmed. You can now log in to the Gearchanix System.</p>
                                   <p><a href='http://your-login-url.com' style='padding: 10px 20px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 5px;'>Click here to log in</a></p>
                                   <p>Best Regards,<br>PUP Motor Pool Admins</p>";

                    // Send the email
                    $mail->send();
                } catch (Exception $e) {
                    echo json_encode(['success' => true, 'message' => 'Role updated but email could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
                    exit; // Stop script execution
                }

                echo json_encode(['success' => true, 'message' => 'User role updated successfully. Email notification sent.']);
            } else {
                echo json_encode(['success' => true, 'message' => 'User details updated successfully.']);
            }
            exit; // Stop script execution
        }
    }

    // Check if an ID is provided for editing
    $user_ID = $_GET['id'] ?? null;
    if ($user_ID) {
        // Prepare and execute the query to fetch user data
        $stmt = $pdo->prepare("SELECT user_ID, CONCAT(first_name, ' ', middle_name, ' ', last_name) AS name, user_role, user_email AS email, contact_num AS contact FROM users WHERE user_ID = ?");
        $stmt->execute([$user_ID]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($user);
        exit; // Stop script execution
    }

    // Prepare and execute the query to fetch all users
    $stmt = $pdo->prepare("SELECT user_ID, CONCAT(first_name, ' ', middle_name, ' ', last_name) AS name, user_role, user_email AS email, contact_num AS contact FROM users");
    $stmt->execute();

    // Fetch the results as an associative array
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the results as JSON
    echo json_encode($users);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>

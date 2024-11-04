<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


$host = '127.0.0.1';
$dbname = 'gearchanix';
$username = 'root'; 
$password = ''; 

// Create a connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start session to store success flag
// Start session to store success flag
session_start();

// Prepare response array
$response = ['success' => false, 'error' => ''];

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize inputs
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $middle_name = $conn->real_escape_string($_POST['middle_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $contact_number = $conn->real_escape_string($_POST['contact_number']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($conn->real_escape_string($_POST['password']), PASSWORD_DEFAULT); // Hash password

    // Insert user data into the `users` table
    $sql_user = "INSERT INTO users (first_name, middle_name, last_name, contact_num, user_email, user_role, username, user_password)
                 VALUES (?, ?, ?, ?, ?, 'SET ROLE', ?, ?)";

    // Prepare the statement for the `users` table
    if ($stmt_user = $conn->prepare($sql_user)) {
        // Bind the parameters
        $stmt_user->bind_param("sssssss", $first_name, $middle_name, $last_name, $contact_number, $email, $email, $password);

        // Execute the query for the `users` table
        if ($stmt_user->execute()) {
            // Get the newly inserted user ID
            $user_ID = $conn->insert_id;

            // Insert into the `roles` table with default role_status 'Pending'
            $sql_role = "INSERT INTO roles (user_ID, role_status) VALUES (?, 'Pending')";

            if ($stmt_role = $conn->prepare($sql_role)) {
                $stmt_role->bind_param("i", $user_ID);

                // Execute the query for the `roles` table
                if ($stmt_role->execute()) {
                    // Set success flag in session
                    $_SESSION['registration_success'] = true;
                    $response['success'] = true; // Update response to indicate success
                } else {
                    $response['error'] = "Error inserting into roles table: " . $stmt_role->error;
                }
                $stmt_role->close();
            } else {
                $response['error'] = "Error preparing roles statement: " . $conn->error;
            }
        } else {
            $response['error'] = "Error inserting into users table: " . $stmt_user->error;
        }
        $stmt_user->close();
    } else {
        $response['error'] = "Error preparing users statement: " . $conn->error;
    }
}

// Close the connection
$conn->close();

// Return the JSON response
header('Content-Type: application/json');
echo json_encode($response);

?>

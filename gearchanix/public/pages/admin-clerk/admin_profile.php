<?php
session_start();

// Database connection
$host = '127.0.0.1';
$dbname = 'gearchanix';
$username = 'root';
$password = '';

$userID = $_SESSION['user_ID'];

$stmt = $conn->prepare("SELECT * FROM users WHERE user_ID = :user_ID");
$stmt->bindParam(':user_ID', $userID);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    // Display user profile information
    echo "Username: " . htmlspecialchars($user['username']);
    echo "Email: " . htmlspecialchars($user['user_email']);
    // Add more fields as necessary
} else {
    echo "User not found.";
}


try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Retrieve user information based on user_ID from session
    $userId = $_SESSION['user_ID']; // Ensure you have the user's ID stored in the session
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_ID = :user_ID LIMIT 1");
    $stmt->bindParam(':user_ID', $userId);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get form data
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $user_email = $_POST['user_email'];
        $contact_num = $_POST['contact_num'];
        $user_idpic = NULL;

        /* Handle profile photo upload
        if (isset($_FILES['user_idpic']) && $_FILES['user_idpic']['size'] > 0) {
            $targetDir = "admin-clerk-profile/"; // Make sure this directory exists
            $targetFile = $targetDir . basename($_FILES['user_idpic']['name']);
            
            // Check if the file is an actual image
            $check = getimagesize($_FILES['user_idpic']['tmp_name']);
            if ($check !== false) {
                // Move the file to the target directory
                if (move_uploaded_file($_FILES['user_idpic']['tmp_name'], $targetFile)) {
                    $user_idpic = $targetFile; // Store the path in the variable
                } else {
                    echo "Error: Unable to upload the file.";
                }
            } else {
                echo "Error: File is not an image.";
            }
        }*/

        if (isset($_FILES['user_idpic'])) {
            $fileTmpPath = $_FILES['user_idpic']['tmp_name'];
            $fileName = $_FILES['user_idpic']['name'];
            $fileSize = $_FILES['user_idpic']['size'];
            $fileType = $_FILES['user_idpic']['type'];
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            
            // Check if the file is of an allowed type
            if (in_array($fileType, $allowedTypes) && $fileSize < 2000000) { // 2MB limit
                $destination = "admin-clerk-profile/" . $fileName; // Ensure this directory exists
                move_uploaded_file($fileTmpPath, $destination);
                
                // Update user profile with image path
                $stmt = $conn->prepare("UPDATE users SET user_idpic = :user_idpic WHERE user_ID = :user_ID");
                $stmt->bindParam(':user_idpic', $fileName);
                $stmt->bindParam(':user_ID', $userID);
                $stmt->execute();
            } else {
                echo "Invalid file type or size.";
            }
        }
        


        // Update user information in the database
        $sql = "UPDATE users SET first_name = :first_name, last_name = :last_name, user_email = :user_email, contact_num = :contact_num, user_idpic = :user_idpic WHERE user_ID = :user_ID";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':user_email', $user_email);
        $stmt->bindParam(':contact_num', $contact_num);
        $stmt->bindParam(':user_ID', $user_Id);
        

        if ($stmt->execute()) {
            echo "Profile updated successfully!";
            // Refresh user data
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            echo "Error: Unable to update the profile.";
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$conn = null;
?>
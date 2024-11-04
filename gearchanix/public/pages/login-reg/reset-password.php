<?php
require 'C:\xampp\htdocs\Gearchanix\vendor\autoload.php'; // Include Composer's autoloader
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Database connection
$host = '127.0.0.1';
$db = 'gearchanix';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $token = $_POST['token'];
        $email = $_POST['email'];
        $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Verify the token and email
        $stmt = $pdo->prepare("SELECT * FROM password_resets WHERE user_email = ? AND token = ?");
        $stmt->execute([$email, $token]);
        $resetRequest = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resetRequest) {
            // Update the user's password in the users table
            $stmt = $pdo->prepare("UPDATE users SET user_password = ? WHERE user_email = ?");
            $stmt->execute([$newPassword, $email]);

            // Delete the reset request from the password_resets table
            $stmt = $pdo->prepare("DELETE FROM password_resets WHERE user_email = ?");
            $stmt->execute([$email]);

            // Send a notification email about the password change
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'gearchanixmotorpool@gmail.com'; 
            $mail->Password = 'vcfi mhvv qulf qtmw'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('gearchanixmotorpool@gmail.com', 'Gearchanix Team');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Password Changed';
            $mail->Body = "Hello,<br><br>Your password has been successfully changed! If you didn't do this, please contact our support services.<br><br>Best Regards,<br>Gearchanix Team.";

            $mail->send();
            echo "Password has been reset and a confirmation email has been sent.";
            echo "<script>
                    setTimeout(function() {
                        window.location.href = '/gearchanix/gearchanix-main/gearchanix/public/pages/hompage/homepage.html'; // Redirect to homepage.html
                    }, 3000); // Redirect after 3 seconds
                  </script>";   
        } else {
            echo "Invalid token or email address.";
        }
    } else {
        // Display reset password form
        if (isset($_GET['token']) && isset($_GET['email'])) {
            $token = $_GET['token'];
            $email = $_GET['email'];
            ?>
           <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>

    <link rel="stylesheet" href="/Gearchanix/GEARCHANIX-MAIN/gearchanix/src/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.0/css/all.css">

    <style>
        /* Global Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff; /* White background color */
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #800000; /* Maroon text color */
            margin-bottom: 20px;
        }

        /* Form Styles */
        form {
            max-width: 400px; /* Set a max width for the form */
            margin: auto; /* Center the form */
            background: #ffeaa7; /* Light yellow background for the form */
            padding: 20px; /* Inner padding */
            border-radius: 8px; /* Rounded corners */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }

        /* Label Styles */
        label {
            display: block; /* Stack the label */
            margin-bottom: 10px; /* Space between label and input */
            font-weight: bold; /* Bold text for labels */
            color: #800000; /* Maroon text color */
        }

        /* Input Styles */
        input[type="password"], input[type="hidden"] {
            width: 100%; /* Full width for responsive design */
            padding: 10px 40px 10px 10px; /* Inner padding and right padding for the eye icon */
            margin-bottom: 15px; /* Space below input */
            border: 1px solid #ced4da; /* Border color */
            border-radius: 5px; /* Rounded corners */
            font-size: 16px; /* Font size for input */
        }

        /* Button Styles */
        button {
            background-color: #800000; /* Maroon button color */
            color: white; /* White text color */
            border: none; /* No border */
            border-radius: 5px; /* Rounded corners */
            padding: 10px; /* Inner padding */
            width: 100%; /* Full width button */
            font-size: 16px; /* Font size for button */
            cursor: pointer; /* Pointer cursor on hover */
            transition: background-color 0.3s; /* Smooth background transition */
        }

        button:hover {
            background-color: #5e0000; /* Darker maroon on hover */
        }

        /* Responsive Styles */
        @media (max-width: 576px) {
            body {
                padding: 10px; /* Reduced padding for smaller screens */
            }

            h2 {
                font-size: 1.5rem; /* Smaller heading size */
            }

            form {
                padding: 15px; /* Reduced padding for smaller screens */
            }

            input[type="password"],
            button {
                font-size: 14px; /* Smaller font size for inputs/buttons */
            }
        }

        /* Additional styles for the password input and eye icon */
        .password-container {
            position: relative;
        }

        .eye-icon {
            position: absolute;
            right: 15px;
            top: 50%; /* Center vertically */
            transform: translateY(50%); /* Adjust for centering */
            cursor: pointer;
            color: #800000; /* Optional: change icon color */
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Reset Password</h2>
        <form action="reset-password.php" method="POST" onsubmit="return validatePassword()">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
            
            <div class="mb-3 password-container">
                <label for="password">New Password:</label>
                <input type="password" name="password" id="password" required class="form-control">
                <i class="fas fa-eye eye-icon" id="togglePassword" onclick="togglePasswordVisibility()"></i>
            </div>
            
            <button type="submit" class="btn btn-primary">Reset Password</button>
        </form>
    </div>

    <script src="/Gearchanix/GEARCHANIX-main/gearchanix/src/assets/js/validatepassword.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


            <?php
        } else {
            echo "Invalid token or email address.";
        }
    }
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

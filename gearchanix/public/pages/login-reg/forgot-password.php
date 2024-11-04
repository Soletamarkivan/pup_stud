
<?php
require 'C:\xampp\htdocs\Gearchanix\vendor\autoload.php'; 
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
        $email = $_POST['email'];

        // Check if email exists in users table
        $stmt = $pdo->prepare("SELECT * FROM users WHERE user_email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Generate a token and save it in password_resets table
            $token = bin2hex(random_bytes(50));
            $stmt = $pdo->prepare("INSERT INTO password_resets (user_email, token) VALUES (?, ?)");
            $stmt->execute([$email, $token]);

            // Create reset link
            $resetLink = "http://localhost/Gearchanix/Gearchanix-main/gearchanix/public/pages/login-reg/reset-password.php?token=$token&email=$email";

            // Send email with reset link
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
            $mail->Subject = 'Password Reset Request';
            $mail->Body = "Hello,<br><br>To reset your password, please click the link below:<br><a href='$resetLink'>Reset Password</a><br><br>Best Regards,<br>Your Team.";

            $mail->send();
            echo "Reset link has been sent to your email.";
        } else {
            echo "No account found with that email address.";
        }
    }
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

?>


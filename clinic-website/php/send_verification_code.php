<?php
session_start();
require 'Exception.php';
require 'PHPMailer.php';
require 'SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_SESSION['user_email'])) {
    // Retrieve the email from session and trim any extra spaces
    $email = trim($_SESSION['user_email']);

    // Debug: Output the email to ensure correctness
    echo "Retrieved email: " . htmlspecialchars($email) . "<br>";
    echo "Session ID in send_verification_code: " . session_id() . "<br>";  // Log session ID to track consistency

   if (isset($_SESSION['user_email'])) {
  // Check if email is valid
  if (!filter_var($_SESSION['user_email'], FILTER_VALIDATE_EMAIL)) {
    // Redirect to login with error message
    header("Location: login.html?error=invalid_email");
    exit();
  }
    }

    // Generate random 6-digit code
    $verification_code = rand(100000, 999999);

    // Save the verification code in the session for later verification
    $_SESSION['verification_code'] = $verification_code;

    // Send verification code via email using PHPMailer
    $mail = new PHPMailer(true);

    try {
        // SMTP server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'clinicbasem@gmail.com'; // Replace with your email
        $mail->Password = 'adni fuef qlcm pizd';  // Replace with your App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Email recipients and content
        $mail->setFrom('clinicbasem@gmail.com', 'Clinic Name');
        $mail->addAddress($email);  // Use the validated email from session

        $mail->isHTML(true);
        $mail->Subject = 'Your 6-Digit Verification Code';
        $mail->Body    = "Your verification code is: <strong>$verification_code</strong>";

        $mail->send();
        echo 'Verification code has been sent to your email.';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    echo "No email address found in session. Please log in again.";
}

 	if ($mail->send()) {
            echo "Code sent successfully! <a href='../2fa.html'>Click here to continue</a>";
        } else {
            echo "Error: " . $stmt->error;
        }
?>

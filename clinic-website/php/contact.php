<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'Exception.php';
require 'PHPMailer.php';
require 'SMTP.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    if (!empty($name) && !empty($email) && !empty($message)) {
        $mail = new PHPMailer(true);

        try {
            // Enable debugging (use 0 for production)
            $mail->SMTPDebug = 0;  // Change to 3 for more details
            $mail->Debugoutput = 'html';

            // SMTP settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'clinicbasem@gmail.com';
            $mail->Password   = 'adni fuef qlcm pizd';  // Regenerate and update
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Recipients
            $mail->setFrom('clinicbasem@gmail.com', 'Clinic Name');
            $mail->addAddress('clinicbasem@gmail.com'); // Send to yourself for testing

            // Email content
            $mail->isHTML(true);
            $mail->Subject = 'New Contact Form Submission';
            $mail->Body    = "<h2>New message from the contact form:</h2>
                              <p><strong>Name:</strong> $name</p>
                              <p><strong>Email:</strong> $email</p>
                              <p><strong>Message:</strong><br>$message</p>";
            $mail->AltBody = "New message from the contact form:\nName: $name\nEmail: $email\nMessage: $message";

            // Send email
            if ($mail->send()) {
                echo 'Message has been sent successfully!';
            } else {
                echo 'Failed to send the message. Please try again later.';
            }

        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo 'Please fill in all fields.';
    }
} else {
    echo 'Invalid request method.';
}

?>
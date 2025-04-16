<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer classes
require 'Exception.php';
require 'PHPMailer.php';
require 'SMTP.php';

/**
 * Sends an email notification to the patient.
 *
 * @param string $email The patient's email address.
 * @param string $first_name The patient's first name.
 * @param string $last_name The patient's last name.
 * @param string $patient_status The updated patient status.
 * @param string $remarks Additional remarks or comments.
 * @return void
 */
function sendNotification($email, $first_name, $last_name, $patient_status, $remarks = '') {
    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // SMTP server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'clinicbasem@gmail.com'; // Replace with your email
        $mail->Password = 'adni fuef qlcm pizd';   // Replace with your App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Email sender and recipient
        $mail->setFrom('clinicbasem@gmail.com', 'Clinic Name');
        $mail->addAddress($email); // Patient's email address

        // Email content
        $mail->isHTML(true); // Enable HTML formatting
        $mail->Subject = 'Your Patient Status Has Been Updated';
        $mail->Body = "
            <h1>Dear $first_name $last_name,</h1>
            <p>We wanted to inform you that your patient status has been updated.</p>
            <p><strong>Updated Status:</strong> $patient_status</p>
            " . ($remarks ? "<p><strong>Remarks:</strong> $remarks</p>" : "") . "
            <p>If you have any questions, please do not hesitate to contact us.</p>
            <p>Best regards,<br>Clinic Name</p>
        ";

        $mail->AltBody = "Dear $first_name $last_name,\n\nYour patient status has been updated.\n\nUpdated Status: $patient_status\n" . 
                         ($remarks ? "Remarks: $remarks\n" : "") . 
                         "If you have any questions, please do not hesitate to contact us.\n\nBest regards,\nClinic Name";

        // Send email
        $mail->send();
        echo 'Notification email sent successfully.';
    } catch (Exception $e) {
        echo "Notification email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

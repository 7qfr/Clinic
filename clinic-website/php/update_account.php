<?php
session_start();
require 'db_connection.php';
require 'Exception.php';
require 'PHPMailer.php';
require 'SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Validate and sanitize inputs
$id = isset($_POST['id']) ? intval($_POST['id']) : null;
$type = isset($_POST['type']) ? $_POST['type'] : null;
$first_name = isset($_POST['first_name']) ? trim($_POST['first_name']) : null;
$last_name = isset($_POST['last_name']) ? trim($_POST['last_name']) : null;
$email = isset($_POST['email']) ? trim($_POST['email']) : null;
$health_status = isset($_POST['health_status']) ? trim($_POST['health_status']) : null;
$allergies = isset($_POST['allergies']) ? trim($_POST['allergies']) : null;
$medical_history = isset($_POST['medical_history']) ? trim($_POST['medical_history']) : null;

// Determine the table based on user type
$table = ($type === 'admin') ? 'admin' : 'users';

if (!$id || !$first_name || !$last_name || !$email) {
    echo "Invalid input. Please ensure all fields are filled.";
    exit();
}

// Build the SQL query
$query = "UPDATE $table SET first_name = ?, last_name = ?, email = ?";
$params = ["sss", $first_name, $last_name, $email];

if ($table === 'users') {
    $query .= ", health_status = ?, allergies = ?, medical_history = ?";
    $params[0] .= "sss";
    $params[] = $health_status;
    $params[] = $allergies;
    $params[] = $medical_history;
}

$query .= " WHERE id = ?";
$params[0] .= "i";
$params[] = $id;

// Prepare and execute the statement
$stmt = $conn->prepare($query);
$stmt->bind_param(...$params);

if ($stmt->execute()) {
    // Check which fields were updated for email notification
    $updatedFields = [];
    if ($health_status) $updatedFields[] = "Health Status: \"$health_status\"";
    if ($allergies) $updatedFields[] = "Allergies: \"$allergies\"";
    if ($medical_history) $updatedFields[] = "Medical History: \"$medical_history\"";

    // Send email notification if there are updates
    if (!empty($updatedFields)) {
        $subject = "Update to Your Account Details";
        $message = "
            Hello $first_name $last_name,

            The following details of your account have been updated:

            " . implode("\n", $updatedFields) . "

            Please log in to your account for more details.

            Regards,
            Clinic Name
        ";

        try {
            $mail = new PHPMailer(true);

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
            $mail->addAddress($email);  // Use the validated email
            $mail->Subject = $subject;
            $mail->Body = $message;

            // Send the email
            $mail->send();
        } catch (Exception $e) {
            error_log("Failed to send email: " . $mail->ErrorInfo);
        }
    }
    header("Location: view_accounts.php");
    exit();
} else {
    echo "Error updating account: " . $stmt->error;
}
?>

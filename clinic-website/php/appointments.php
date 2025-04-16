<?php
session_start();
require 'db_connection.php'; 

// Include PHPMailer library files
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'Exception.php';
require 'PHPMailer.php';
require 'SMTP.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $full_name = $_POST['full_name'];
    $preferred_date = $_POST['preferred_date'];
    $preferred_time = $_POST['preferred_time'];
    $doctor = $_POST['doctor'];
    $purpose_of_visit = $_POST['purpose_of_visit'];

    // Validate inputs 
    if (empty($full_name) || empty($preferred_date) || empty($preferred_time) || empty($doctor) || empty($purpose_of_visit)) {
        echo "All fields are required!";
        exit();
    }

    // Prepare SQL query to insert appointment data
    $stmt = $conn->prepare("INSERT INTO appointments (Full_Name, Preferred_Date, Preferred_Time, Select_Doctor, Purpose_of_Visit) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $full_name, $preferred_date, $preferred_time, $doctor, $purpose_of_visit);

    // Execute the query
    if ($stmt->execute()) {
        // Email functionality using PHPMailer
        $mail = new PHPMailer(true);

        try {
            // SMTP server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';              
            $mail->SMTPAuth   = true;                          
            $mail->Username   = 'clinicbasem@gmail.com';       // Your email
            $mail->Password   = 'adni fuef qlcm pizd';         // Your App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;// Enable TLS encryption
            $mail->Port       = 587;                          

            // Recipients
            $mail->setFrom('clinicbasem@gmail.com', 'Clinic Booking System');
            $mail->addAddress('clinicbasem@gmail.com');        // Clinic's email

            // Email content
            $mail->isHTML(true);                                
            $mail->Subject = 'New Appointment Booking';
            $mail->Body    = "<h2>New Appointment Details:</h2>
                              <p><strong>Full Name:</strong> $full_name</p>
                              <p><strong>Preferred Date:</strong> $preferred_date</p>
                              <p><strong>Preferred Time:</strong> $preferred_time</p>
                              <p><strong>Doctor:</strong> $doctor</p>
                              <p><strong>Purpose of Visit:</strong> $purpose_of_visit</p>";
            $mail->AltBody = "New Appointment Details:\n
                              Full Name: $full_name\n
                              Preferred Date: $preferred_date\n
                              Preferred Time: $preferred_time\n
                              Doctor: $doctor\n
                              Purpose of Visit: $purpose_of_visit";

            // Send the email
            $mail->send();
            echo "Appointment booked successfully! An email notification has been sent to the clinic. <a href='../index.html'>Back to Home</a>";
        } catch (Exception $e) {
            echo "Appointment booked successfully, but the email could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request!";
}
?>


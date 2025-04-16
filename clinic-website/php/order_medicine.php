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
    $email = $_POST['email'];
    $address = $_POST['address'];
    $medicine_name = $_POST['medicine_name'];
    $dosage = $_POST['dosage'];
    $amount = $_POST['amount'];
    $notes = $_POST['notes'];

    // Validate required fields
    if (empty($full_name) || empty($email) || empty($address) || empty($medicine_name) || empty($dosage) || empty($amount)) {
        echo "All fields except notes are required!";
        exit();
    }

    // Prepare SQL query to insert order data into 'medicine_orders' table
    $stmt = $conn->prepare("INSERT INTO medicine_orders (full_name, email, address, medicine_name, dosage, amount, notes) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssis", $full_name, $email, $address, $medicine_name, $dosage, $amount, $notes);

    // Execute the query
    if ($stmt->execute()) {
        // Send email notification to the clinic
        $mail = new PHPMailer(true);

        try {
            // SMTP settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'clinicbasem@gmail.com'; // Replace with your email
            $mail->Password   = 'adni fuef qlcm pizd';   // Replace with your App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Email recipients and content
            $mail->setFrom('clinicbasem@gmail.com', 'Clinic Name');
            $mail->addAddress('clinicbasem@gmail.com'); // Email recipient

            // Email subject and body
            $mail->Subject = 'New Medicine Order Received';
            $mail->isHTML(true); // HTML format
            $mail->Body    = "
                <h2>New Medicine Order</h2>
                <p><strong>Full Name:</strong> $full_name</p>
                <p><strong>Email:</strong> $email</p>
                <p><strong>Address:</strong> $address</p>
                <p><strong>Medicine Name:</strong> $medicine_name</p>
                <p><strong>Dosage:</strong> $dosage</p>
                <p><strong>Amount:</strong> $amount</p>
                <p><strong>Notes:</strong> $notes</p>
            ";
            $mail->AltBody = "New Medicine Order\nFull Name: $full_name\nEmail: $email\nAddress: $address\nMedicine Name: $medicine_name\nDosage: $dosage\nAmount: $amount\nNotes: $notes";

            // Send email
            if ($mail->send()) {
                echo "Medicine order submitted successfully! A confirmation has been sent to the clinic. <a href='../index.html'>Back to Home</a>";
            } else {
                echo "Order submitted, but failed to send email notification. Please contact the clinic directly.";
            }

        } catch (Exception $e) {
            echo "Error: Order submitted, but email could not be sent. Mailer Error: {$mail->ErrorInfo}";
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


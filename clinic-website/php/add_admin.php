<?php
session_start();
require 'db_connection.php';



// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['admin_first_name'];
    $last_name = $_POST['admin_last_name'];
    $email = $_POST['admin_email'];
    $password = password_hash($_POST['admin_password'], PASSWORD_DEFAULT); // Hash the password

    // Insert the admin into the 'admins' table
    $stmt = $conn->prepare("INSERT INTO admin (first_name, last_name, email, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $first_name, $last_name, $email, $password);

    if ($stmt->execute()) {
        echo "Admin added successfully! <a href='../Admin.html'>Back to Dashboard</a>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<?php
session_start();
require 'db_connection.php';

if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit();
    }

    $_SESSION['user_email'] = $email;

    // Check if the user is an admin
    $stmt = $conn->prepare("SELECT id, password FROM admin WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($admin_id, $admin_password);
        $stmt->fetch();

        if (password_verify($password, $admin_password)) {
            $_SESSION['admin_id'] = $admin_id;
            $_SESSION['is_admin'] = true;
            header("Location: ../2fa.html");
            exit();
        } else {
            echo "Invalid email or password.";
            exit();
        }
    } else {
        // Check if the user is a regular user
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id, $user_password);
            $stmt->fetch();

            if (password_verify($password, $user_password)) {
                $_SESSION['user_id'] = $user_id;
                $_SESSION['is_admin'] = false;
                header("Location: send_verification_code.php");
                exit();
            } else {
                echo "Invalid email or password.";
                exit();
            }
        } else {
            echo "User not found.";
            exit();
        }
    }
} else {
    echo "Email or password not provided.";
    exit();
}
?>

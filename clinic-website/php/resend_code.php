<?php
session_start();

// Resend the verification code by calling send_verification_code.php again
if (isset($_SESSION['user_email'])) {
    header("Location: send_verification_code.php");
    exit();
} else {
    echo "No email found in session. Please log in again.";
}

?>

<?php
session_start();

if (isset($_POST['verification_code'])) {
    $entered_code = $_POST['verification_code'];

    // Debugging: Output the entered code and session code
    echo "Entered code: " . htmlspecialchars($entered_code) . "<br>";
    echo "Stored code: " . htmlspecialchars($_SESSION['verification_code']) . "<br>";

    // Check if the entered code matches the one stored in session
    if ($entered_code == $_SESSION['verification_code']) {
        // Code is correct
        echo 'Verification successful. Redirecting...';
        // Redirect to the user dashboard or homepage
        header("Location: ../index.html");
        exit();
    } else {
        // Code is incorrect
        echo 'Invalid verification code. Please try again.';
    }
} else {
    echo 'No code entered.';
}


?>


<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to access this page.";
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data
$stmt = $conn->prepare("SELECT first_name, last_name, email, allergies, medical_history, profile_picture, health_status FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($first_name, $last_name, $email, $allergies, $medical_history, $profile_picture, $health_status);
$stmt->fetch();
$stmt->close();

// Use default profile picture if none exists
$profile_picture = $profile_picture ?: '../uploads/default_profile.png';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Information</title>
    <link rel="stylesheet" href="../css/account.css">
</head>
<body>
    <div class="container">
        <h1>Personal Information</h1>
        <form action="update_personal_information.php" method="POST" enctype="multipart/form-data">

            <!-- First Name -->
            <div class="form-group">
                <label for="first-name">First Name:</label>
                <input type="text" id="first-name" name="first_name" value="<?= htmlspecialchars($first_name); ?>" required>
            </div>

            <!-- Last Name -->
            <div class="form-group">
                <label for="last-name">Last Name:</label>
                <input type="text" id="last-name" name="last_name" value="<?= htmlspecialchars($last_name); ?>" required>
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($email); ?>" required>
            </div>

            <!-- Health Status -->
            <div class="form-group">
                <label for="health-status">Health Status:</label>
                <textarea id="health-status" name="health_status" readonly><?= htmlspecialchars($health_status); ?></textarea>
            </div>

            <!-- Allergies -->
            <div class="form-group">
                <label for="allergies">Allergies:</label>
                <textarea id="allergies" name="allergies"><?= htmlspecialchars($allergies); ?></textarea>
            </div>

            <!-- Medical History -->
            <div class="form-group">
                <label for="medical-history">Medical History:</label>
                <textarea id="medical-history" name="medical_history"><?= htmlspecialchars($medical_history); ?></textarea>
            </div>

            <!-- Submit Button -->
            <div class="form-group">
                <button type="submit">Save</button>
            </div>
        </form>
    </div>
</body>
</html>

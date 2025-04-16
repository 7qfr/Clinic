<?php
session_start();
require 'db_connection.php';
require 'email_notif.php';

if (!isset($_POST['id'], $_POST['type'])) {
    echo "Invalid access.";
    exit();
}

$id = $_POST['id'];
$type = $_POST['type'];
$table = ($type === 'admin') ? 'admin' : 'users';

// Fetch account details
$stmt = $conn->prepare("SELECT first_name, last_name, email, profile_picture, allergies, medical_history, health_status FROM $table WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$result) {
    echo "Account not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Account</title>
    <link rel="stylesheet" href="../css/account.css">
</head>
<body>
    <div class="container">
        <h2>Edit Account</h2>
        <form action="update_account.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $id; ?>">
            <input type="hidden" name="type" value="<?= $type; ?>">

            <!-- First Name -->
            <div class="form-group">
                <label for="first-name">First Name:</label>
                <input type="text" id="first-name" name="first_name" value="<?= htmlspecialchars($result['first_name']); ?>" required>
            </div>

            <!-- Last Name -->
            <div class="form-group">
                <label for="last-name">Last Name:</label>
                <input type="text" id="last-name" name="last_name" value="<?= htmlspecialchars($result['last_name']); ?>" required>
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($result['email']); ?>" required>
            </div>

            
                <!-- Allergies -->
                <div class="form-group">
                    <label for="allergies">Allergies:</label>
                    <textarea id="allergies" name="allergies"><?= htmlspecialchars($result['allergies']); ?></textarea>
                </div>

                <!-- Medical History -->
                <div class="form-group">
                    <label for="medical-history">Medical History:</label>
                    <textarea id="medical-history" name="medical_history"><?= htmlspecialchars($result['medical_history']); ?></textarea>
                </div>

                <!-- Health Status -->
                <div class="form-group">
                    <label for="health-status">Health Status:</label>
                    <textarea id="health-status" name="health_status"><?= htmlspecialchars($result['health_status']); ?></textarea>
                </div>
            

            <!-- Submit Button -->
            <button type="submit">Save Changes</button>
        </form>
    </div>
</body>
</html>

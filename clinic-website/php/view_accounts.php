<?php
session_start();
require 'db_connection.php';

// Ensure only admins can access
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    die("Access denied.");
}

// Fetch users and admins
$query_users = "SELECT id, first_name, last_name, email FROM users";
$query_admins = "SELECT id, first_name, last_name, email FROM admin";
$result_users = $conn->query($query_users);
$result_admins = $conn->query($query_admins);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Accounts</title>
    <link rel="stylesheet" href="../css/view_accounts.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Account Management</h1>
        </header>
        
        <section class="account-section">
            <h2>User Accounts</h2>
            <div class="account-table">
                <?php while ($user = $result_users->fetch_assoc()): ?>
                    <div class="account-row">
                        <div class="account-info">
                            <p><strong>Name:</strong> <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></p>
                            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']); ?></p>
                        </div>
                        <div class="account-actions">
                            <form action="edit_account.php" method="POST">
                                <input type="hidden" name="id" value="<?= $user['id']; ?>">
                                <input type="hidden" name="type" value="user">
                                <button class="edit-button" type="submit">Edit</button>
                            </form>
                            <form action="delete_account.php" method="POST">
                                <input type="hidden" name="id" value="<?= $user['id']; ?>">
                                <input type="hidden" name="type" value="user">
                                <button class="delete-button" type="submit">Delete</button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
            
            <h2>Admin Accounts</h2>
            <div class="account-table">
                <?php while ($admin = $result_admins->fetch_assoc()): ?>
                    <div class="account-row">
                        <div class="account-info">
                            <p><strong>Name:</strong> <?= htmlspecialchars($admin['first_name'] . ' ' . $admin['last_name']); ?></p>
                            <p><strong>Email:</strong> <?= htmlspecialchars($admin['email']); ?></p>
                        </div>
                        <div class="account-actions">
                            <form action="edit_account.php" method="POST">
                                <input type="hidden" name="id" value="<?= $admin['id']; ?>">
                                <input type="hidden" name="type" value="admin">
                                <button class="edit-button" type="submit">Edit</button>
                            </form>
                            <form action="delete_account.php" method="POST">
                                <input type="hidden" name="id" value="<?= $admin['id']; ?>">
                                <input type="hidden" name="type" value="admin">
                                <button class="delete-button" type="submit">Delete</button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>
    </div>
</body>
</html>

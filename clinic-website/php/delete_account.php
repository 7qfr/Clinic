<?php
session_start();
require 'db_connection.php';

$id = $_POST['id'];
$type = $_POST['type'];
$table = ($type === 'admin') ? 'admin' : 'users';

$stmt = $conn->prepare("DELETE FROM $table WHERE id = ?");
$stmt->bind_param("i", $id);
if ($stmt->execute()) {
    echo "Account deleted successfully.";
    header("Location: view_accounts.php");
    exit();
} else {
    echo "Error deleting account.";
}
?>

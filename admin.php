<?php
include 'db.php';

$username = 'actualmanx';
$password = 'Charlotte1%';
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$role = 'admin';

$stmt = $db->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $hashedPassword, $role);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Admin user created successfully.";
} else {
    echo "Failed to create admin user.";
}
?>

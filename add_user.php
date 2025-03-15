<?php
include 'db.php';
session_start();
// Check if the user is an admin
if ($_SESSION['role'] !== 'admin') {
    die("Unauthorized access.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Assuming $db is the database connection
    $stmt = $db->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $hashedPassword, $role);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {
        echo "User added successfully.";
    } else {
        echo "Failed to add user.";
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Upload/Delete/Add User</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="container">
   <form method="POST">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <select name="role" required>
        <option value="admin">Admin</option>
        <option value="user">User</option>
    </select>
    <button type="submit">Add User</button>
</form>
</div>
</body>
</html>

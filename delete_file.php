<?php
include 'db.php';
session_start();
// Check if the user is an admin
if ($_SESSION['role'] !== 'admin') {
    die("Unauthorized access.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fileId = $_POST['file_id'];
    
    // Assuming $db is the database connection
    $stmt = $db->prepare("SELECT true_filename FROM files WHERE id = ?");
    $stmt->bind_param("i", $fileId);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($trueFilename);
    $stmt->fetch();
    
    if ($stmt->num_rows > 0) {
        $filePath = 'uploads/' . $trueFilename;
        
        if (unlink($filePath)) {
            $stmt = $db->prepare("DELETE FROM files WHERE id = ?");
            $stmt->bind_param("i", $fileId);
            $stmt->execute();
            echo "File deleted successfully.";
        } else {
            echo "Failed to delete file from disk.";
        }
    } else {
        echo "File not found.";
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
    <input type="text" name="file_id" placeholder="File ID" required>
    <button type="submit">Delete File</button>
</form>
</div>
</body>
</html>

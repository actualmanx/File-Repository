<?php
include 'db.php';
session_start();
// Check if the user is an admin
if ($_SESSION['role'] !== 'admin') {
    die("Unauthorized access.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $trueFilename = $_FILES['file']['name'];
    $easyFilename = $_POST['easy_filename'];
    $version = $_POST['version'];
    $uploadDir = 'uploads/';
    $uploadFile = $uploadDir . basename($trueFilename);

    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
        // Save file info to the database
        // Assuming $db is the database connection
        $stmt = $db->prepare("INSERT INTO files (real_filename, easy_filename, version) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $trueFilename, $easyFilename, $version);
        $stmt->execute();
        echo "File uploaded successfully.";
    } else {
        echo "File upload failed.";
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
    <form enctype="multipart/form-data" method="POST">
    <input type="file" name="file" required>
    <input type="text" name="easy_filename" placeholder="Easy Filename" required>
    <input type="text" name="version" placeholder="Version" required>
    <button type="submit">Upload</button>
</form>
</div>
</body>
</html>
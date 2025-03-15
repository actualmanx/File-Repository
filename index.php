<?php
include 'db.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>File Repository</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<header>
    <h1>Welcome to the File Repository</h1>
</header>
<div class="container">
    <a href="download.php">Download Files</a><br>
    <?php if ($_SESSION['role'] === 'admin') { ?>
        <a href="upload.php">Upload Files</a><br>
        <a href="delete_file.php">Delete Files</a><br>
        <a href="add_user.php">Add Users</a>
    <?php } ?>
    <br>
    <a href="logout.php">Logout</a>
</div>
</body>
</html>

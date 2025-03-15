<?php
include 'db.php';
// Assuming $db is the database connection
$result = $db->query("SELECT * FROM files");

while ($row = $result->fetch_assoc()) {
    echo "<a href='uploads/" . $row['real_filename'] . "' download>" . $row['easy_filename'] . " (Version: " . $row['version'] . ")</a><br>";
}
?>

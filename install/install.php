<?php
// Check if the install is locked
if (file_exists('lock.php')) {
    die("Installation has already been completed. Please remove the 'install' folder.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect database and admin details from the form
    $dbHost = $_POST['db_host'];
    $dbUser = $_POST['db_user'];
    $dbPass = $_POST['db_pass'];
    $dbName = $_POST['db_name'];
    $adminUser = $_POST['admin_user'];
    $adminPass = $_POST['admin_pass'];

    // Connect to the database
    $conn = new mysqli($dbHost, $dbUser, $dbPass);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Create the database
    if (!$conn->query("CREATE DATABASE IF NOT EXISTS $dbName")) {
        die("Error creating database: " . $conn->error);
    }

    $conn->select_db($dbName);

    // Create the tables
    $usersTable = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        role ENUM('admin', 'user') NOT NULL
    )";

    $filesTable = "CREATE TABLE IF NOT EXISTS files (
        id INT AUTO_INCREMENT PRIMARY KEY,
        true_filename VARCHAR(255) NOT NULL,
        easy_filename VARCHAR(255) NOT NULL,
        version VARCHAR(50) NOT NULL,
        uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    if (!$conn->query($usersTable) || !$conn->query($filesTable)) {
        die("Error creating tables: " . $conn->error);
    }

    // Hash the admin password
    $hashedPassword = password_hash($adminPass, PASSWORD_DEFAULT);

    // Insert the first admin user
    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'admin')");
    $stmt->bind_param("ss", $adminUser, $hashedPassword);

    if ($stmt->execute()) {
        echo "Installation successful! Admin user has been created.";
        
        // Create the db.php file
        $dbFileContent = <<<PHP
<?php
\$servername = "$dbHost";
\$username = "$dbUser";
\$password = "$dbPass";
\$dbname = "$dbName";

// Create connection
\$db = new mysqli(\$servername, \$username, \$password, \$dbname);

// Check connection
if (\$db->connect_error) {
    die("Connection failed: " . \$db->connect_error);
}
?>
PHP;

        if (file_put_contents("../db.php", $dbFileContent)) {
            echo "<br>Database configuration file (db.php) created successfully.";
        } else {
            echo "<br>Failed to create db.php file. Please check file permissions.";
        }

        // Create a lock file to block future installations
        file_put_contents('lock.php', "<?php // Installation lock file ?>");
    } else {
        die("Error creating admin user: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Installer</title>
    <link rel="stylesheet" type="text/css" href="../style.css">
</head>
<body>
<div class="container">
    <h1>Installer</h1>
    <form method="POST">
        <h2>Database Configuration</h2>
        <input type="text" name="db_host" placeholder="Database Host" required>
        <input type="text" name="db_user" placeholder="Database User" required>
        <input type="password" name="db_pass" placeholder="Database Password">
        <input type="text" name="db_name" placeholder="Database Name" required>
        
        <h2>Admin User</h2>
        <input type="text" name="admin_user" placeholder="Admin Username" required>
        <input type="password" name="admin_pass" placeholder="Admin Password" required>
        
        <button type="submit">Install</button>
    </form>
</div>
</body>
</html>

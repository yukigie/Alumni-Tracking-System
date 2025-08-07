<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['backupFile']) && $_FILES['backupFile']['error'] === UPLOAD_ERR_OK) {
        $uploadedFile = $_FILES['backupFile']['tmp_name'];
        $fileName = $_FILES['backupFile']['name'];
        $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);

        $uploadDir = __DIR__ . '/uploadsql/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Move uploaded file to the uploadsql directory
        $destination = $uploadDir . basename($fileName);
        move_uploaded_file($uploadedFile, $destination);

        if ($fileExt === 'sql') {
            // Parse and execute SQL file manually with `DROP TABLE IF EXISTS`
            $sqlContent = file_get_contents($destination);
            if ($sqlContent === false) {
                die('Error reading SQL file.');
            }

            // Disable foreign key checks
            if (!$con->query("SET FOREIGN_KEY_CHECKS = 0")) {
                die("Error disabling foreign key checks: " . $con->error);
            }

            $queries = preg_split('/;\s*[\r\n]+/', $sqlContent); // Split by semicolon followed by a new line
            foreach ($queries as $query) {
                $query = trim($query);

                // Skip empty lines or comments
                if (empty($query) || preg_match('/^(--|#|\/\*)/', $query)) {
                    continue;
                }

                // Check for CREATE TABLE and add DROP TABLE IF EXISTS
                if (stripos($query, 'CREATE TABLE') === 0) {
                    preg_match('/CREATE TABLE `?([a-zA-Z0-9_]+)`?/', $query, $matches);
                    if (!empty($matches[1])) {
                        $tableName = $matches[1];
                        $dropQuery = "DROP TABLE IF EXISTS `$tableName`";
                        if (!$con->query($dropQuery)) {
                            echo "Error dropping table $tableName: " . $con->error . "<br>";
                            die();
                        }
                    }
                }

                // Execute the query
                if (!$con->query($query)) {
                    echo "<a href='Admin-Backup.php'>< Return To Backup Page</a><br><br>";
                    echo "Error executing query: " . htmlspecialchars($query) . "<br>";
                    echo "Error message: " . $con->error . "<br>";
                    die();
                }
            }

            // Re-enable foreign key checks
            if (!$con->query("SET FOREIGN_KEY_CHECKS = 1")) {
                die("Error enabling foreign key checks: " . $con->error);
            }

            echo "<script>
                alert('Database restored successfully from SQL file.');
                window.location.href = 'Admin-Backup.php';
              </script>";
        } elseif ($fileExt === 'zip') {
            // Handle ZIP files (remains the same as before)
        } else {
            echo "<a href='Admin-Backup.php'>< Return To Backup Page</a><br><br>";
            echo 'Error: Unsupported file type. Please upload a .sql or .zip file.';
        }

        // Cleanup uploaded file
        unlink($destination);
    } else {
        echo "<a href='Admin-Backup.php'>< Return To Backup Page</a><br><br>";
        echo 'Error: File upload failed.';
    }

    $con->close();
}
?>

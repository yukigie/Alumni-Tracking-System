<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tables = $_POST['tables'] ?? []; 
    $selectAll = in_array('select_all', $tables); 

    // Define the export directory
    $serverExportDir = __DIR__ . '/exports/';
    if (!is_dir($serverExportDir)) {
        mkdir($serverExportDir, 0777, true);
    }

    function normalizePath($path) {
        return str_replace('\\', '/', $path);
    }

    // Array to store exported file paths
    $exportedFiles = [];

    // Export database to SQL
    if (isset($_POST['backupSQL'])) {
        $databaseName = 'db_alumnitracker';
        $backupFile = $serverExportDir . $databaseName . '_' . date('Y-m-d_H-i-s') . '.sql';
        $sqlDump = "";

        // Fetch all tables
        $tablesResult = $con->query("SHOW TABLES");
        if ($tablesResult) {
            while ($row = $tablesResult->fetch_row()) {
                $tableName = $row[0];

                // Get create table statement
                $createResult = $con->query("SHOW CREATE TABLE `$tableName`");
                $createRow = $createResult->fetch_row();
                $sqlDump .= $createRow[1] . ";\n\n";

                // Get table data
                $dataResult = $con->query("SELECT * FROM `$tableName`");
                if ($dataResult && $dataResult->num_rows > 0) {
                    while ($dataRow = $dataResult->fetch_assoc()) {
                        $values = array_map(function ($value) use ($con) {
                            return $value === null ? 'NULL' : "'" . $con->real_escape_string($value) . "'";
                        }, array_values($dataRow));
                        $values = implode(",", $values);
                        $sqlDump .= "INSERT INTO `$tableName` VALUES ($values);\n";
                    }
                }
                $sqlDump .= "\n\n";
            }
        } else {
            die("Error fetching tables: " . $con->error);
        }


        if (file_put_contents($backupFile, $sqlDump) === false) {
            die("Error: Unable to write SQL backup to file.");
        }
        $exportedFiles[] = $backupFile;
    }

    // Export selected tables to CSV
    if ($selectAll || !empty($tables)) {
        $tablesToExport = $selectAll ? [] : $tables;

        // Fetch all table names dynamically for "Select All"
        if ($selectAll) {
            $result = $con->query("SHOW TABLES");
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_array()) {
                    $tablesToExport[] = $row[0];
                }
            }
        }

        // Export each table to CSV
        foreach ($tablesToExport as $table) {
            $csvFilename = normalizePath($serverExportDir . $table . '_' . date('Y-m-d_H-i-s') . '.csv');
            $result = $con->query("SELECT * FROM `$table`");
            if ($result) {
                $file = fopen($csvFilename, 'w');
                $fields = $result->fetch_fields();
                $headers = array_map(function($field) { return $field->name; }, $fields);
                fputcsv($file, $headers);

                while ($row = $result->fetch_assoc()) {
                    fputcsv($file, $row);
                }
                fclose($file);
                $exportedFiles[] = $csvFilename;
            } else {
                echo "Error exporting table $table: " . $con->error . "<br>";
            }
        }
    }

    // Create ZIP archive
    if (!empty($exportedFiles)) {
        $zipFilename = $serverExportDir . 'backup_data_' . date('Y-m-d_H-i-s') . '.zip';
        $zip = new ZipArchive();

        // Open the ZIP archive
        if ($zip->open($zipFilename, ZipArchive::CREATE) === TRUE) {
            foreach ($exportedFiles as $file) {
                if (file_exists($file)) {
                    $zip->addFile($file, basename($file));
                } else {
                    echo "Warning: File $file does not exist.<br>";
                }
            }
            $zip->close();
        } else {
            die("Error: Unable to create ZIP archive.");
        }

        // Verify the ZIP file before sending it for download
        if (file_exists($zipFilename) && filesize($zipFilename) > 0) {
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="' . basename($zipFilename) . '"');
            header('Content-Length: ' . filesize($zipFilename));
            readfile($zipFilename);

            // Clean up files
            foreach ($exportedFiles as $file) {
                unlink($file); // Delete exported files
            }
            unlink($zipFilename);
        } else {
            echo "Error: ZIP file was not created successfully.<br>";
        }
    } else {
        echo "No files were exported to add to the ZIP.<br>";
    }

    $con->close();
}
?>

<?php
$hostname = "localhost";
$username ="u745708551_dbuser";
$password = "DBPassword@2003";
$database = "u745708551_db_alumnitrack";

//Create Connection
$con = new mysqli($hostname, $username, $password, $database);

//check connection
if ($con->connect_error) {
	die("Connection Failed: " . $con->connect_error);
}
<?php
$host = "localhost"; // Change if using a different server
$user = "root";      // Default XAMPP username
$pass = "";          // Default XAMPP password is empty
$dbname = "exam_db"; // Database name

$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

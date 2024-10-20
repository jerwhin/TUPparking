<?php
$servername = "localhost";
$username = "root";  // Default MySQL username for XAMPP
$password = "";  // Leave empty if no password is set
$dbname = "Parking";  // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
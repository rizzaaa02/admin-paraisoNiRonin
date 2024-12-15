<?php
// Database credentials
$servername = "localhost"; // Server name or IP
$username = "root";        // Database username
$password = "Mabayag123***";            // Database password
$database = "ronin";         // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

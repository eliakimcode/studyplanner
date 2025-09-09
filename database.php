<?php
// Database connection parameters
$host = 'localhost';
$username = 'root'; 
$password = ''; 
$database = 'studyplanner';

// Create a new database connection
$conn = new mysqli($host, $username, $password, $database);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start a session
session_start();
?>

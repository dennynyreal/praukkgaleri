<?php
// config.php

// Database configuration
$servername = "localhost"; // Change if your database server is different
$username = "root";         // Your database username
$password = "";             // Your database password
$dbname = "ukk_galerifoto"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set the charset to utf8
$conn->set_charset("utf8");
?>
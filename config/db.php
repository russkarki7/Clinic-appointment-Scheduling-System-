<?php

$host = "localhost";   
$user = "np03cs4s240191";          
$password = "VCbZA5VhUN";          
$database = "np03cs4s240191"; 

$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("❌ Database connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8");

?>

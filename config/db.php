<?php

$host = "localhost";    
$user = "root";          
$password = "";          
$database = "clinic_db"; 

$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("❌ Database connection failed: " . $conn->connect_error);
}


$conn->set_charset("utf8");


?>

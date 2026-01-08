<?php
// config/db.php

$host = 'localhost';
$db_name = 'careerroadmap';
$username = 'root';
$password = ''; // Default XAMPP password is empty

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected successfully"; 
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}
?>

<?php

$host = "localhost";
$user = "root";
$pass = "";
$db = "db_lms";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Table creation if it doesn't exist
// Users table
$users = "CREATE TABLE IF NOT EXISTS users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    password VARCHAR(60) NOT NULL,
    account_type VARCHAR(10) NOT NULL,
    nim_nip VARCHAR(20) NOT NULL,
    name VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    prodi VARCHAR(20) NOT NULL,
    class VARCHAR(1),
    year INT(4),
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

$conn->query($users);
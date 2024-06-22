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
    user_id VARCHAR(20) UNIQUE PRIMARY KEY NOT NULL,
    password VARCHAR(60) NOT NULL,
    account_type VARCHAR(10) NOT NULL,
    name VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    prodi VARCHAR(20) NOT NULL,
    class VARCHAR(1),
    year INT(4),
    date_registered TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

// Courses table
$courses = "CREATE TABLE IF NOT EXISTS courses (
    course_id VARCHAR(8) UNIQUE PRIMARY KEY NOT NULL, 
    course_name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    date_created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    owner_id VARCHAR(20) NOT NULL,
    FOREIGN KEY (owner_id) REFERENCES users(user_id)
);";

// Enrollments table
$enrollments = "CREATE TABLE IF NOT EXISTS enrollments (
    enrollment_id INT AUTO_INCREMENT PRIMARY KEY NOT NULL, 
    course_id VARCHAR(8) NOT NULL, 
    user_id VARCHAR(20) NOT NULL,
    FOREIGN KEY (course_id) REFERENCES courses(course_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    UNIQUE (course_id, user_id)
);";

// Materials table
$materials = "CREATE TABLE IF NOT EXISTS materials (
    material_id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    course_id VARCHAR(8) NOT NULL, 
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    file_path VARCHAR(255),
    file_name VARCHAR(255),
    date_created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    owner_id VARCHAR(20) NOT NULL,
    FOREIGN KEY (course_id) REFERENCES courses(course_id),
    FOREIGN KEY (owner_id) REFERENCES users(user_id)
);";

// Assignments table
$assignments = "CREATE TABLE IF NOT EXISTS assignments (
    assignment_id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    course_id VARCHAR(8) NOT NULL, 
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    file_path VARCHAR(255),
    file_name VARCHAR(255),
    due_date DATETIME NOT NULL,
    date_created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    owner_id VARCHAR(20) NOT NULL,
    FOREIGN KEY (course_id) REFERENCES courses(course_id),
    FOREIGN KEY (owner_id) REFERENCES users(user_id)
);";

// Test table
$tests = "CREATE TABLE IF NOT EXISTS tests (
    test_id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    course_id VARCHAR(8) NOT NULL, 
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    link VARCHAR(255) NOT NULL,
    due_date DATETIME NOT NULL,
    date_created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    owner_id VARCHAR(20) NOT NULL,
    FOREIGN KEY (course_id) REFERENCES courses(course_id),
    FOREIGN KEY (owner_id) REFERENCES users(user_id)
);";


// Submissions table
$submissions = "CREATE TABLE IF NOT EXISTS submissions (
    submission_id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    assignment_id INT,
    test_id INT,
    user_id VARCHAR(20) NOT NULL,
    description TEXT NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    date_submitted DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (assignment_id) REFERENCES assignments(assignment_id),
    FOREIGN KEY (test_id) REFERENCES tests(test_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);";

// Grades table?

$conn->query($users);
$conn->query($courses);
$conn->query($enrollments);
$conn->query($materials);
$conn->query($assignments);
$conn->query($tests);
$conn->query($submissions);
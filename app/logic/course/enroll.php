<?php
require_once "../connection.php";

// Get the data from the form
$user_id = $_POST['user_id'];
$course_id = $_POST['course_id'];

// Check if the course exists
$query_check = "SELECT * FROM courses WHERE course_id = ?";
$stmt = $conn->prepare($query_check);
$stmt->bind_param("s", $course_id);
$stmt->execute();

if ($stmt->get_result()->num_rows == 0) {
    echo '<script>window.location.replace("../../public/dashboard/dashboard.php?enroll_error=tidak ditemukan") </script>';
    $stmt->close();
    exit();
}

// Check if the user is already enrolled
$query_check = "SELECT * FROM enrollments WHERE user_id = ? AND course_id = ?";
$stmt = $conn->prepare($query_check);
$stmt->bind_param("ss", $user_id, $course_id);
$stmt->execute();

if ($stmt->get_result()->num_rows > 0) {
    echo '<script>window.location.replace("../../public/dashboard/dashboard.php?enroll_error=sudah terdaftar") </script>';
    $stmt->close();
    exit();
}
$stmt->close();

// Insert the data to the database
$query_add = "INSERT INTO enrollments (course_id, user_id) VALUES (?, ?)";
$stmt = $conn->prepare($query_add);
$stmt->bind_param("ss", $course_id, $user_id);
$stmt->execute();
$stmt->close();

// Redirect to dashboard
echo '<script>window.location.replace("../../public/dashboard/dashboard.php?enroll=' . $course_id . '") </script>';
exit();
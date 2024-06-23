<?php
require_once "../../logic/connection.php";

// Get the data
$course_id = $_POST['course_id'];
$user_id = $_POST['user_id'];
$test_id = $_POST['test_id'];

// Insert the data to the database
$query = "INSERT INTO submissions (test_id, user_id) VALUES (?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $test_id, $user_id);
$stmt->execute();
$stmt->close();

// Redirect to course page
echo '../../public/dashboard/course.php?id=' . $course_id . '&&success=Berhasil menandai';
exit();
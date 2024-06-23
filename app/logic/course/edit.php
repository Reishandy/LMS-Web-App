<?php
require_once "../connection.php";

// Get the data from the form
$course_id = $_POST['course_id'];
$course_name = $_POST['name'];
$description = $_POST['description'];

// Check if the course exists
$query_check = "SELECT * FROM courses WHERE course_id = ?";
$stmt = $conn->prepare($query_check);
$stmt->bind_param("s", $course_id);
$stmt->execute();

if ($stmt->get_result()->num_rows == 0) {
    echo '../../public/dashboard/dashboard.php?error=course_not_found';
    exit();
}
$stmt->close();

// Update the data in the database
$query_update = "UPDATE courses SET course_name = ?, description = ? WHERE course_id = ?";
$stmt = $conn->prepare($query_update);
$stmt->bind_param("sss", $course_name, $description, $course_id);
$stmt->execute();
$stmt->close();

// Redirect to dashboard
echo '../../public/dashboard/dashboard.php?edit=' . $course_name;
exit();
<?php
require_once "../connection.php";

// Get the data from the form
$course_id = $_POST['course_id'];

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

// Delete submissions by referencing the assignment_id or test_id
$query_delete = "DELETE FROM submissions WHERE assignment_id IN (SELECT assignment_id FROM assignments WHERE course_id = ?)";
$stmt = $conn->prepare($query_delete);
$stmt->bind_param("s", $course_id);
$stmt->execute();
$stmt->close();

$query_delete = "DELETE FROM submissions WHERE test_id IN (SELECT test_id FROM tests WHERE course_id = ?)";
$stmt = $conn->prepare($query_delete);
$stmt->bind_param("s", $course_id);
$stmt->execute();
$stmt->close();

// Delete materials
$query_delete = "DELETE FROM materials WHERE course_id = ?";
$stmt = $conn->prepare($query_delete);
$stmt->bind_param("s", $course_id);
$stmt->execute();
$stmt->close();

// Delete assignments
$query_delete = "DELETE FROM assignments WHERE course_id = ?";
$stmt = $conn->prepare($query_delete);
$stmt->bind_param("s", $course_id);
$stmt->execute();
$stmt->close();

// Delete tests
$query_delete = "DELETE FROM tests WHERE course_id = ?";
$stmt = $conn->prepare($query_delete);
$stmt->bind_param("s", $course_id);
$stmt->execute();
$stmt->close();

// Delete enrollments
$query_delete = "DELETE FROM enrollments WHERE course_id = ?";
$stmt = $conn->prepare($query_delete);
$stmt->bind_param("s", $course_id);
$stmt->execute();
$stmt->close();

// Delete the course
$query_delete = "DELETE FROM courses WHERE course_id = ?";
$stmt = $conn->prepare($query_delete);
$stmt->bind_param("s", $course_id);
$stmt->execute();
$stmt->close();

// Redirect to dashboard
echo '../../public/dashboard/dashboard.php?delete=' . $course_id;
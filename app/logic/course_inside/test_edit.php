<?php
require_once "../../logic/connection.php";

// Get the data
$owner_id = $_SESSION['owner_id'];
$test_id = $_POST['test_id'];
$course_id = $_POST['course_id'];
$title = $_POST['name'];
$description = $_POST['description'];
$due_date = $_POST['due_date'];
$link = $_POST['link'];

// Update the data to the database
$query = "UPDATE tests SET title = ?, description = ?, link = ?, due_date = ? WHERE test_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssssi", $title, $description, $link, $due_date, $test_id);
$stmt->execute();
$stmt->close();

// Redirect to course page
echo '../../public/dashboard/course.php?id=' . $course_id . '&&success=Berhasil mengubah  ' . $title;
exit();
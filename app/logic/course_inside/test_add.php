<?php
require_once "../../logic/connection.php";

// Get the data
$course_id = $_POST['course_id'];
$owner_id = $_POST['owner_id'];
$title = $_POST['name'];
$description = $_POST['description'];
$due_date = $_POST['due_date'];
$link = $_POST['link'];

// Insert the data to the database
$query = "INSERT INTO tests (course_id, title, description, link, due_date, owner_id) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssssss", $course_id, $title, $description, $link, $due_date, $owner_id);
$stmt->execute();
$stmt->close();

// Redirect to course page
echo '../../public/dashboard/course.php?id=' . $course_id . '&&success=Berhasil menambahkan ' . $title;
exit();
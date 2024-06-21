<?php
require_once "../connection.php";

// Get the data from the form
$owner_id = $_POST['owner_id'];
$course_name = $_POST['name'];
$description = $_POST['description'];

while (true) {
    // Generate course ID
    $course_id = generate_course_id();

    // Check if the course id is already registered
    $query_check = "SELECT * FROM courses WHERE course_id = ?";
    $stmt = $conn->prepare($query_check);
    $stmt->bind_param("s", $course_id);
    $stmt->execute();

    if ($stmt->get_result()->num_rows > 0) {
        continue;
    }

    break;
}
$stmt->close();

// Insert the data to the database
$query_add = "INSERT INTO courses (course_id, course_name, description, owner_id) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($query_add);
$stmt->bind_param("ssss", $course_id, $course_name, $description, $owner_id);
$stmt->execute();
$stmt->close();

// Redirect to dashboard
echo '<script>window.location.replace("../../public/dashboard/dashboard.php?add=' . $course_id . '") </script>';
exit();

function generate_course_id(): string
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $lettersLength = strlen($characters);

    $courseId = '';
    for ($i = 0; $i < 8; $i++) {
        $courseId .= $characters[rand(0, $lettersLength - 1)];
    }

    return $courseId;
}

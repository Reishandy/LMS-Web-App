<?php
require_once "../../logic/connection.php";

function get_user_data($user_id): false|array|null
{
    global $conn;

    $query = "SELECT * FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $user_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $stmt->close();

    return $result->fetch_assoc();
}

function get_courses($type, $id): array
{
    global $conn;

    if ($type == "professor") {
        $query = "SELECT course_id, course_name, description, name AS owner_name FROM courses JOIN users ON courses.owner_id = users.user_id WHERE owner_id = ? ORDER BY date_created DESC";
    } else {
        $query = "SELECT courses.course_id, course_name, description, name AS owner_name FROM courses JOIN enrollments ON courses.course_id = enrollments.course_id JOIN users ON courses.owner_id = users.user_id WHERE enrollments.user_id = ? ORDER BY date_created DESC";
    }

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    $courses = [];
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }

    $stmt->close();

    return $courses;
}
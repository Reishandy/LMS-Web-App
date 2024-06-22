<?php
require_once "../../logic/connection.php";

function check_enrollment_or_owner($course_id, $user_id, $type): bool
{
    global $conn;

    if ($type == "student") {
        $query = "SELECT * FROM enrollments WHERE user_id = ? AND course_id = ?";

    } else {
        $query = "SELECT * FROM courses WHERE owner_id = ? AND course_id = ?";

    }
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $user_id, $course_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    if ($result->num_rows == 0) {
        return false;
    }

    return true;
}

function get_class_data($class_id): false|array|null
{
    global $conn;

    $query = "
    SELECT 
        c.course_name AS name,
        c.description,
        u.name AS owner_name,
        c.course_id,
        COUNT(DISTINCT e.user_id) AS enrolled_students,
        COUNT(DISTINCT m.material_id) AS materials_count,
        COUNT(DISTINCT a.assignment_id) AS assignments_count,
        COUNT(DISTINCT t.test_id) AS tests_count
    FROM 
        courses c
    LEFT JOIN 
        users u ON c.owner_id = u.user_id
    LEFT JOIN 
        enrollments e ON c.course_id = e.course_id
    LEFT JOIN 
        materials m ON c.course_id = m.course_id
    LEFT JOIN 
        assignments a ON c.course_id = a.course_id
    LEFT JOIN 
        tests t ON c.course_id = t.course_id
    WHERE 
        c.course_id = ?
    GROUP BY 
        c.course_name, c.description, u.name, c.course_id;
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $class_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $stmt->close();

    return $result->fetch_assoc();
}
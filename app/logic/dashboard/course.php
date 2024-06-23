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

function get_materials($course_id): false|array|null
{
    global $conn;

    $query = "SELECT * FROM materials WHERE course_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $course_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    return $result->fetch_all(MYSQLI_ASSOC);
}

function get_assignments($course_id): false|array|null
{
    global $conn;

    $query = "SELECT * FROM assignments WHERE course_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $course_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    return $result->fetch_all(MYSQLI_ASSOC);
}

function get_tests($course_id): false|array|null
{
    global $conn;

    $query = "SELECT * FROM tests WHERE course_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $course_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    return $result->fetch_all(MYSQLI_ASSOC);
}

// Check if test is marked
function is_test_marked($test_id, $user_id): bool
{
    global $conn;

    $query = "SELECT * FROM submissions WHERE test_id = ? AND user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $test_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    if ($result->num_rows == 0) {
        return false;
    }

    return true;
}

// Check if assigment is done
function is_assignment_done($assignment_id, $user_id): bool
{
    global $conn;

    $query = "SELECT * FROM submissions WHERE assignment_id = ? AND user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $assignment_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    if ($result->num_rows == 0) {
        return false;
    }

    return true;
}

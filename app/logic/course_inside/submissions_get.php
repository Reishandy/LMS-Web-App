<?php
require_once "../../logic/connection.php";

// Get the data
$course_id = $_POST["course_id"];
$id = $_POST["id"];
$type = $_POST["type"];

if ($type == "Tes") {
    $query = "SELECT submissions.*, users.name, users.class, users.year FROM submissions JOIN users ON submissions.user_id = users.user_id WHERE test_id = ?";
} else {
    $query = "SELECT submissions.*, users.name, users.class, users.year FROM submissions JOIN users ON submissions.user_id = users.user_id WHERE assignment_id = ?";
}

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

echo json_encode($result->fetch_all(MYSQLI_ASSOC));
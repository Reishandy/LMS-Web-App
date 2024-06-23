<?php
require_once "../../logic/connection.php";

// Get the data from the form
$item_id = $_POST['id'];
$item_type = $_POST['type'];
$course_id = $_POST['course_id'];

// Delete the uploaded file
if ($item_type == "Materi") {
    $query_select = "SELECT file_path FROM materials WHERE material_id = ?";
} else if ($item_type == "Tugas") {
    $query_select = "SELECT file_path FROM assignments WHERE assignment_id = ?";
}

$stmt = $conn->prepare($query_select);
$stmt->bind_param("i", $item_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($file_path);
$stmt->fetch();

if ($file_path != null) {
    unlink($file_path);
}

$stmt->close();
// Delete depending on the type of item
if ($item_type == "Materi") {
    $query_delete = "DELETE FROM materials WHERE material_id = ?";
} else if ($item_type == "Tugas") {
    $query_delete = "DELETE FROM assignments WHERE assignment_id = ?";
} else if ($item_type == "Tes") {
    $query_delete = "DELETE FROM tests WHERE test_id = ?";
} else {
    echo '<script>window.location.replace("../../public/dashboard/course.php?id=' . $course_id . '&&error=invalid_item_type") </script>';
    exit();
}

$stmt = $conn->prepare($query_delete);
$stmt->bind_param("i", $item_id);
$stmt->execute();
$stmt->close();

// Delete the submission table entry if the item is a test or assignment
if ($item_type == "Tugas" || $item_type == "Tes") {
    $query_delete_submission = "DELETE FROM submissions WHERE assignment_id = ? OR test_id = ?";
    $stmt = $conn->prepare($query_delete_submission);
    $stmt->bind_param("ii", $item_id, $item_id);
    $stmt->execute();
    $stmt->close();
}

echo '<script>window.location.replace("../../public/dashboard/course.php?id=' . $course_id . '&&success=' . $item_type . ' berhasil dihapus") </script>';
exit();

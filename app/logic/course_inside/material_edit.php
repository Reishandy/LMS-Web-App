<?php
require_once "../../logic/connection.php";

// Get the data
$owner_id = $_SESSION['owner_id'];
$material_id = $_POST['material_id'];
$course_id = $_POST['course_id'];
$title = $_POST['name'];
$description = $_POST['description'];
$file_path = null;
$file_name = null;

// Check the file is uploaded
if (isset($_FILES['file']) && $_FILES['file']['size'] > 0){
    $file = $_FILES['file'];
    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];
    $file_size = $file['size'];

    // Check the file size (max 10MB)
    if ($file_size > 10000000) {
        echo '<script>window.location.replace("../../public/dashboard/course.php?id=' . $course_id . '&&error=Ukuran file tidak boleh melebihi 10MB") </script>';
        exit();
    }

    // Define the directory to save the file
    $upload_dir = "../../uploads/materials/";

    // Create the directory if it doesn't exist
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Make unique file name with hash
    $file_name_path = time() . "_" . $course_id . "_" . hash('md5', $file_name);

    // Move the file to the directory
    if (!move_uploaded_file($file_tmp, $upload_dir . $file_name_path)) {
        echo '<script>window.location.replace("../../public/dashboard/course.php?id=' . $course_id . '&&error=Terjadi kesalahan saat mengunggah file") </script>';
        exit();
    }

    $file_path = $upload_dir . $file_name_path;
}

// Update the data to the database if the file is uploaded
if ($file_path != null) {
    $query = "UPDATE materials SET title = ?, description = ?, file_path = ?, file_name = ? WHERE material_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssi", $title, $description, $file_path, $file_name, $material_id);
} else {
    $query = "UPDATE materials SET title = ?, description = ? WHERE material_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssi", $title, $description, $material_id);
}
$stmt->execute();
$stmt->close();

// Redirect to course page
echo '<script>window.location.replace("../../public/dashboard/course.php?id=' . $course_id . '&&success=Berhasil mengubah ' . $title . '") </script>';

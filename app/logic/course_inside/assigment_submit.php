<?php
require_once "../../logic/connection.php";

// Get the data
$course_id = $_POST['course_id'];
$user_id = $_POST['user_id'];
$assigment_id = $_POST['assigment_id'];
$description = $_POST['description'] == "" ? null : $_POST['description'];

// Check the file
if (isset($_FILES['file']) && $_FILES['file']['size'] > 0){
    $file = $_FILES['file'];
    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];
    $file_size = $file['size'];

    // Check the file size (max 10MB)
    if ($file_size > 10000000) {
        echo '../../public/dashboard/course.php?id=' . $course_id . '&&error=Ukuran file tidak boleh melebihi 10MB';
        exit();
    }

    // Define the directory to save the file
    $upload_dir = "../../uploads/submissions/";

    // Create the directory if it doesn't exist
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Make unique file name with hash
    $file_name_path = time() . "_" . $course_id . "_" . hash('md5', $file_name);

    // Move the file to the directory
    if (!move_uploaded_file($file_tmp, $upload_dir . $file_name_path)) {
        echo '../../public/dashboard/course.php?id=' . $course_id . '&&error=Terjadi kesalahan saat mengunggah file';
        exit();
    }

    $file_path = $upload_dir . $file_name_path;
} else {
    echo '../../public/dashboard/course.php?id=' . $course_id . '&&error=File tidak ditemukan';
    exit();
}

// Insert the data to the database
$query = "INSERT INTO submissions (assignment_id, user_id, description, file_path, file_name) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("sssss", $assigment_id, $user_id, $description, $file_path, $file_name);
$stmt->execute();
$stmt->close();

// Redirect to course page
echo '../../public/dashboard/course.php?id=' . $course_id . '&&success=Berhasil mengumpulkan tugas';
exit();
<?php
require_once "../../logic/connection.php";

// Get the data
$course_id = $_POST['course_id'];
$owner_id = $_POST['owner_id'];
$title = $_POST['name'];
$description = $_POST['description'];
$due_date = $_POST['due_date'];
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
        echo '../../public/dashboard/course.php?id=' . $course_id . '&&error=Ukuran file tidak boleh melebihi 10MB';
        exit();
    }

    // Define the directory to save the file
    $upload_dir = "../../uploads/assignments/";

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
}

// Insert the data to the database
$query = "INSERT INTO assignments (course_id, title, description, file_path, file_name, due_date, owner_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("sssssss", $course_id, $title, $description, $file_path, $file_name, $due_date, $owner_id);
$stmt->execute();
$stmt->close();

// Redirect to course page
echo '../../public/dashboard/course.php?id=' . $course_id . '&&success=Berhasil menambahkan ' . $title;
exit();
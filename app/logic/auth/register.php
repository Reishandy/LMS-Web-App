<?php
require_once "../connection.php";

// Get the data from the form
$account = $_POST['account'];
$nim_nip = $_POST['nim-nip'];
$name = $_POST['name'];
$email = $_POST['email'];
$prodi = $_POST['prodi'];
$class = $_POST['class'] ?? null;
$year = $_POST['year'] ? intval($_POST['year']) : null;
$password = $_POST['password'];

// Check if the NIM/NIP is already registered
$query_check = "SELECT * FROM users WHERE nim_nip = ?";
$stmt = $conn->prepare($query_check);
$stmt->bind_param("s", $nim_nip);
$stmt->execute();

if ($stmt->get_result()->num_rows > 0) {
    echo '<script>window.location.replace("../../public/auth/register.php?error=taken") </script>';
    exit();
}

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert the data to the database
$query_add = "INSERT INTO users (password, account_type, nim_nip, name, email, prodi, class, year) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query_add);
$stmt->bind_param("sssssssi", $hashed_password, $account, $nim_nip, $name, $email, $prodi, $class, $year);
$stmt->execute();

// Redirect to login page
echo '<script>window.location.replace("../../public/auth/login.php?success=true") </script>';
exit();
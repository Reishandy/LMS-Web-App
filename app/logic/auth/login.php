<?php
require_once "../connection.php";

// Get the data from the form
$account = $_POST['account'];
$nim_nip = $_POST['nim-nip'];
$password = $_POST['password'];

// Check if the NIM/NIP is already registered
$query_check = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query_check);
$stmt->bind_param("s", $nim_nip);
$stmt->execute();

$result = $stmt->get_result();
if ($result->num_rows == 0) {
    echo '../../public/auth/login.php?error=username';
    $stmt->close();
    exit();
}

// Verify the password
$hashed_password = $result->fetch_assoc()['password'];
$stmt->close();
if (!password_verify($password, $hashed_password)) {
    echo '../../public/auth/login.php?error=password';
    exit();
}

// Start the session
session_start();
$_SESSION['type'] = $account;
$_SESSION['id'] = $nim_nip;
$_SESSION['expire'] = time() + 10800;

// Redirect to the dashboard
echo '../../public/dashboard/dashboard.php';
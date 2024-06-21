<!doctype html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=chrome">
    <title>LMS - Class</title>

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/class.css">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:ital,wght@0,100..800;1,100..800&display=swap"
          rel="stylesheet">
</head>
<body>
<!-- Check login state -->
<?php
session_start();
if (!isset($_SESSION['id']) || !isset($_SESSION['type'])) {
    echo '<script>window.location.replace("../auth/login.php") </script>';
    exit();
}

if ($_SESSION['expire'] < time()) {
    session_destroy();
    echo '<script>window.location.replace("../auth/login.php?error=expired") </script>';
    exit();
}

// Get necessary data from session
$id = $_SESSION['id'];
$type = $_SESSION['type'];

// TODO: Get class data from database
//  name, description, owner_name, course_id, enrolled_students, materials_count, assignments_count, tests_count, etc...

?>

<!-- === MODAL === -->
<div class="modal fade" id="modal-logout" tabindex="-1" aria-labelledby="modal-logout-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-logout-label">Konfirmasi keluar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                Apakah anda yakin ingin keluar?
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                <button id="logout" type="button" class="btn btn-outline-danger">Keluar</button>
            </div>
        </div>
    </div>
</div>

<!-- === NAVBAR === -->
<div class="div-nav m-3 p-3">
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <h1 class="navbar-brand">LMS</h1>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="dashboard.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="https://reishandy.github.io/">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="https://github.com/Reishandy/LMS-Web-App">Repository</a>
                    </li>
                </ul>

                <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modal-logout">Keluar
                </button>
                <?php
                if ($type == "professor") {
                    echo '<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-add">Tambah</button>';
                }
                ?>
            </div>
        </div>
    </nav>
</div>

<!-- === BODY === -->
<div class="div-body m-4 p-3">
    <?php
    if (isset($_GET['error'])) {
        ?>
        <div class="alert alert-danger" role="alert">
            Terjadi kesalahan: <?php echo $_GET['error'] ?>
        </div>
        <?php
    }
    ?>

    <!-- === CLASS DETAILS === -->
    <!-- TODO: Rework -->
    <div class="div-details m-1 p-4">
        <div class="row">
            <div class="col-7">
                <h1>Selamat datang di LMS</h1>
                <h2><?php echo $type == "professor" ? "Dosen" : "Mahasiswa" ?>: <?php echo $name ?></h2>
            </div>
            <div class="col">
                <h4><?php echo $type == "professor" ? "NIP" : "NIM" ?>: <?php echo $id ?></h4>
                <h4>Email: <?php echo $email ?></h4>
                <h4>Prodi: <?php echo strtoupper($prodi) ?></h4>
                <?php
                if ($type == "student") {
                    $class = strtoupper($class);
                    echo "<h4>Kelas: $class</h4>";
                    echo "<h4>Angkatan: $year</h4>";
                }
                ?>
            </div>
        </div>
    </div>

    <!-- === COURSES === -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">
        <!-- TODO: Rework, copy and modify from dashboard -->
    </div>
</div>


<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.slim.min.js"
        integrity="sha256-kmHvs0B+OpCW5GVHUNjv9rOmY0IvSIRcf7zGUDTDQM8=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.2/anime.min.js"
        integrity="sha512-aNMyYYxdIxIaot0Y1/PLuEu3eipGCmsEUBrUq+7aVyPGMFH8z0eTP0tkqAvv34fzN6z+201d3T8HPb1svWSKHQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="../assets/js/class.js"></script>
<script src="../assets/js/dashboard_shared.js"></script>
<script src="../assets/js/animation.js"></script>
</body>
</html>
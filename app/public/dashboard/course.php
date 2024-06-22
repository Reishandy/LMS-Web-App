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
    <link rel="stylesheet" href="../assets/css/course.css">

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

require_once "../../logic/dashboard/course.php";

if (!isset($_GET['id'])) {
    echo '<script>window.location.replace("dashboard.php?error=missing_course_id") </script>';
    exit();
}

// Check if user is enrolled in the course or is the owner
$course_id = $_GET['id'];
if (!check_enrollment_or_owner($course_id, $id, $type)) {
    echo '<script>window.location.replace("dashboard.php?error=not_enrolled_nor_owner") </script>';
    exit();
}

$course_data = get_class_data($course_id);

$owner_name = $course_data['owner_name'];
$title = $course_data['name'];
$description = $course_data['description'];
$course_id = $course_data['course_id'];
$enrolled_students = $course_data['enrolled_students'];
$materials_count = $course_data['materials_count'];
$assignments_count = $course_data['assignments_count'];
$tests_count = $course_data['tests_count'];

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

<div class="modal fade" id="modal-add-choice" tabindex="-1" aria-labelledby="modal-add-choice-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-add-choice-label">Tambah ke kelas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body d-flex justify-content-evenly align-items-center">
                <button class="btn btn-primary" data-bs-target="#modal-add-material" data-bs-toggle="modal"
                        data-bs-dismiss="modal">Materi
                </button>
                <button class="btn btn-primary" data-bs-target="#modal-add-assigment" data-bs-toggle="modal"
                        data-bs-dismiss="modal">Tugas
                </button>
                <button class="btn btn-primary" data-bs-target="#modal-add-test" data-bs-toggle="modal"
                        data-bs-dismiss="modal">Test
                </button>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-add-material" tabindex="-1" aria-labelledby="modal-add-material-label"
     aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-add-material-label">Tambah materi baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="form-add-material" action="../../logic/course_inside/material-add.php" method="POST"
                  class="needs-validation" enctype="multipart/form-data" novalidate>
                <div class="modal-body">
                    <input type="hidden" name="owner_id" value="<?php echo $id ?>">
                    <input type="hidden" name="course_id" value="<?php echo $course_id ?>">

                    <div class="form-floating">
                        <input type="text" class="form-control" id="modal-add-material-name" name="name"
                               placeholder="Nama" pattern=".{1,255}$" required>
                        <label for="modal-add-material-name">Nama materi</label>
                        <div class="invalid-feedback">
                            Nama kelas tidak valid.
                        </div>
                    </div>

                    <div class="form-floating mt-3">
                        <textarea class="form-control" id="modal-add-material-description" name="description"
                                  placeholder="Deskripsi" required></textarea>
                        <label for="modal-add-material-description">Deskripsi</label>
                        <div class="invalid-feedback">
                            Deskripsi tidak boleh kosong.
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <input type="file" class="form-control" id="modal-add-material-file" name="file" accept="*/*">
                        <label for="modal-add-material-file">Ukuran maksimum 10MB</label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-target="#modal-add-choice"
                            data-bs-toggle="modal" data-bs-dismiss="modal">Kembali
                    </button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-add-assigment" tabindex="-1" aria-labelledby="modal-add-assigment-label"
     aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-add-assigment-label">Tambah tugas baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="form-add-assigment" action="../../logic/course_inside/assigment-add.php" method="POST"
                  class="needs-validation" enctype="multipart/form-data" novalidate>
                <div class="modal-body">
                    <input type="hidden" name="owner_id" value="<?php echo $id ?>">
                    <input type="hidden" name="course_id" value="<?php echo $course_id ?>">

                    <div class="form-floating">
                        <input type="text" class="form-control" id="modal-add-assigment-name" name="name"
                               placeholder="Nama" pattern=".{1,255}$" required>
                        <label for="modal-add-assigment-name">Nama tugas</label>
                        <div class="invalid-feedback">
                            Nama kelas tidak valid.
                        </div>
                    </div>

                    <div class="form-floating mt-3">
                        <textarea class="form-control" id="modal-add-assigment-description" name="description"
                                  placeholder="Deskripsi" required></textarea>
                        <label for="modal-add-assigment-description">Deskripsi</label>
                        <div class="invalid-feedback">
                            Deskripsi tidak boleh kosong.
                        </div>
                    </div>

                    <div class="form-floating mt-3">
                        <input type="datetime-local" class="form-control" id="modal-add-assigment-due-date"
                               name="due_date" required>
                        <label for="modal-add-assigment-due-date">Batas waktu</label>
                        <div class="invalid-feedback">
                            Batas waktu tidak valid.
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <input type="file" class="form-control" id="modal-add-assigment-file" name="file" accept="*/*">
                        <label for="modal-add-material-file">Ukuran maksimum 10MB</label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-target="#modal-add-choice"
                            data-bs-toggle="modal" data-bs-dismiss="modal">Kembali
                    </button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-add-test" tabindex="-1" aria-labelledby="modal-add-test-label"
     aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-add-assigment-label">Tambah tugas baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="form-add-test" action="../../logic/course_inside/test-add.php" method="POST"
                  class="needs-validation" novalidate>
                <div class="modal-body">
                    <input type="hidden" name="owner_id" value="<?php echo $id ?>">
                    <input type="hidden" name="course_id" value="<?php echo $course_id ?>">

                    <div class="form-floating">
                        <input type="text" class="form-control" id="modal-add-test-name" name="name"
                               placeholder="Nama" pattern=".{1,255}$" required>
                        <label for="modal-add-test-name">Nama tes</label>
                        <div class="invalid-feedback">
                            Nama kelas tidak valid.
                        </div>
                    </div>

                    <div class="form-floating mt-3">
                        <textarea class="form-control" id="modal-add-test-description" name="description"
                                  placeholder="Deskripsi" required></textarea>
                        <label for="modal-add-test-description">Deskripsi</label>
                        <div class="invalid-feedback">
                            Deskripsi tidak boleh kosong.
                        </div>
                    </div>

                    <div class="form-floating mt-3">
                        <input type="url" class="form-control" id="modal-add-test-link" name="link"
                               placeholder="Link" required>
                        <label for="modal-add-test-link">Link</label>
                        <div class="invalid-feedback">
                            Link tidak valid.
                        </div>
                    </div>

                    <div class="form-floating mt-3">
                        <input type="datetime-local" class="form-control" id="modal-add-test-due-date" name="due_date"
                               required>
                        <label for="modal-add-test-due-date">Batas waktu</label>
                        <div class="invalid-feedback">
                            Batas waktu tidak valid.
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-target="#modal-add-choice"
                            data-bs-toggle="modal" data-bs-dismiss="modal">Kembali
                    </button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
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
                <button id="button-back" type="button" class="btn btn-secondary">Kembali</button>
                <?php
                if ($type == "professor") {
                    echo '<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-add-choice">Tambah</button>';
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
    } elseif (isset($_GET['success'])) {
        ?>
        <div class="alert alert-success" role="alert">
            Berhasil: <?php echo $_GET['success'] ?>
        </div>
        <?php
    }
    ?>

    <!-- === CLASS DETAILS === -->
    <!-- TODO: Rework -->
    <div class="div-details m-1 p-4">
        <div class="row">
            <div class="col-7">
                <h1><?php echo $title ?></h1>
                <p><?php echo $description ?></p>
            </div>
            <div class="col">
                <h3>Oleh: <?php echo $owner_name ?></h3>
                <h4>Mahasiswa Yang terdaftar: <?php echo $enrolled_students ?></h4>
                <h4>Isi kelas:</h4>
                <h4><?php echo $materials_count ?> Materi | <?php echo $assignments_count ?> Tugas
                    | <?php echo $tests_count ?> Test</h4>
            </div>
        </div>
    </div>

    <!-- === COURSES === -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">
        <!-- TODO: Rework, copy and modify from dashboard -->
    </div>
</div>

<footer>
    <div class="div-body m-3 p-3">
        All rights reserved
        <br>
        &copy; 2024 Reishandy
    </div>
</footer>


<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.slim.min.js"
        integrity="sha256-kmHvs0B+OpCW5GVHUNjv9rOmY0IvSIRcf7zGUDTDQM8=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.2/anime.min.js"
        integrity="sha512-aNMyYYxdIxIaot0Y1/PLuEu3eipGCmsEUBrUq+7aVyPGMFH8z0eTP0tkqAvv34fzN6z+201d3T8HPb1svWSKHQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="../assets/js/course.js"></script>
<script src="../assets/js/dashboard_shared.js"></script>
<script src="../assets/js/animation.js"></script>
</body>
</html>
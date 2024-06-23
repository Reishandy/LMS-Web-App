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

<div class="modal fade" id="modal-choice" tabindex="-1" aria-labelledby="modal-choice-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-choice-label">Tambah ke kelas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body d-flex justify-content-evenly align-items-center">
                <button class="btn btn-primary" data-bs-target="#modal-material" data-bs-toggle="modal"
                        data-bs-dismiss="modal">Materi
                </button>
                <button class="btn btn-primary" data-bs-target="#modal-assigment" data-bs-toggle="modal"
                        data-bs-dismiss="modal">Tugas
                </button>
                <button class="btn btn-primary" data-bs-target="#modal-test" data-bs-toggle="modal"
                        data-bs-dismiss="modal">Test
                </button>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-material" tabindex="-1" aria-labelledby="modal-material-label"
     aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-material-label">Tambah materi baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="form-material" action="../../logic/course_inside/material_add.php" method="POST"
                  class="needs-validation" enctype="multipart/form-data" novalidate>
                <div class="modal-body">
                    <input type="hidden" name="owner_id" value="<?php echo $id ?>">
                    <input type="hidden" name="course_id" value="<?php echo $course_id ?>">

                    <div class="form-floating">
                        <input type="text" class="form-control" id="modal-material-name" name="name"
                               placeholder="Nama" pattern=".{1,255}$" required>
                        <label for="modal-material-name">Nama materi</label>
                        <div class="invalid-feedback">
                            Nama materi tidak valid.
                        </div>
                    </div>

                    <div class="form-floating mt-3">
                        <textarea class="form-control" id="modal-material-description" name="description"
                                  placeholder="Deskripsi" required></textarea>
                        <label for="modal-material-description">Deskripsi</label>
                        <div class="invalid-feedback">
                            Deskripsi tidak boleh kosong.
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <input type="file" class="form-control" id="modal-material-file" name="file" accept="*/*">
                        <label for="modal-material-file">Ukuran maksimum 10MB</label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-target="#modal-choice"
                            data-bs-toggle="modal" data-bs-dismiss="modal">Kembali
                    </button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-assigment" tabindex="-1" aria-labelledby="modal-assigment-label"
     aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-assigment-label">Tambah tugas baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="form-assigment" action="../../logic/course_inside/assigment_add.php" method="POST"
                  class="needs-validation" enctype="multipart/form-data" novalidate>
                <div class="modal-body">
                    <input type="hidden" name="owner_id" value="<?php echo $id ?>">
                    <input type="hidden" name="course_id" value="<?php echo $course_id ?>">

                    <div class="form-floating">
                        <input type="text" class="form-control" id="modal-assigment-name" name="name"
                               placeholder="Nama" pattern=".{1,255}$" required>
                        <label for="modal-assigment-name">Nama tugas</label>
                        <div class="invalid-feedback">
                            Nama tugas tidak valid.
                        </div>
                    </div>

                    <div class="form-floating mt-3">
                        <textarea class="form-control" id="modal-assigment-description" name="description"
                                  placeholder="Deskripsi" required></textarea>
                        <label for="modal-assigment-description">Deskripsi</label>
                        <div class="invalid-feedback">
                            Deskripsi tidak boleh kosong.
                        </div>
                    </div>

                    <div class="form-floating mt-3">
                        <input type="datetime-local" class="form-control" id="modal-assigment-due-date"
                               name="due_date" required>
                        <label for="modal-assigment-due-date">Batas waktu</label>
                        <div class="invalid-feedback">
                            Batas waktu tidak valid.
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <input type="file" class="form-control" id="modal-assigment-file" name="file" accept="*/*">
                        <label for="modal-material-file">Ukuran maksimum 10MB</label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-target="#modal-choice"
                            data-bs-toggle="modal" data-bs-dismiss="modal">Kembali
                    </button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-test" tabindex="-1" aria-labelledby="modal-test-label"
     aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-edit-label">Tambah tes baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="form-test" action="../../logic/course_inside/test_add.php" method="POST"
                  class="needs-validation" novalidate>
                <div class="modal-body">
                    <input type="hidden" name="owner_id" value="<?php echo $id ?>">
                    <input type="hidden" name="course_id" value="<?php echo $course_id ?>">

                    <div class="form-floating">
                        <input type="text" class="form-control" id="modal-test-name" name="name"
                               placeholder="Nama" pattern=".{1,255}$" required>
                        <label for="modal-test-name">Nama tes</label>
                        <div class="invalid-feedback">
                            Nama tes tidak valid.
                        </div>
                    </div>

                    <div class="form-floating mt-3">
                        <textarea class="form-control" id="modal-test-description" name="description"
                                  placeholder="Deskripsi" required></textarea>
                        <label for="modal-test-description">Deskripsi</label>
                        <div class="invalid-feedback">
                            Deskripsi tidak boleh kosong.
                        </div>
                    </div>

                    <div class="form-floating mt-3">
                        <input type="url" class="form-control" id="modal-test-link" name="link"
                               placeholder="Link" required>
                        <label for="modal-test-link">Link</label>
                        <div class="invalid-feedback">
                            Link tidak valid.
                        </div>
                    </div>

                    <div class="form-floating mt-3">
                        <input type="datetime-local" class="form-control" id="modal-test-due-date" name="due_date"
                               required>
                        <label for="modal-test-due-date">Batas waktu</label>
                        <div class="invalid-feedback">
                            Batas waktu tidak valid.
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-target="#modal-choice"
                            data-bs-toggle="modal" data-bs-dismiss="modal">Kembali
                    </button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-material-edit" tabindex="-1" aria-labelledby="modal-material-edit-label"
     aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-material-label">Edit materi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="form-material-edit" action="../../logic/course_inside/material_edit.php" method="POST"
                  class="needs-validation" enctype="multipart/form-data" novalidate>
                <div class="modal-body">
                    <input type="hidden" name="owner_id" value="<?php echo $id ?>">
                    <input type="hidden" name="course_id" value="<?php echo $course_id ?>">
                    <input id="id-material-edit" type="hidden" name="material_id">

                    <div class="form-floating">
                        <input type="text" class="form-control" id="modal-material-edit-name" name="name"
                               placeholder="Nama" pattern=".{1,255}$" required>
                        <label for="modal-material-edit-name">Nama materi</label>
                        <div class="invalid-feedback">
                            Nama materi tidak valid.
                        </div>
                    </div>

                    <div class="form-floating mt-3">
                        <textarea class="form-control" id="modal-material-edit-description" name="description"
                                  placeholder="Deskripsi" required></textarea>
                        <label for="modal-material-edit-description">Deskripsi</label>
                        <div class="invalid-feedback">
                            Deskripsi tidak boleh kosong.
                        </div>
                    </div>

                    <h5 id="attachment-material" class="mt-3"></h5>

                    <div class="form-group mt-3">
                        <input type="file" class="form-control" id="modal-material-edit-file" name="file" accept="*/*">
                        <label for="modal-material-edit-file">Ukuran maksimum 10MB</label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Edit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-assigment-edit" tabindex="-1" aria-labelledby="modal-assigment-edit-label"
     aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-assigment-edit-label">Edit tugas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="form-assigment-edit" action="../../logic/course_inside/assigment_edit.php" method="POST"
                  class="needs-validation" enctype="multipart/form-data" novalidate>
                <div class="modal-body">
                    <input type="hidden" name="owner_id" value="<?php echo $id ?>">
                    <input type="hidden" name="course_id" value="<?php echo $course_id ?>">
                    <input id="id-assigment-edit" type="hidden" name="assigment_id">

                    <div class="form-floating">
                        <input type="text" class="form-control" id="modal-assigment-edit-name" name="name"
                               placeholder="Nama" pattern=".{1,255}$" required>
                        <label for="modal-assigment-edit-name">Nama tugas</label>
                        <div class="invalid-feedback">
                            Nama tugas tidak valid.
                        </div>
                    </div>

                    <div class="form-floating mt-3">
                        <textarea class="form-control" id="modal-assigment-edit-description" name="description"
                                  placeholder="Deskripsi" required></textarea>
                        <label for="modal-assigment-edit-description">Deskripsi</label>
                        <div class="invalid-feedback">
                            Deskripsi tidak boleh kosong.
                        </div>
                    </div>

                    <div class="form-floating mt-3">
                        <input type="datetime-local" class="form-control" id="modal-assigment-edit-due-date"
                               name="due_date" required>
                        <label for="modal-assigment-edit-due-date">Batas waktu</label>
                        <div class="invalid-feedback">
                            Batas waktu tidak valid.
                        </div>
                    </div>

                    <h5 id="attachment-assigment" class="mt-3"></h5>

                    <div class="form-group mt-3">
                        <input type="file" class="form-control" id="modal-edit-assigment-file" name="file" accept="*/*">
                        <label for="modal-material-edit-file">Ukuran maksimum 10MB</label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Edit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-test-edit" tabindex="-1" aria-labelledby="modal-test-edit-label"
     aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-tes-edit-label">Edit tes</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="form-test-edit" action="../../logic/course_inside/test_edit.php" method="POST"
                  class="needs-validation" novalidate>
                <div class="modal-body">
                    <input type="hidden" name="owner_id" value="<?php echo $id ?>">
                    <input type="hidden" name="course_id" value="<?php echo $course_id ?>">
                    <input id="id-test-edit" type="hidden" name="test_id">

                    <div class="form-floating">
                        <input type="text" class="form-control" id="modal-test-edit-name" name="name"
                               placeholder="Nama" pattern=".{1,255}$" required>
                        <label for="modal-test-edit-name">Nama tes</label>
                        <div class="invalid-feedback">
                            Nama tes tidak valid.
                        </div>
                    </div>

                    <div class="form-floating mt-3">
                        <textarea class="form-control" id="modal-test-edit-description" name="description"
                                  placeholder="Deskripsi" required></textarea>
                        <label for="modal-test-edit-description">Deskripsi</label>
                        <div class="invalid-feedback">
                            Deskripsi tidak boleh kosong.
                        </div>
                    </div>

                    <div class="form-floating mt-3">
                        <input type="url" class="form-control" id="modal-test-edit-link" name="link"
                               placeholder="Link" required>
                        <label for="modal-test-edit-link">Link</label>
                        <div class="invalid-feedback">
                            Link tidak valid.
                        </div>
                    </div>

                    <div class="form-floating mt-3">
                        <input type="datetime-local" class="form-control" id="modal-test-edit-due-date" name="due_date"
                               required>
                        <label for="modal-test-edit-due-date">Batas waktu</label>
                        <div class="invalid-feedback">
                            Batas waktu tidak valid.
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Edit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-assigment-submit" tabindex="-1" aria-labelledby="modal-assigment-submit-label"
     aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-assigment-submit-label">Submit tugas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="form-assigment-submit" action="../../logic/course_inside/assigment_submit.php" method="POST"
                  class="needs-validation" enctype="multipart/form-data" novalidate>
                <div class="modal-body">
                    <input type="hidden" name="user_id" value="<?php echo $id ?>">
                    <input type="hidden" name="course_id" value="<?php echo $course_id ?>">
                    <input id="id-assigment-submit" type="hidden" name="assigment_id">

                    <div class="form-floating mt-3">
                        <textarea class="form-control" id="modal-assigment-submit-description" name="description"
                                  placeholder="Deskripsi"></textarea>
                        <label for="modal-assigment-submit-description">Deskripsi</label>
                    </div>

                    <div class="form-group mt-3">
                        <input type="file" class="form-control" id="modal-assigment-file" name="file" accept="*/*"
                               required>
                        <label for="modal-material-file">Ukuran maksimum 10MB</label>
                        <div class="invalid-feedback">
                            File tidak boleh kosong.
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-details" tabindex="-1" aria-labelledby="modal-details-label"
     aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-details-label">Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-submissions" tabindex="-1" aria-labelledby="modal-submissions-label"
     aria-hidden="true">
    <div class="modal-dialog modal-fullscreen modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-submissions-label">Submissions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">NIM</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Kelas</th>
                            <th scope="col">Angkatan</th>
                            <th scope="col">Tanggal dikerjakan</th>
                            <th scope="col">Deskripsi</th>
                            <th scope="col">File</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                    <div id="spinner" class="d-flex justify-content-center align-items-center">
                        <div class="spinner-grow text-secondary p-4" role="status">
                            <span class="visually-hidden">Memuat...</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-delete-in" tabindex="-1" aria-labelledby="modal-delete-in-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-delete-in-label">Konfirmasi hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                Apakah anda yakin ingin menghapus item ini?
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>

                <form id="form-delete" action="../../logic/course_inside/in_delete.php" method="POST">
                    <input type="hidden" name="id" id="id-delete-in">
                    <input type="hidden" name="type" id="type-delete-in">
                    <input type="hidden" name="course_id" value="<?php echo $course_id ?>">
                    <button id="delete" type="submit" class="btn btn-outline-danger">Hapus</button>
                </form>
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
                <button id="button-back" type="button" class="btn btn-secondary">Kembali</button>
                <?php
                if ($type == "professor") {
                    echo '<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-choice">Tambah</button>';
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
        <?php
        $materials = get_materials($course_id);
        $assignments = get_assignments($course_id);
        $tests = get_tests($course_id);

        // Combine into one array and assign type
        $contents = [];
        foreach ($materials as $material) {
            $material['type'] = 'Materi';
            $material['color'] = '#FFD700';
            $material['id'] = $material['material_id'];
            $material['edit'] = 'modal-material-edit';
            $material['link'] = '';
            $material['due_date'] = '';
            $contents[] = $material;
        }
        foreach ($assignments as $assignment) {
            $assignment['type'] = 'Tugas';
            $assignment['color'] = '#20B2AA';
            $assignment['id'] = $assignment['assignment_id'];
            $assignment['edit'] = 'modal-assigment-edit';
            $assignment['link'] = '';
            $contents[] = $assignment;
        }
        foreach ($tests as $test) {
            $test['type'] = 'Tes';
            $test['color'] = '#FFA07A';
            $test['id'] = $test['test_id'];
            $test['edit'] = 'modal-test-edit';
            $test['file_path'] = '';
            $test['file_name'] = '';
            $contents[] = $test;
        }

        // sort by date newest first
        usort($contents, function ($a, $b) {
            return strtotime($b['date_created']) - strtotime($a['date_created']);
        });

        if (empty($contents)) {
            echo '<h3 class="text-start m-5">Tidak ada materi, tugas, maupun tes</h3>';
        }

        foreach ($contents as $content) {
            $content['deadline'] = false;
            // Due date for test and assignment
            if ($content['type'] == 'Tugas' || $content['type'] == 'Tes') {
                $due_date = strtotime($content['due_date']);
                $now = time();
                if ($due_date < $now) {
                    $content['deadline'] = true;
                }
            }

            // Check test marked
            $marked = false;
            $deadline = false;
            if ($content['type'] == 'Tes') {
                $marked = is_test_marked($content['id'], $id);
                if ($marked) {
                    $content['deadline'] = true;
                }
            }

            // Check assignment done
            if ($content['type'] == 'Tugas') {
                $marked = is_assignment_done($content['id'], $id);
            }
            ?>
            <div class="p-3">
                <div class="div-card p-3">
                    <div class="row">
                        <div class="col-5"><h4 class="p-1" style="background-color: <?php echo $content['color'] ?>">
                                <?php echo $content['type'] ?></h4>
                        </div>
                        <div class="col">
                            <?php
                            if ($type != "professor") {
                                if ($marked) {
                                    if ($content['type'] == 'Tes') {
                                        echo '<h4 class="p-1">Sudah selesai</h4>';
                                    } else {
                                        echo '<h4 class="p-1">Sudah dikumpulkan</h4>';
                                    }
                                } elseif ($content['deadline']) {
                                    echo '<h4 class="p-1">Terlewati</h4>';
                                } else {
                                    if ($content['type'] == 'Tes')
                                        echo '<h4 class="p-1">Belum selesai</h4>';
                                    elseif ($content['type'] == 'Tugas')
                                        echo '<h4 class="p-1">Belum dikumpulkan</h4>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <h3><?php echo $content['title'] ?></h3>
                    <p><?php echo $content['description'] ?></p>

                    <hr>

                    <div class="col card-details">
                        <div class="row">
                            <?php
                            if ($content['type'] == 'Tugas' || $content['type'] == 'Tes') {
                                ?>
                                <h5>Deadline: <?php echo $content['due_date'] ?></h5>
                                <?php
                            } else {
                                ?>
                                <h5>Diunggah: <?php echo $content['date_created'] ?></h5>
                                <?php
                            }
                            ?>
                        </div>
                        <div class="row">
                            <div class="d-flex justify-content-evenly align-items-center">
                                <?php
                                echo '<button class="btn btn-primary"
                                     data-bs-toggle="modal" data-bs-target="#modal-details" 
                                     data-bs-type="' . $content['type'] . '"
                                     data-bs-title="' . $content['title'] . '" 
                                     data-bs-description="' . $content['description'] . '"
                                     data-bs-due-date="' . $content['due_date'] . '"
                                     data-bs-file-path="' . $content['file_path'] . '"
                                     data-bs-file-name="' . $content['file_name'] . '"
                                     data-bs-link="' . $content['link'] . '">Lihat</button>';
                                if ($type == "professor" && $content['type'] != "Materi") {
                                    // submission list button
                                    echo '<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-submissions" 
                                          data-bs-id="' . $content['id'] . '"
                                          data-bs-course-id="' . $course_id . '"
                                          data-bs-type="' . $content['type'] . '"
                                          data-bs-title="' . $content['title'] . '"
                                          data-bs-all="false">Submisi</button>';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <?php
                            if ($type == "student") {
                                if ($content['type'] == "Tes") {
                                    ?>
                                    <form id="form-test-submit" action="../../logic/course_inside/test_submit.php"
                                          method="post">
                                        <input type="hidden" name="user_id" value="<?php echo $id ?>">
                                        <input type="hidden" name="test_id" value="<?php echo $content['id'] ?>">
                                        <input type="hidden" name="course_id" value="<?php echo $course_id ?>">
                                        <div class="d-flex justify-content-evenly align-items-center">
                                            <button type="submit" class="btn btn-primary" <?php
                                            if ($content['deadline']) {
                                                echo 'disabled';
                                            } ?>>
                                                <?php
                                                if ($marked) {
                                                    echo 'Sudah tertandai';
                                                } elseif ($content['deadline']) {
                                                    echo 'Deadline terlewati';
                                                } else {
                                                    echo 'Tandai sudah';
                                                }
                                                ?>
                                            </button>
                                        </div>
                                    </form>
                                    <?php
                                } elseif ($content['type'] == "Tugas") {
                                    ?>
                                    <div class="d-flex justify-content-evenly align-items-center">
                                        <button class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#modal-assigment-submit"
                                                data-bs-id="<?php echo $content['id'] ?>"
                                                data-bs-title="<?php echo $content['title'] ?>"
                                            <?php
                                            if ($content['deadline']) {
                                                echo 'disabled';
                                            }
                                            ?>>
                                            <?php
                                            if ($marked) {
                                                echo 'Kumpulkan ulang';
                                            } elseif ($content['deadline']) {
                                                echo 'Deadline terlewati';
                                            } else {
                                                echo 'Kumpulkan';
                                            }
                                            ?>
                                        </button>
                                    </div>
                                    <?php
                                }
                            } else {
                                ?>
                                <form id="form-test-submit" action="#" method="post"></form>
                                <?php
                            }
                            ?>
                        </div>
                        <div class="row">
                            <div class="d-flex justify-content-evenly align-items-center">
                                <?php
                                if ($type == "professor") {
                                    // submission list all button
                                    if ($content['type'] != "Materi") {
                                        echo '<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-submissions" 
                                          data-bs-id="' . $content['id'] . '"
                                          data-bs-course-id="' . $course_id . '"
                                          data-bs-type="' . $content['type'] . '"
                                          data-bs-title="' . $content['title'] . '"
                                          data-bs-all="true">Semua</button>';
                                    }
                                    echo '<button class="btn btn-primary" data-bs-toggle="modal" 
                                          data-bs-target="#' . $content['edit'] . '" 
                                          data-bs-id="' . $content['id'] . '"
                                          data-bs-type="' . $content['type'] . '"
                                          data-bs-title="' . $content['title'] . '" 
                                          data-bs-description="' . $content['description'] . '"
                                          data-bs-due-date="' . $content['due_date'] . '"
                                          data-bs-file-path="' . $content['file_path'] . '"
                                          data-bs-file-name="' . $content['file_name'] . '"
                                          data-bs-link="' . $content['link'] . '">Edit</button>';
                                    echo '<button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modal-delete-in" 
                                          data-bs-id="' . $content['id'] . '"
                                          data-bs-type="' . $content['type'] . '"
                                          data-bs-title="' . $content['title'] . '">Hapus</button>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        } ?>
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
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
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
<!doctype html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=chrome">
    <title>LMS</title>

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/dashboard.css">

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

require_once "../../logic/dashboard/dashboard.php";

// Get user data
$user = get_user_data($id);

$name = $user['name'];
$email = $user['email'];
$prodi = $user['prodi'];
$class = $user['class'];
$year = $user['year'];

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

<div class="modal fade" id="modal-add" tabindex="-1" aria-labelledby="modal-add-label" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-add-label">Buat kelas baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="form-add" action="../../logic/course/add.php" method="POST" class="needs-validation" novalidate>
                <div class="modal-body">
                    <input type="hidden" name="owner_id" value="<?php echo $id ?>">

                    <div class="form-floating">
                        <input type="text" class="form-control" id="name" name="name"
                               placeholder="Nama" pattern=".{1,255}$" required>
                        <label for="name">Nama kelas</label>
                        <div class="invalid-feedback">
                            Nama kelas tidak valid.
                        </div>
                    </div>

                    <div class="form-floating mt-3">
                        <textarea class="form-control" id="description" name="description"
                                  placeholder="Deskripsi" required></textarea>
                        <label for="description">Deskripsi</label>
                        <div class="invalid-feedback">
                            Deskripsi tidak boleh kosong.
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Buat</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-join" tabindex="-1" aria-labelledby="modal-join-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-join-label">Bergabung ke kelas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="form-join" action="../../logic/course/enroll.php" method="POST" class="needs-validation"
                  novalidate>
                <div class="modal-body">
                    <input type="hidden" name="user_id" value="<?php echo $id ?>">

                    <div class="form-floating">
                        <input type="text" class="form-control" id="course-id" name="course_id"
                               placeholder="ID" pattern="[a-zA-Z0-9]{8}" required>
                        <label for="course-id">ID kelas</label>
                        <div class="invalid-feedback">
                            ID kelas tidak valid.
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Gabung</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-delete" tabindex="-1" aria-labelledby="modal-delete-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-delete-label">Konfirmasi hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                Apakah anda yakin ingin menghapus kelas ini? semua data yang terkait akan ikut terhapus.
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>

                <form id="form-delete" action="../../logic/course/delete.php" method="POST">
                    <input type="hidden" name="course_id" id="course-id-delete">
                    <button id="delete" type="submit" class="btn btn-outline-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-edit" tabindex="-1" aria-labelledby="modal-edit-label" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-edit-label">Edit </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="form-edit" action="../../logic/course/edit.php" method="POST" class="needs-validation" novalidate>
                <div class="modal-body">
                    <input type="hidden" name="course_id" id="course-id-edit">

                    <div class="form-floating">
                        <input type="text" class="form-control" id="edit-name" name="name"
                               placeholder="Nama" pattern=".{1,255}$" required>
                        <label for="edit-name">Nama kelas</label>
                        <div class="invalid-feedback">
                            Nama kelas tidak valid.
                        </div>
                    </div>

                    <div class="form-floating mt-3">
                        <textarea class="form-control" id="edit-description" name="description"
                                  placeholder="Deskripsi" required></textarea>
                        <label for="edit-description">Deskripsi</label>
                        <div class="invalid-feedback">
                            Deskripsi tidak boleh kosong.
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
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
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
                    echo '<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-add">Buat kelas baru</button>';
                } else {
                    echo '<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-join">Gabung kelas</button>';
                }
                ?>
            </div>
        </div>
    </nav>
</div>

<!-- === BODY === -->
<div class="div-body m-4 p-3">
    <?php
    if (isset($_GET['add'])) {
        ?>
        <div class="alert alert-success" role="alert">
            Kelas berhasil dibuat dengan id <?php echo $_GET['add'] ?>
        </div>
        <?php
    } elseif (isset($_GET['enroll'])) {
        ?>
        <div class="alert alert-success" role="alert">
            Berhasil bergabung dengan kelas id <?php echo $_GET['enroll'] ?>
        </div>
        <?php
    } elseif (isset($_GET['enroll_error'])) {
        ?>
        <div class="alert alert-danger" role="alert">
            Gagal bergabung ke kelas, <?php echo $_GET['enroll_error'] ?>
        </div>
        <?php
    } elseif (isset($_GET['delete'])) {
        ?>
        <div class="alert alert-success" role="alert">
            Kelas <?php echo $_GET['delete'] ?> berhasil dihapus.
        </div>
        <?php
    } elseif (isset($_GET['edit'])) {
        ?>
        <div class="alert alert-success" role="alert">
            Kelas <?php echo $_GET['edit'] ?> berhasil diubah.
        </div>
        <?php
    } elseif (isset($_GET['error'])) {
        ?>
        <div class="alert alert-danger" role="alert">
            Terjadi kesalahan: <?php echo $_GET['error'] ?>
        </div>
        <?php
    }
    ?>

    <!-- === ACCOUNT DETAILS === -->
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
        <?php
        $courses = get_courses($type, $id);

        if (empty($courses)) {
            echo '<h3 class="text-start m-5">Tidak ada kelas</h3>';
        }

        foreach ($courses as $course) {
            $course_id = $course['course_id'];
            $course_name = $course['course_name'];
            $course_description = $course['description'];
            $owner_name = $course['owner_name'];

            ?>
            <div class="p-3">
                <div class="div-card p-3">
                    <h3><?php echo $course_name ?></h3>
                    <p><?php echo $course_description ?></p>

                    <hr>

                    <div class="col card-details">
                        <div class="row">
                            <h5>Pemilik: <?php echo $owner_name ?></h5>
                        </div>
                        <div class="row">
                            <h5>ID: <?php echo $course_id ?></h5>
                        </div>
                        <div class="row">
                            <div class="d-flex justify-content-evenly align-items-center">
                                <a href="course.php?id=<?php echo $course_id ?>" class="btn btn-primary">Masuk</a>
                                <?php
                                if ($type == "professor") {
                                    echo '<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-edit" data-bs-id="' . $course_id . '" data-bs-name="' . $course_name . '" data-bs-description="' . $course_description . '">Edit</button>';
                                    echo '<button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modal-delete" data-bs-id="' . $course_id . '" data-bs-name="' . $course_name . '">Hapus</button>';
                                }

                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
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
<script src="../assets/js/dashboard.js"></script>
<script src="../assets/js/dashboard_shared.js"></script>
<script src="../assets/js/animation.js"></script>
</body>
</html>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=chrome">
    <title>LMS - Mausk</title>

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/auth.css">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:ital,wght@0,100..800;1,100..800&display=swap"
          rel="stylesheet">
</head>
<body>

<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="div-form login p-4">
        <h1 class="mt-4">MASUK</h1>

        <div class="m-5">
            <form action="../../logic/auth/login.php" method="POST" class="needs-validation" novalidate>

                <?php
                if (isset($_GET['error']) && $_GET['error'] == 'username') {
                    ?>
                    <div class="alert alert-danger" role="alert">
                        Akun tidak ditemukan.
                    </div>
                    <?php
                } elseif (isset($_GET['error']) && $_GET['error'] == 'password') {
                    ?>
                    <div class="alert alert-danger" role="alert">
                        Password salah.
                    </div>
                    <?php
                } elseif (isset($_GET['error']) && $_GET['error'] == 'expired') {
                    ?>
                    <div class="alert alert-danger" role="alert">
                        Sesi telah berakhir, Silahkan masuk kembali.
                    </div>
                    <?php
                } elseif (isset($_GET['success']) && $_GET['success'] == 'true') {
                    ?>
                    <div class="alert alert-success" role="alert">
                        Telah berhasil mendaftar. Silahkan masuk.
                    </div>
                    <?php
                }
                ?>

                <div class="row">
                    <div class="col-4">
                        <div class="form-floating">
                            <select class="form-select form-select-lg" aria-label="Default select example" id="account"
                                    name="account" required>
                                <option selected value="student">Mahasiswa</option>
                                <option value="professor">Dosen</option>
                            </select>
                            <label for="account">Akun</label>
                        </div>
                    </div>

                    <div class="col-8">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="nim-nip" name="nim-nip"
                                   placeholder="NIM / NIP" pattern="^[0-9]{11}$|^[0-9]{18}$"
                                   required>
                            <label for="nim-nip">NIM / NIP</label>
                            <div id="validate-nim-nip" class="invalid-feedback">
                                NIM tidak valid.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-floating mt-4">
                    <input type="password" class="form-control" id="password" name="password"
                           placeholder="Password" pattern=".{8,}" required>
                    <label for="password">Password</label>
                    <div class="invalid-feedback">
                        Masukkan password minimal 8 karakter.
                    </div>
                </div>

                <div class="form-group mt-4">
                    <h5>Daftar</h5>
                </div>

                <button type="submit" class="btn btn-outline-dark btn-lg mt-4">
                    Masuk
                </button>
            </form>
        </div>

        <h6>&copy; 2024 Reishandy</h6>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.2/anime.min.js"
        integrity="sha512-aNMyYYxdIxIaot0Y1/PLuEu3eipGCmsEUBrUq+7aVyPGMFH8z0eTP0tkqAvv34fzN6z+201d3T8HPb1svWSKHQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="../assets/js/login.js"></script>
<script src="../assets/js/auth.js"></script>
<script src="../assets/js/animation.js"></script>
</body>
</html>
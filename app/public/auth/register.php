<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=chrome">
    <title>LMS - Daftar</title>

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
    <div class="div-form p-4">
        <h1 class="mt-4">DAFTAR</h1>

        <div class="m-5">
            <form action="../../logic/auth/register.php" method="POST" class="needs-validation" novalidate>

                <?php
                if (isset($_GET['error']) && $_GET['error'] == 'taken') {
                    ?>
                    <div class="alert alert-danger" role="alert">
                        Akun sudah terdaftar.
                    </div>
                    <?php
                }
                ?>

                <div class="form-floating">
                    <select class="form-select" aria-label="Default select example" id="account"
                            name="account" required>
                        <option selected value="student">Mahasiswa</option>
                        <option value="professor">Dosen</option>
                    </select>
                    <label for="account">Akun</label>
                </div>

                <div class="row mt-4 row-rem">
                    <div class="col-4 col-4-rem">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="nim-nip" name="nim-nip"
                                   placeholder="NIM / NIP" pattern="^[0-9]{11}$$"
                                   required>
                            <label for="nim-nip">NIM</label>
                            <div id="validate-nim-nip" class="invalid-feedback">
                                NIM tidak valid.
                            </div>
                        </div>
                    </div>

                    <div class="col-4 col-4-rem">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="name" name="name"
                                   placeholder="Name" pattern=".{1,50}$" required>
                            <label for="name">Nama</label>
                            <div class="invalid-feedback">
                                Masukkan nama lengkap.
                            </div>
                        </div>
                    </div>

                    <div class="col-4 col-4-rem">
                        <div class="form-floating">
                            <input type="email" class="form-control" id="email" name="email"
                                   placeholder="Email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                                   required>
                            <label for="email">Email</label>
                            <div class="invalid-feedback">
                                Masukkan email yang valid.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4 row-rem">
                    <div class="col-4 col-4-rem">
                        <div class="form-floating">
                            <select class="form-select" aria-label="Default select example" id="prodi"
                                    name="prodi" required>
                                <option selected value="ilkom">Ilmu Komputer</option>
                                <option value="tekin">Teknik Industri</option>
                                <option value="tekpang">Teknologi Pangan</option>
                            </select>
                            <label for="prodi">Prodi</label>
                        </div>
                    </div>

                    <div class="col-4 col-4-rem">
                        <div class="form-floating">
                            <select class="form-select" aria-label="Default select example" id="class"
                                    name="class" required>
                                <option selected value="a">A</option>
                                <option value="b">B</option>
                            </select>
                            <label for="class">Kelas</label>
                        </div>
                    </div>

                    <div class="col-4 col-4-rem">
                        <div class="form-floating">
                            <select class="form-select" aria-label="Default select example" id="year"
                                    name="year" required>
                                <option selected value="2023">2023</option>
                                <option value="2022">2022</option>
                                <option value="2021">2021</option>
                                <option value="2020">2020</option>
                            </select>
                            <label for="year">Angkatan</label>
                        </div>
                    </div>
                </div>

                <div class="row mt-4 row-rem">
                    <div class="col-6 col-6-rem">
                        <div class="form-floating">
                            <input type="password" class="form-control" id="password" name="password"
                                   placeholder="Password" pattern=".{8,}" required>
                            <label for="password">Password</label>
                            <div class="invalid-feedback">
                                Masukkan password minimal 8 karakter.
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-6-rem">
                        <div class="form-floating">
                            <input type="password" class="form-control" id="password-confirm"
                                   name="password-confirm"
                                   placeholder="Confirm Password" required>
                            <label for="password-confirm">Konfirmasi password</label>
                            <div class="invalid-feedback">
                                Password tidak sama.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-4">
                    <h5>Masuk</h5>
                </div>

                <button type="submit" class="btn btn-outline-dark btn-lg mt-4">
                    Daftar
                </button>
            </form>
        </div>

        <h6>&copy; 2024 Reishandy</h6>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.2/anime.min.js"
        integrity="sha512-aNMyYYxdIxIaot0Y1/PLuEu3eipGCmsEUBrUq+7aVyPGMFH8z0eTP0tkqAvv34fzN6z+201d3T8HPb1svWSKHQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="../assets/js/register.js"></script>
<script src="../assets/js/auth.js"></script>
<script src="../assets/js/animation.js"></script>
</body>
</html>
<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_petugas = $_POST['nama_petugas'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $telp = $_POST['telp'];

    $query = "INSERT INTO petugas (nama_petugas, username, password, tlep) 
              VALUES ('$nama_petugas', '$username', '$password', '$telp')";

    if (mysqli_query($conn, $query)) {
        $success = "Registrasi berhasil!";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register Petugas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
    body {
        background-color: #f2fff5;
        font-family: 'Poppins', sans-serif;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .form-wrapper {
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
    }

    .form-section {
        padding: 40px 30px;
        background-color: #fff;
        position: relative;
    }

    .logo-ecoguard {
        position: absolute;
        top: 20px;
        left: 30px;
        display: flex;
        align-items: center;
    }

    .logo-ecoguard img {
        width: 30px;
        margin-right: 8px;
    }

    .logo-ecoguard span {
        font-size: 20px;
        font-weight: bold;
        color: #28a745;
    }

    .form-label {
        font-weight: 500;
    }

    .form-control {
        border-radius: 12px;
    }

    .image-section {
        background-image: url(daunregister.png);
        /* ganti sesuai gambar yang kamu mau */
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
        border-top-right-radius: 20px;
        border-bottom-right-radius: 20px;
    }

    @media (max-width: 768px) {
        .image-section {
            display: none;
        }
    }
    </style>
</head>

<body>
    <div class="container">
        <div class="row form-wrapper">
            <!-- Form -->
            <div class="col-md-6 form-section">
                <div class="logo-ecoguard">
                    <img src="logoeco.png" alt="EcoGuard Logo">
                    <span>EcoGuard</span>
                </div>

                <h3 class="text-success mb-4 text-center mt-5">Register Petugas</h3>

                <?php if (isset($success)): ?>
                <div class="alert alert-success"><?= $success ?></div>
                <?php elseif (isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label for="nama_petugas" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama_petugas" name="nama_petugas" required>
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="telp" class="form-label">Telepon</label>
                        <input type="text" class="form-control" id="telp" name="telp" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Register</button>
                    <p class="mt-3 text-center">Sudah punya akun? <a href="login_petugas.php"
                            class="text-success">Login</a></p>
                    <p class="mt-2 text-center">
                        <a href="register_masyarakat.php" class="btn btn-outline-success w-100">Register sebagai
                            Pengguna</a>
                    </p>
                </form>
            </div>

            <!-- Gambar -->
            <div class="col-md-6 image-section d-none d-md-block"></div>
        </div>
    </div>
</body>

</html>
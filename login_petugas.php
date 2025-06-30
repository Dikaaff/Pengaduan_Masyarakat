<?php
include 'koneksi.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM petugas WHERE username = '$username'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['id_petugas'] = $user['id_petugas'];
        $_SESSION['nama_petugas'] = $user['nama_petugas'];
        header('Location: dashboard_petugas.php');
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Petugas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
    body {
        background-color: #f2fff5;
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
        /* Ganti jika ingin daun lain */
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

                <h3 class="text-success mb-4 text-center mt-5">Login Petugas</h3>

                <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Login</button>
                    <p class="mt-3 text-center">Belum punya akun? <a href="register_petugas.php"
                            class="text-success">Register</a></p>
                    <p class="mt-2 text-center">
                        <a href="login_masyarakat.php" class="btn btn-outline-success w-100">Login sebagai
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
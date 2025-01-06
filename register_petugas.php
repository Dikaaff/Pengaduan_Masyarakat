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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Petugas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background-color: #f8f9fa;">
    <div class="container mt-5">
        <div class="card mx-auto" style="max-width: 400px; border: none;">
            <div class="card-body">
                <h3 class="text-center text-success">Register Petugas</h3>
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
                </form>
            </div>
        </div>
    </div>
</body>

</html>
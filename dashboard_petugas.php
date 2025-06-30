<?php
include 'koneksi.php';
session_start();


// Cek apakah petugas sudah login
if (!isset($_SESSION['id_petugas'])) {
    header('Location: login_petugas.php');
    exit;
}

// Ambil data petugas yang login
$id_petugas = $_SESSION['id_petugas'];
$nama_petugas = $_SESSION['nama_petugas'];

// Update status laporan (terima)
if (isset($_GET['terima'])) {
    $id_pengaduan = $_GET['terima'];
    $query = "UPDATE pengaduan SET status = 'accepted' WHERE id_pengaduan = '$id_pengaduan'";
    mysqli_query($conn, $query);
}

// Update status laporan (tolak)
if (isset($_GET['tolak'])) {
    $id_pengaduan = $_GET['tolak'];
    $query = "UPDATE pengaduan SET status = 'rejected' WHERE id_pengaduan = '$id_pengaduan'";
    mysqli_query($conn, $query);
}

// Hapus laporan
if (isset($_GET['hapus'])) {
    $id_pengaduan = $_GET['hapus'];
    $query = "DELETE FROM pengaduan WHERE id_pengaduan = '$id_pengaduan'";
    mysqli_query($conn, $query);
}

// Ambil data laporan
$query = "SELECT p.*, m.nama FROM pengaduan p 
          JOIN masyarakat m ON p.nik = m.nik 
          ORDER BY p.status, p.tgl_pengaduan DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard Petugas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
    body {
        background-color: #f2fff5;
        font-family: 'Segoe UI', sans-serif;
    }

    .navbar {
        background-color: #fff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .logo-ecoguard {
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

    .container {
        margin-top: 40px;
    }

    .card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05);
    }

    .table {
        border-radius: 10px;
        overflow: hidden;
    }

    .table th {
        background-color: #d1f5d3 !important;
        color: #2c7a2c;
    }

    .table td,
    .table th {
        vertical-align: middle;
    }

    .btn-sm {
        border-radius: 10px;
        padding: 6px 14px;
        font-size: 14px;
    }

    .table-striped>tbody>tr:nth-of-type(odd) {
        background-color: #f7fdf8;
    }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar px-4 py-3 d-flex justify-content-between bg-white shadow-sm">
        <div class="logo-ecoguard d-flex align-items-center">
            <img src="logoeco.png" width="30" class="me-2">
            <span class="fw-bold text-success">EcoGuard</span>
        </div>
        <a href="login_petugas.php" class="btn btn-outline-danger btn-sm">Logout</a>
    </nav>

    <div class="container">
        <h3 class="text-success text-center mb-2">Dashboard Petugas</h3>
        <p class="text-center mb-4">Selamat datang, <?= htmlspecialchars($nama_petugas) ?>!</p>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Laporan Pengaduan</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>Pelapor</th>
                                <th>Laporan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $row['tgl_pengaduan'] ?></td>
                                <td><?= htmlspecialchars($row['nama']) ?></td>
                                <td><?= htmlspecialchars($row['isi_laporan']) ?></td>
                                <td>
                                    <span class="badge bg-<?= 
                                        $row['status'] == 'accepted' ? 'success' :
                                        ($row['status'] == 'rejected' ? 'danger' : 'warning') ?>">
                                        <?= ucfirst($row['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($row['status'] == 'pending'): ?>
                                    <a href="?terima=<?= $row['id_pengaduan'] ?>"
                                        class="btn btn-success btn-sm">Terima</a>
                                    <a href="?tolak=<?= $row['id_pengaduan'] ?>"
                                        class="btn btn-warning btn-sm">Tolak</a>
                                    <?php endif; ?>
                                    <a href="?hapus=<?= $row['id_pengaduan'] ?>" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Yakin ingin menghapus laporan ini?')">Hapus</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
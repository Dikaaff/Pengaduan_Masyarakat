<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['id_petugas'])) {
    header('Location: login_petugas.php');
    exit;
}

$id_petugas = $_SESSION['id_petugas'];
$nama_petugas = $_SESSION['nama_petugas'];

// Aksi
if (isset($_GET['terima'])) {
    $id_pengaduan = $_GET['terima'];
    mysqli_query($conn, "UPDATE pengaduan SET status = 'accepted' WHERE id_pengaduan = '$id_pengaduan'");
}
if (isset($_GET['tolak'])) {
    $id_pengaduan = $_GET['tolak'];
    mysqli_query($conn, "UPDATE pengaduan SET status = 'rejected' WHERE id_pengaduan = '$id_pengaduan'");
}
if (isset($_GET['hapus'])) {
    $id_pengaduan = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM pengaduan WHERE id_pengaduan = '$id_pengaduan'");
}

// Data laporan
$query = "SELECT p.*, m.nama FROM pengaduan p 
          JOIN masyarakat m ON p.nik = m.nik 
          ORDER BY p.status, p.tgl_pengaduan DESC";
$result = mysqli_query($conn, $query);

// Statistik
$count_pending = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM pengaduan WHERE status = 'pending'"))['total'];
$count_accepted = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM pengaduan WHERE status = 'accepted'"))['total'];
$count_rejected = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM pengaduan WHERE status = 'rejected'"))['total'];
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Petugas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
    body {
        font-family: 'Poppins', sans-serif;
        background: #ffffff;
        margin: 0;
        padding: 0;
    }

    .sidebar {
        background: #fff;
        width: 240px;
        height: 100vh;
        border-right: 1px solid #ddd;
        position: fixed;
        padding: 20px;
    }

    .sidebar h4 {
        color: #28a745;
        margin-bottom: 30px;
    }

    .sidebar a {
        display: block;
        color: #000;
        font-weight: 500;
        margin-bottom: 15px;
        text-decoration: none;
    }

    .sidebar a.active,
    .sidebar a:hover {
        background-color: #28a745;
        color: #fff;
        padding: 8px;
        border-radius: 8px;
    }

    .main {
        margin-left: 260px;
        padding: 30px;
    }

    .table-responsive {
        border-radius: 12px;
        overflow-x: auto;
    }

    .img-thumb {
        width: 60px;
        border-radius: 8px;
        cursor: pointer;
    }

    .badge {
        font-size: 14px;
        padding: 6px 10px;
    }

    .btn-sm {
        font-size: 13px;
        padding: 5px 10px;
        border-radius: 8px;
    }

    .stat-card {
        border-radius: 12px;
        padding: 15px;
        color: white;
        text-align: center;
    }

    .bg-pending {
        background-color: #ffc107;
        color: #000;
    }

    .bg-accepted {
        background-color: #28a745;
    }

    .bg-rejected {
        background-color: #dc3545;
    }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="d-flex align-items-center mb-4">
            <img src="logoeco.png" width="30" class="me-2">
            <h4 class="mb-0">EcoGuard</h4>
        </div>
        <a href="#" class="active">📖 Data Laporan</a>
        <a href="login_petugas.php" class="btn btn-outline-danger mt-5">Logout</a>
    </div>

    <!-- Main -->
    <div class="main">
        <h4 class="mb-4">Dashboard Petugas > <strong>Data Laporan</strong></h4>
        <p>Selamat datang, <strong><?= htmlspecialchars($nama_petugas) ?></strong></p>

        <!-- Statistik -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="stat-card bg-pending">
                    <h6>🕒 Pending</h6>
                    <h4><?= $count_pending ?></h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card bg-accepted">
                    <h6>✅ Accepted</h6>
                    <h4><?= $count_accepted ?></h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card bg-rejected">
                    <h6>❌ Rejected</h6>
                    <h4><?= $count_rejected ?></h4>
                </div>
            </div>
        </div>

        <!-- Tabel Laporan -->
        <div class="table-responsive mt-4">
            <table class="table table-bordered align-middle">
                <thead class="table-success">
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>Pelapor</th>
                        <th>Laporan</th>
                        <th>Foto</th>
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
                            <?php if ($row['foto']): ?>
                            <a href="<?= $row['foto'] ?>" target="_blank">
                                <img src="<?= $row['foto'] ?>" class="img-thumb mb-1">
                            </a><br>
                            <a href="<?= $row['foto'] ?>" download class="btn btn-outline-primary btn-sm">Download</a>
                            <?php else: ?> -
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge bg-<?= 
                            $row['status'] == 'accepted' ? 'success' :
                            ($row['status'] == 'rejected' ? 'danger' : 'warning text-dark') ?>">
                                <?= ucfirst($row['status']) ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($row['status'] == 'pending'): ?>
                            <a href="?terima=<?= $row['id_pengaduan'] ?>" class="btn btn-success btn-sm mb-1">Terima</a>
                            <a href="?tolak=<?= $row['id_pengaduan'] ?>" class="btn btn-warning btn-sm mb-1">Tolak</a>
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

</body>

</html>
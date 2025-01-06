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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Petugas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background-color: #f8f9fa;">
    <div class="container mt-5">
        <h3 class="text-center text-success">Dashboard Petugas</h3>
        <p class="text-center">Selamat datang, <?= htmlspecialchars($nama_petugas) ?>!</p>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Laporan Pengaduan</h5>
                <table class="table table-bordered">
                    <thead class="table-success">
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
                            <td><?= $row['nama'] ?></td>
                            <td><?= $row['isi_laporan'] ?></td>
                            <td><?= ucfirst($row['status']) ?></td>
                            <td>
                                <?php if ($row['status'] == 'pending'): ?>
                                <a href="?terima=<?= $row['id_pengaduan'] ?>" class="btn btn-success btn-sm">Terima</a>
                                <a href="?tolak=<?= $row['id_pengaduan'] ?>" class="btn btn-warning btn-sm">Tolak</a>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
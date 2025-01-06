<?php
include 'koneksi.php';
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['nik'])) {
    header('Location: login_masyarakat.php');
    exit;
}

// Ambil data masyarakat yang login
$nik = $_SESSION['nik'];
$nama = $_SESSION['nama'];

// Tambah laporan
if (isset($_POST['tambah'])) {
    $isi_laporan = $_POST['isi_laporan'];
    $tgl_pengaduan = date('Y-m-d');

    $query = "INSERT INTO pengaduan (tgl_pengaduan, nik, isi_laporan, status) 
              VALUES ('$tgl_pengaduan', '$nik', '$isi_laporan', 'pending')";
    mysqli_query($conn, $query);

    // Redirect untuk mencegah form resubmission
    header('Location: dashboard_masyarakat.php');
    exit;
}

// Hapus laporan
if (isset($_GET['hapus'])) {
    $id_pengaduan = $_GET['hapus'];
    $query = "DELETE FROM pengaduan WHERE id_pengaduan = '$id_pengaduan' AND nik = '$nik'";
    mysqli_query($conn, $query);

    // Redirect untuk mencegah form resubmission
    header('Location: dashboard_masyarakat.php');
    exit;
}

// Edit laporan
if (isset($_POST['edit'])) {
    $id_pengaduan = $_POST['id_pengaduan'];
    $isi_laporan = $_POST['isi_laporan'];
    $query = "UPDATE pengaduan SET isi_laporan = '$isi_laporan' WHERE id_pengaduan = '$id_pengaduan' AND nik = '$nik'";
    mysqli_query($conn, $query);

    // Redirect untuk mencegah form resubmission
    header('Location: dashboard_masyarakat.php');
    exit;
}

// Ambil laporan masyarakat
$query = "SELECT * FROM pengaduan WHERE nik = '$nik'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Masyarakat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background-color: #f8f9fa;">
    <div class="container mt-5">
        <h3 class="text-center text-success">Dashboard Masyarakat</h3>
        <p class="text-center">Selamat datang, <?= htmlspecialchars($nama) ?>!</p>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Laporan Anda</h5>
                <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#tambahModal">Tambah
                    Laporan</button>
                <table class="table table-bordered">
                    <thead class="table-success">
                        <tr>
                            <th>#</th>
                            <th>Tanggal</th>
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
                            <td><?= $row['isi_laporan'] ?></td>
                            <td><?= ucfirst($row['status']) ?></td>
                            <td>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#editModal<?= $row['id_pengaduan'] ?>">Edit</button>
                                <a href="?hapus=<?= $row['id_pengaduan'] ?>" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin ingin menghapus laporan ini?')">Hapus</a>
                            </td>
                        </tr>

                        <!-- Modal Edit -->
                        <div class="modal fade" id="editModal<?= $row['id_pengaduan'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Laporan</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="id_pengaduan"
                                                value="<?= $row['id_pengaduan'] ?>">
                                            <div class="mb-3">
                                                <label for="isi_laporan" class="form-label">Laporan</label>
                                                <textarea class="form-control" name="isi_laporan"
                                                    required><?= $row['isi_laporan'] ?></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" name="edit" class="btn btn-primary">Simpan
                                                Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div class="modal fade" id="tambahModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Laporan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="isi_laporan" class="form-label">Laporan</label>
                            <textarea class="form-control" name="isi_laporan" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="tambah" class="btn btn-success">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
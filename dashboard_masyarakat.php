<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['nik'])) {
    header('Location: login_masyarakat.php');
    exit;
}

$nik = $_SESSION['nik'];
$nama = $_SESSION['nama'];
$email = $_SESSION['email'] ?? '';

if (isset($_POST['tambah'])) {
    $isi_laporan = $_POST['isi_laporan'];
    $tgl_pengaduan = date('Y-m-d');

    $foto_name = $_FILES['foto']['name'];
    $foto_tmp = $_FILES['foto']['tmp_name'];
    $foto_path = 'uploads/' . time() . '_' . $foto_name;

    if (move_uploaded_file($foto_tmp, $foto_path)) {
        $query = "INSERT INTO pengaduan (tgl_pengaduan, nik, isi_laporan, status, foto) 
                  VALUES ('$tgl_pengaduan', '$nik', '$isi_laporan', 'pending', '$foto_path')";
        mysqli_query($conn, $query);
    }
    header('Location: dashboard_masyarakat.php');
    exit;
}

if (isset($_GET['hapus'])) {
    $id_pengaduan = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM pengaduan WHERE id_pengaduan = '$id_pengaduan' AND nik = '$nik'");
    header('Location: dashboard_masyarakat.php');
    exit;
}

if (isset($_POST['edit'])) {
    $id_pengaduan = $_POST['id_pengaduan'];
    $isi_laporan = $_POST['isi_laporan'];
    mysqli_query($conn, "UPDATE pengaduan SET isi_laporan = '$isi_laporan' WHERE id_pengaduan = '$id_pengaduan' AND nik = '$nik'");
    header('Location: dashboard_masyarakat.php');
    exit;
}

$query = "SELECT * FROM pengaduan WHERE nik = '$nik' ORDER BY tgl_pengaduan DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Masyarakat</title>
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

    .form-section {
        background: #f8f8f8;
        border-radius: 16px;
        padding: 25px;
    }

    .btn-upload {
        background-color: #00cfff;
        color: white;
    }

    .btn-kirim {
        background-color: #28e745;
        color: white;
        padding: 10px 30px;
        border-radius: 20px;
        font-weight: 600;
    }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="d-flex align-items-center mb-4">
            <img src="logoeco.png" width="30" class="me-2">
            <h4 class="mb-0">EcoGuard</h4>
        </div>

        <a href="#buatLaporan" class="active">✏️ Buat Laporan</a>
        <a href="#dataLaporan">📖 Data Laporan</a>
        <a href="login_masyarakat.php" class="btn btn-outline-danger mt-5">Logout</a>
    </div>

    <div class="main">
        <h4 class="mb-4">Dashboard > <strong>Buat Laporan</strong></h4>

        <form method="POST" enctype="multipart/form-data" class="row g-4 form-section" id="buatLaporan">
            <div class="col-md-6">
                <label class="form-label">Nama:</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($nama) ?>" disabled>
                <label class="form-label mt-3">Email:</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($email) ?>" disabled>
                <label class="form-label mt-3">Tanggal Dibuat:</label>
                <input type="text" class="form-control" value="<?= date('Y-m-d') ?>" disabled>
            </div>
            <div class="col-md-6">
                <label class="form-label">Deskripsi & Upload Foto:</label>
                <textarea name="isi_laporan" class="form-control mb-3" rows="8" required></textarea>
                <input type="file" name="foto" class="form-control btn-upload" required>
                <div class="text-end mt-4">
                    <button type="submit" name="tambah" class="btn btn-kirim">Kirim</button>
                </div>
            </div>
        </form>

        <div class="mt-5" id="dataLaporan">
            <h5 class="mb-3">Data Laporan Anda</h5>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-success">
                        <tr>
                            <th>#</th>
                            <th>Tanggal</th>
                            <th>Isi</th>
                            <th>Status</th>
                            <th>Foto</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $row['tgl_pengaduan'] ?></td>
                            <td><?= $row['isi_laporan'] ?></td>
                            <td>
                                <?php
                                    $status = strtolower($row['status']);
                                    $badgeClass = 'bg-secondary'; // default abu

                                    if ($status === 'pending') $badgeClass = 'bg-warning text-dark';
                                    elseif ($status === 'diterima' || $status === 'acc') $badgeClass = 'bg-success';
                                    elseif ($status === 'ditolak' || $status === 'reject') $badgeClass = 'bg-danger';

                                    echo "<span class='badge $badgeClass px-3 py-2'>" . ucfirst($status) . "</span>";
                                    ?>
                            </td>

                            <td>
                                <?php if ($row['foto']): ?>
                                <a href="#" data-bs-toggle="modal"
                                    data-bs-target="#fotoModal<?= $row['id_pengaduan'] ?>">
                                    <img src="<?= $row['foto'] ?>" width="60" style="cursor: zoom-in;">
                                </a><br>
                                <a href="<?= $row['foto'] ?>" download
                                    class="btn btn-sm btn-outline-primary mt-1">Download</a>

                                <!-- Modal Foto -->
                                <div class="modal fade" id="fotoModal<?= $row['id_pengaduan'] ?>" tabindex="-1"
                                    aria-labelledby="fotoModalLabel<?= $row['id_pengaduan'] ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-body p-0">
                                                <img src="<?= $row['foto'] ?>" class="img-fluid w-100">
                                            </div>
                                            <div class="modal-footer">
                                                <a href="<?= $row['foto'] ?>" download
                                                    class="btn btn-success">Download</a>
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php else: ?>
                                -
                                <?php endif; ?>
                            </td>

                            <td>
                                <a href="?hapus=<?= $row['id_pengaduan'] ?>" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin hapus?')">Hapus</a>
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
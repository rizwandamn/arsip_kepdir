<?php
session_start();
require 'db.php'; // Koneksi ke database
require 'session.php';
checkLogin();

// Pastikan admin sudah login
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Query untuk mengambil semua dokumen dari database
$query = "SELECT * FROM dokumen ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-4">Dashboard Admin</h1>

        <!-- Menampilkan pesan keberhasilan -->
        <?php if (isset($_GET['message'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_GET['message']); ?>
            </div>
        <?php endif; ?>

        <!-- Tombol tambah dokumen -->
        <a href="upload_dokumen.php" class="btn btn-primary mb-3">Tambah Dokumen</a>

        <!-- Tabel daftar dokumen -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>No. Surat</th>
                    <th>Tanggal Surat</th>
                    <th>Kategori</th>
                    <th>Jenis</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $row['title']; ?></td>
                        <td><?= $row['no_surat']; ?></td>
                        <td><?= $row['tanggal_surat']; ?></td>
                        <td><?= ucfirst($row['kategori']); ?></td>
                        <td><?= ucfirst($row['jenis']); ?></td>
                        <td>
                            <!-- Tombol aksi -->
                            <a href="preview_dokumen.php?id=<?= $row['id_dokumen']; ?>" class="btn btn-info btn-sm">Preview</a>
                            <a href="download_dokumen.php?id=<?= $row['id_dokumen']; ?>" class="btn btn-success btn-sm">Download</a>
                            <a href="edit_dokumen.php?id=<?= $row['id_dokumen']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete_dokumen.php?id=<?= $row['id_dokumen']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus dokumen ini?');">Hapus</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada dokumen</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Tombol logout -->
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</body>
</html>

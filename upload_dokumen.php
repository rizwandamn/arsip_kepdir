<?php
session_start();
require 'session.php';
require 'db.php'; // Koneksi ke database
checkLogin();

// Pastikan admin sudah login
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Proses pengunggahan dokumen
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $deskripsi = $_POST['deskripsi'];
    $no_surat = $_POST['no_surat'];
    $tanggal_surat = $_POST['tanggal_surat'];
    $kategori = $_POST['kategori'];
    $jenis = $_POST['jenis'];
    $tahun_akademik = $_POST['tahun_akademik'];
    $uploaded_by = $_SESSION['id_user']; // Ambil ID user dari session

    // Debugging untuk menampilkan kategori
    echo "Kategori yang dikirim: " . htmlspecialchars($kategori);

    // Proses upload file
    $file_path = 'uploads/' . basename($_FILES['file']['name']);
    if (move_uploaded_file($_FILES['file']['tmp_name'], $file_path)) {
        // Insert data ke database
        $query = "INSERT INTO dokumen (title, deskripsi, file_path, kategori, jenis, no_surat, tanggal_surat, tahun_akademik, uploaded_by, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'ssssssssi', $title, $deskripsi, $file_path, $kategori, $jenis, $no_surat, $tanggal_surat, $tahun_akademik, $uploaded_by);

        if (mysqli_stmt_execute($stmt)) {
            echo "<div class='alert alert-success'>Dokumen berhasil diunggah!</div>";
        } else {
            echo "<div class='alert alert-danger'>Gagal mengunggah dokumen: " . mysqli_error($conn) . "</div>";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "<div class='alert alert-danger'>Gagal mengunggah file.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Dokumen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-4">Upload Dokumen</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title" class="form-label">Judul</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label for="no_surat" class="form-label">No. Surat</label>
                <input type="text" name="no_surat" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="tanggal_surat" class="form-label">Tanggal Surat</label>
                <input type="date" name="tanggal_surat" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="kategori" class="form-label">Kategori</label>
                <select name="kategori" class="form-select" required>
                    <option value="pendidikan">Pendidikan</option> <!-- Ubah dari pdd menjadi pendidikan -->
                    <option value="penelitian">Penelitian</option>
                    <option value="pengabdian">Pengabdian</option>
                    <option value="lainnya">Lainnya</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="jenis" class="form-label">Jenis Dokumen</label>
                <select name="jenis" class="form-select" required>
                    <option value="surat_keputusan">Surat Keputusan</option>
                    <option value="surat_tugas">Surat Tugas</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="tahun_akademik" class="form-label">Tahun Akademik</label>
                <select name="tahun_akademik" class="form-select" required>
                    <option value="ganjil">Ganjil</option>
                    <option value="genap">Genap</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="file" class="form-label">File Dokumen</label>
                <input type="file" name="file" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Unggah Dokumen</button>
        </form>

        <a href="dashboard_admin.php" class="btn btn-secondary mt-3">Kembali ke Dashboard</a>
    </div>
</body>
</html>

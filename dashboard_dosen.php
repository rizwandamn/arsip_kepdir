<?php
session_start();
require 'db.php'; // Koneksi ke database
require 'session.php';
checkLogin();


// Menampilkan semua error
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Pastikan dosen sudah login
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'dosen') {
    header("Location: login.php");
    exit();
}

// Siapkan variabel filter
$kategori_filter = isset($_POST['kategori']) ? $_POST['kategori'] : '';
$jenis_filter = isset($_POST['jenis']) ? $_POST['jenis'] : '';

// Query untuk mengambil semua dokumen dengan filter
$query = "SELECT * FROM dokumen WHERE 1=1";

// Tambahkan filter kategori jika ada
if ($kategori_filter) {
    $query .= " AND kategori = '$kategori_filter'";
}

// Tambahkan filter jenis jika ada
if ($jenis_filter) {
    $query .= " AND jenis = '$jenis_filter'";
}

$query .= " ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query error: " . mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Dosen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-4">Dashboard Dosen</h1>

        <!-- Form sortir kategori dan jenis surat -->
        <form method="post" action="">
            <div class="row mb-4">
                <div class="col">
                    <select name="kategori" class="form-select">
                        <option value="">Semua</option>
                        <option value="pendidikan" <?= $kategori_filter == 'pendidikan' ? 'selected' : ''; ?>>Pendidikan</option>
                        <option value="penelitian" <?= $kategori_filter == 'penelitian' ? 'selected' : ''; ?>>Penelitian</option>
                        <option value="pengabdian" <?= $kategori_filter == 'pengabdian' ? 'selected' : ''; ?>>Pengabdian</option>
                        <option value="lainnya" <?= $kategori_filter == 'lainnya' ? 'selected' : ''; ?>>Lainnya</option>
                    </select>
                </div>
                <div class="col">
                    <select name="jenis" class="form-select">
                        <option value="">Semua</option>
                        <option value="surat_keputusan" <?= $jenis_filter == 'surat_keputusan' ? 'selected' : ''; ?>>Surat Keputusan</option>
                        <option value="surat_tugas" <?= $jenis_filter == 'surat_tugas' ? 'selected' : ''; ?>>Surat Tugas</option>
                    </select>
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-primary">Sortir</button>
                </div>
            </div>
        </form>

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
                            <!-- Cek apakah dokumen sudah ditandai oleh user -->
                            <?php
                            $userId = $_SESSION['id_user'];
                            $docId = $row['id_dokumen'];

                            $checkQuery = "SELECT * FROM marked_dokumen WHERE id_user = ? AND id_dokumen = ?";
                            $checkStmt = mysqli_prepare($conn, $checkQuery);
                            mysqli_stmt_bind_param($checkStmt, 'ii', $userId, $docId);
                            mysqli_stmt_execute($checkStmt);
                            $checkResult = mysqli_stmt_get_result($checkStmt);

                            $isMarked = mysqli_num_rows($checkResult) > 0; // True jika sudah ditandai
                            ?>

                            <!-- Tambahkan tombol "Tandai/Batal Tandai" -->
                            <a href="mark_dokumen.php?id=<?= $docId; ?>" class="btn btn-secondary btn-sm">
                                <?= $isMarked ? 'Batal Tandai' : 'Tandai'; ?>
                            </a>
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

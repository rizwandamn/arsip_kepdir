<?php
session_start();
require 'db.php'; 
require 'session.php';
checkLogin();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$kategori_filter = isset($_POST['kategori']) ? $_POST['kategori'] : '';
$jenis_filter = isset($_POST['jenis']) ? $_POST['jenis'] : '';
$search_term = isset($_POST['search']) ? $_POST['search'] : '';

$is_searching = !empty($search_term) || !empty($kategori_filter) || !empty($jenis_filter);

$query = "SELECT * FROM dokumen WHERE 1=1";

if ($kategori_filter) {
    $query .= " AND kategori = '$kategori_filter'";
}
if ($jenis_filter) {
    $query .= " AND jenis = '$jenis_filter'";
}
if ($search_term) {
    $query .= " AND (title LIKE '%$search_term%' OR no_surat LIKE '%$search_term%')";
}

$query .= " ORDER BY created_at DESC"; 

$result = mysqli_query($conn, $query);

$unverifiedQuery = "SELECT COUNT(*) as count FROM pengguna WHERE is_verified = 0";
$unverifiedResult = mysqli_query($conn, $unverifiedQuery);
$unverifiedCount = mysqli_fetch_assoc($unverifiedResult)['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="dashboard_admin.css">
</head>
<body>
    <div class="container">
        <h1 class="mt-4">Dashboard Admin</h1>

        <!-- Notifikasi pengguna yang perlu diverifikasi -->
        <?php if ($unverifiedCount > 0): ?>
            <div class="alert alert-warning d-flex justify-content-between align-items-center">
                <span><strong>Pemberitahuan:</strong> Ada <?= $unverifiedCount; ?> pengguna yang perlu diverifikasi.</span>
                <a href="verify.php" class="btn btn-outline-primary btn-sm">Verifikasi Sekarang</a>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['message'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_GET['message']); ?>
            </div>
        <?php endif; ?>

        <form method="post" action="">
            <div class="row mb-4">
                <div class="col">
                    <select name="kategori" class="form-select">
                        <option value="">Semua Kategori</option>
                        <option value="pendidikan" <?= $kategori_filter == 'pendidikan' ? 'selected' : ''; ?>>Pendidikan</option>
                        <option value="penelitian" <?= $kategori_filter == 'penelitian' ? 'selected' : ''; ?>>Penelitian</option>
                        <option value="pengabdian" <?= $kategori_filter == 'pengabdian' ? 'selected' : ''; ?>>Pengabdian</option>
                        <option value="lainnya" <?= $kategori_filter == 'lainnya' ? 'selected' : ''; ?>>Lainnya</option>
                    </select>
                </div>
                <div class="col">
                    <select name="jenis" class="form-select">
                        <option value="">Semua Jenis</option>
                        <option value="surat_keputusan" <?= $jenis_filter == 'surat_keputusan' ? 'selected' : ''; ?>>Surat Keputusan</option>
                        <option value="surat_tugas" <?= $jenis_filter == 'surat_tugas' ? 'selected' : ''; ?>>Surat Tugas</option>
                    </select>
                </div>
                <div class="col">
                    <input type="text" name="search" class="form-control" placeholder="Cari dokumen..." value="<?= htmlspecialchars($search_term); ?>">
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-primary">Cari & Sortir</button>
                </div>
            </div>
        </form>

        <a href="upload_dokumen.php" class="btn btn-primary mb-3">Tambah Dokumen</a>

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
                            <a href="preview_dokumen.php?id=<?= $row['id_dokumen']; ?>&from=admin" class="btn btn-info btn-sm">Preview</a>
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

        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</body>
</html>

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

// Ambil ID dokumen dari parameter GET
$id_dokumen = $_GET['id'];

// Ambil data dokumen dari database
$query = "SELECT * FROM dokumen WHERE id_dokumen = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $id_dokumen);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$document = mysqli_fetch_assoc($result);

// Proses pengeditan dokumen
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'] ?? null;
    $deskripsi = $_POST['deskripsi'] ?? null;
    $no_surat = $_POST['no_surat'] ?? null;
    $tanggal_surat = $_POST['tanggal_surat'] ?? null;
    $kategori = $_POST['kategori'] ?? null;
    $jenis = $_POST['jenis'] ?? null;
    $tahun_akademik = $_POST['tahun_akademik'] ?? null;
    $uploaded_by = $_SESSION['id_user']; // Ambil ID user dari session

    // Proses upload file jika ada file baru yang diunggah
    $file_path = null;
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $file_path = 'uploads/' . basename($_FILES['file']['name']);
        if (!move_uploaded_file($_FILES['file']['tmp_name'], $file_path)) {
            echo "<div class='alert alert-danger'>Gagal mengunggah file baru.</div>";
            $file_path = null; // Jika gagal, set ke null
        }
    }

    // Query untuk memperbarui data dokumen
    $query = "UPDATE dokumen SET title = ?, deskripsi = ?, kategori = ?, jenis = ?, no_surat = ?, tanggal_surat = ?, tahun_akademik = ?, updated_at = NOW() WHERE id_dokumen = ?";
    $stmt = mysqli_prepare($conn, $query);

    // Bind parameter untuk query
    if ($file_path) {
        $query = "UPDATE dokumen SET title = ?, deskripsi = ?, file_path = ?, kategori = ?, jenis = ?, no_surat = ?, tanggal_surat = ?, tahun_akademik = ?, updated_at = NOW() WHERE id_dokumen = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'ssssssssi', $title, $deskripsi, $file_path, $kategori, $jenis, $no_surat, $tanggal_surat, $tahun_akademik, $id_dokumen);
    } else {
        $query = "UPDATE dokumen SET title = ?, deskripsi = ?, kategori = ?, jenis = ?, no_surat = ?, tanggal_surat = ?, tahun_akademik = ?, updated_at = NOW() WHERE id_dokumen = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'sssssssi', $title, $deskripsi, $kategori, $jenis, $no_surat, $tanggal_surat, $tahun_akademik, $id_dokumen);
    }

    if (mysqli_stmt_execute($stmt)) {
        echo "<div class='alert alert-success'>Dokumen berhasil diperbarui!</div>";
    } else {
        echo "<div class='alert alert-danger'>Gagal memperbarui dokumen: " . mysqli_error($conn) . "</div>";
    }

    mysqli_stmt_close($stmt);
}

// Mengisi form dengan data yang sudah ada
$title = $document['title'] ?? '';
$deskripsi = $document['deskripsi'] ?? '';
$no_surat = $document['no_surat'] ?? '';
$tanggal_surat = $document['tanggal_surat'] ?? '';
$kategori = $document['kategori'] ?? '';
$jenis = $document['jenis'] ?? '';
$tahun_akademik_selected = $document['tahun_akademik'] ?? '';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Dokumen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1 class="mt-4">Edit Dokumen</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="title" class="form-label">Judul</label>
            <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" required>
        </div>
        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea class="form-control" id="deskripsi" name="deskripsi" required><?php echo htmlspecialchars($deskripsi); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="no_surat" class="form-label">No Surat</label>
            <input type="text" class="form-control" id="no_surat" name="no_surat" value="<?php echo htmlspecialchars($no_surat); ?>" required>
        </div>
        <div class="mb-3">
            <label for="tanggal_surat" class="form-label">Tanggal Surat</label>
            <input type="date" class="form-control" id="tanggal_surat" name="tanggal_surat" value="<?php echo htmlspecialchars($tanggal_surat); ?>" required>
        </div>
        <div class="mb-3">
            <label for="kategori" class="form-label">Kategori</label>
            <select class="form-select" id="kategori" name="kategori" required>
                <option value="">Pilih Kategori</option>
                <option value="pendidikan" <?php echo ($kategori == 'pendidikan') ? 'selected' : ''; ?>>Pendidikan</option>
                <option value="penelitian" <?php echo ($kategori == 'penelitian') ? 'selected' : ''; ?>>Penelitian</option>
                <option value="pengabdian" <?php echo ($kategori == 'pengabdian') ? 'selected' : ''; ?>>Pengabdian</option>
                <option value="lainnya" <?php echo ($kategori == 'lainnya') ? 'selected' : ''; ?>>Lainnya</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="jenis" class="form-label">Jenis</label>
            <select class="form-select" id="jenis" name="jenis" required>
                <option value="">Pilih Jenis</option>
                <option value="surat_keputusan" <?php echo ($jenis == 'surat_keputusan') ? 'selected' : ''; ?>>Surat Keputusan</option>
                <option value="surat_tugas" <?php echo ($jenis == 'surat_tugas') ? 'selected' : ''; ?>>Surat Tugas</option>
            </select>
        </div>
        <!-- Dropdown Tahun Akademik -->
        <div class="mb-3">
            <label for="tahun_akademik" class="form-label">Tahun Akademik</label>
            <select name="tahun_akademik" class="form-select" required>
                <option value="">Pilih Tahun Akademik</option>
                <?php
                $startYear = 2024;
                $numberOfYears = 7; // Jumlah tahun akademik yang ingin ditampilkan
                for ($i = 0; $i < $numberOfYears; $i++) {
                    $year = $startYear + $i;
                    $ganjil = "Ganjil $year";
                    $genap = "Genap $year";
                    echo "<option value='$ganjil' " . ($tahun_akademik_selected == $ganjil ? 'selected' : '') . ">Ganjil $year</option>";
                    echo "<option value='$genap' " . ($tahun_akademik_selected == $genap ? 'selected' : '') . ">Genap $year</option>";
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="file" class="form-label">File (jika ingin mengganti)</label>
            <input type="file" class="form-control" id="file" name="file">
        </div>
        <div class="d-flex justify-content-between mt-3">
            <button type="submit" class="btn btn-primary mt-3">Perbarui</button>
            <a href="dashboard_admin.php" class="btn btn-secondary mt-3">Kembali</a>
        </div>
    </form>
</div>
</body>
</html>

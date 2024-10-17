<?php
// session_start();
require 'db.php'; // Koneksi ke database
require 'session.php';
// checkLogin() ;

// Ambil ID dokumen dari URL
if (isset($_GET['id'])) {
    $id_dokumen = intval($_GET['id']);
    
    // Ambil data dokumen dari database
    $query = "SELECT * FROM dokumen WHERE id_dokumen = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $id_dokumen);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($result->num_rows > 0) {
        $dokumen = mysqli_fetch_assoc($result);
    } else {
        echo "<div class='alert alert-danger'>Dokumen tidak ditemukan.</div>";
        exit();
    }
    
    mysqli_stmt_close($stmt);
} else {
    echo "<div class='alert alert-danger'>ID dokumen tidak diberikan.</div>";
    exit();
}

// Cek dari mana pengguna datang
$from_page = isset($_GET['from']) ? $_GET['from'] : 'main';

// Tentukan URL kembali sesuai dengan halaman yang diakses
switch ($from_page) {
    case 'dosen':
        $dashboard_url = 'dashboard_dosen.php';
        break;
    case 'admin':
        $dashboard_url = 'dashboard_admin.php';
        break;
    default: // 'main'
        $dashboard_url = 'index.php';
        break;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Dokumen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-4">Preview Dokumen</h1>

        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($dokumen['title']); ?></h5>
                <p class="card-text"><strong>Deskripsi:</strong> <?php echo nl2br(htmlspecialchars($dokumen['deskripsi'])); ?></p>
                <p class="card-text"><strong>No. Surat:</strong> <?php echo htmlspecialchars($dokumen['no_surat']); ?></p>
                <p class="card-text"><strong>Tanggal Surat:</strong> <?php echo htmlspecialchars($dokumen['tanggal_surat']); ?></p>
                <p class="card-text"><strong>Kategori:</strong> <?php echo htmlspecialchars($dokumen['kategori']); ?></p>
                <p class="card-text"><strong>Jenis Dokumen:</strong> <?php echo htmlspecialchars($dokumen['jenis']); ?></p>
                <p class="card-text"><strong>Tahun Akademik:</strong> <?php echo htmlspecialchars($dokumen['tahun_akademik']); ?></p>
                <p class="card-text"><strong>Diunggah oleh:</strong> <?php echo htmlspecialchars($dokumen['uploaded_by']); ?></p>
                <p class="card-text"><strong>Dibuat pada:</strong> <?php echo htmlspecialchars($dokumen['created_at']); ?></p>
                <p class="card-text"><strong>Terakhir diperbarui pada:</strong> <?php echo htmlspecialchars($dokumen['updated_at']); ?></p>
                <a href="<?php echo htmlspecialchars($dokumen['file_path']); ?>" class="btn btn-primary" download>Unduh Dokumen</a>
            </div>
        </div>

        <a href="<?= $dashboard_url; ?>" class="btn btn-secondary mt-3">Kembali ke Dashboard</a>
    </div>
</body>
</html>

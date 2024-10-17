<?php
// Mulai session jika ada pengguna yang login
session_start();
// checkLogin();

// Koneksi ke database
include 'db.php';

// Siapkan variabel untuk filter
$kategori_filter = isset($_POST['kategori']) ? $_POST['kategori'] : '';
$jenis_filter = isset($_POST['jenis']) ? $_POST['jenis'] : '';

// Ambil dokumen terbaru dari database dengan filter
$query = "SELECT * FROM dokumen WHERE 1=1";

// Tambahkan filter kategori jika ada
if ($kategori_filter) {
    $query .= " AND kategori = ?";
}

// Tambahkan filter jenis jika ada
if ($jenis_filter) {
    $query .= " AND jenis = ?";
}

$query .= " ORDER BY created_at DESC LIMIT 5";
$stmt = mysqli_prepare($conn, $query);

// Bind parameter jika ada filter
if ($kategori_filter && $jenis_filter) {
    mysqli_stmt_bind_param($stmt, 'ss', $kategori_filter, $jenis_filter);
} elseif ($kategori_filter) {
    mysqli_stmt_bind_param($stmt, 's', $kategori_filter);
} elseif ($jenis_filter) {
    mysqli_stmt_bind_param($stmt, 's', $jenis_filter);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Check for errors
if (!$result) {
    die('Error: ' . mysqli_error($conn)); // Simple error handling
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arsip Keputusan & Surat Tugas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom style to center the search form */
        .search-container {
            display: flex;
            justify-content: center;
            margin: 30px 0;
        }
    </style>
</head>
<body>
     <!-- Navbar -->
     <nav class="navbar navbar-expand-lg navbar-light bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">PolnepArsipin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION['username'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="registrasi.php">Registrasi</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="container my-5">
        <div class="jumbotron text-center">
            <h1 class="display-4">SELAMAT DATANG di PolnepArsipin</h1>
            <p class="lead">IT'S ALL ABOUT ARCHIVE</p>
        </div>
    </div>

    <!-- Centered Search Form -->
    <div class="search-container">
        <form method="post" action="search.php" class="input-group mb-4">
            <input type="text" name="search" class="form-control" placeholder="Cari dokumen..." required>
            <button class="btn btn-primary" type="submit">Cari</button>
        </form>
    </div>

    <!-- Filter Form -->
    <div class="container mb-4">
        <form method="post" action="">
            <div class="row">
                <div class="col">
                    <select name="kategori" class="form-select">
                        <option value="">Semua</option>
                        <option value="pendidikan">Pendidikan</option>
                        <option value="penelitian">Penelitian</option>
                        <option value="pengabdian">Pengabdian</option>
                    </select>
                </div>
                <div class="col">
                    <select name="jenis" class="form-select">
                        <option value="">Semua</option>
                        <option value="surat_keputusan">Surat Keputusan</option>
                        <option value="surat_tugas">Surat Tugas</option>
                    </select>
                </div>
                <div class="col">
                    <button class="btn btn-primary" type="submit">Sortir</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Dokumen Terbaru -->
    <div class="container">
        <h3>Dokumen Terbaru</h3>
        <div class="row">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($row['title']); ?></h5>
                                <p class="card-text"><?= htmlspecialchars($row['deskripsi']); ?></p>
                                <p><strong>Kategori:</strong> <?= htmlspecialchars($row['kategori']); ?></p>
                                <p><strong>Jenis:</strong> <?= htmlspecialchars($row['jenis']); ?></p>
                                <p><strong>Tanggal:</strong> <?= htmlspecialchars($row['tanggal_surat']); ?></p>
                                  <!-- Tombol Preview dan Download bisa digunakan tanpa login -->
                                   <a href="preview_dokumen.php?id=<?= $row['id_dokumen']; ?>>&from=main" class="btn btn-primary">Preview</a>
                                   <a href="download_dokumen.php?id=<?= $row['id_dokumen']; ?>" class="btn btn-success">Download</a>

                                
                                <!-- Tombol Tandai -->
                                <a href="mark_dokumen.php?id=<?= $row['id_dokumen']; ?>" class="btn btn-warning" 
                                   onclick="return confirmTandai()">Tandai</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Tidak ada dokumen terbaru.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-primary text-center py-4">
        <p>&copy; 2024 Arsip Keputusan & Surat Tugas</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Fungsi untuk memeriksa apakah pengguna sudah login sebelum menandai dokumen
        function confirmTandai() {
            <?php if (!isset($_SESSION['username'])): ?>
                // Jika pengguna belum login, arahkan ke halaman login
                alert("Anda harus login terlebih dahulu untuk menandai dokumen.");
                window.location.href = "login.php";
                return false; // Cegah link untuk langsung menandai
            <?php else: ?>
                return true; // Lanjutkan jika pengguna sudah login
            <?php endif; ?>
        }
    </script>
</body>
</html>
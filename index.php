<?php
// Mulai session jika ada pengguna yang login
session_start();
// checkLogin();

// Koneksi ke database
include 'db.php';

// Ambil dokumen terbaru dari database
$query = "SELECT * FROM dokumen ORDER BY created_at DESC LIMIT 5";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arsip Keputusan & Surat Tugas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
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
                    <li class="nav-item">
                        <a class="nav-link" href="search.php">Cari Dokumen</a>
                    </li>
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

    <!-- Dokumen Terbaru -->
    <div class="container">
        <h3>Dokumen Terbaru</h3>
        <div class="row">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title"><?= $row['title']; ?></h5>
                                <p class="card-text"><?= $row['deskripsi']; ?></p>
                                <p><strong>Kategori:</strong> <?= $row['kategori']; ?></p>
                                <p><strong>Jenis:</strong> <?= $row['jenis']; ?></p>
                                <p><strong>Tanggal:</strong> <?= $row['tanggal_surat']; ?></p>
                                <a href="preview_dokumen.php?id=<?= $row['id_dokumen']; ?>" class="btn btn-primary">Preview</a>
                                <a href="download_dokumen.php?id=<?= $row['id_dokumen']; ?>" class="btn btn-success">Download</a>
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
</body>
</html>

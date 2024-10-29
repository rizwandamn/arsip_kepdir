<?php
session_start();
require 'db.php'; // Koneksi ke database

$searchTerm = '';
$results = [];

// Cek apakah ada kata kunci pencarian
if (isset($_POST['search'])) {
    $searchTerm = trim($_POST['search']);

    // Ambil data dokumen yang sesuai dengan kata kunci pencarian
    $query = "SELECT * FROM dokumen WHERE title LIKE ? OR deskripsi LIKE ?";
    $stmt = mysqli_prepare($conn, $query);
    $likeTerm = "%" . $searchTerm . "%";
    mysqli_stmt_bind_param($stmt, 'ss', $likeTerm, $likeTerm);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Simpan hasil pencarian ke array
    while ($row = mysqli_fetch_assoc($result)) {
        $results[] = $row;
    }

    mysqli_stmt_close($stmt);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pencarian Dokumen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Pencarian Dokumen</h1>

        <form method="post" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari dokumen..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                <button class="btn btn-primary" type="submit">Cari</button>
            </div>
        </form>

        <?php if (!empty($results)): ?>
            <h2>Hasil Pencarian</h2>
            <div class="list-group">
                <?php foreach ($results as $dokumen): ?>
                    <a href="preview_dokumen.php?id=<?php echo $dokumen['id_dokumen']; ?>" class="list-group-item list-group-item-action">
                        <h5 class="mb-1"><?php echo htmlspecialchars($dokumen['title']); ?></h5>
                        <p class="mb-1"><?php echo htmlspecialchars($dokumen['deskripsi']); ?></p>
                        <small><?php echo htmlspecialchars($dokumen['tanggal_surat']); ?></small>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php elseif (isset($_POST['search'])): ?>
            <div class="alert alert-warning">Tidak ada dokumen ditemukan untuk kata kunci "<strong><?php echo htmlspecialchars($searchTerm); ?></strong>".</div>
        <?php endif; ?>
    </div>
</body>
</html>

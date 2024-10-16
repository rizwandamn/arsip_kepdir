<?php
require 'db.php'; // Koneksi ke database
require 'session.php'; // Mengelola sesi pengguna
checkLogin(); // Pastikan pengguna sudah login

// Ambil notifikasi untuk pengguna yang sedang login
$id_user = $_SESSION['id_user'];
$query = "SELECT * FROM notifikasi WHERE user_id = ? ORDER BY created_at DESC";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $id_user);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$notifikasi = [];
while ($row = mysqli_fetch_assoc($result)) {
    $notifikasi[] = $row;
}

mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Notifikasi</title>
</head>
<body>
    <div class="container mt-5">
        <h2>Notifikasi</h2>
        <ul class="list-group">
            <?php if (count($notifikasi) > 0): ?>
                <?php foreach ($notifikasi as $notif): ?>
                    <li class="list-group-item">
                        <?php echo htmlspecialchars($notif['pesan']); ?> 
                        <small class="text-muted"><?php echo htmlspecialchars($notif['created_at']); ?></small>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="list-group-item">Tidak ada notifikasi baru.</li>
            <?php endif; ?>
        </ul>
        <a href="dashboard_admin.php" class="btn btn-primary mt-3">Kembali ke Dashboard</a>
    </div>
</body>
</html>

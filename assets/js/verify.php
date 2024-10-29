<?php
// Tampilkan semua kesalahan untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Cek apakah session sudah dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Sertakan file koneksi database
require 'db.php'; // Pastikan untuk menyesuaikan dengan nama file koneksi Anda

// Inisialisasi variabel pesan
$message = "";

// Proses verifikasi pengguna
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil dan amankan input pengguna
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Query untuk mencari pengguna berdasarkan username
    $sql = "SELECT * FROM pengguna WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        // Cek apakah password sesuai
        if (password_verify($password, $user['password'])) {
            // Update status verifikasi pengguna
            $updateSql = "UPDATE pengguna SET is_verified = 1 WHERE username = '$username'";
            if (mysqli_query($conn, $updateSql)) {
                $message = "Akun berhasil diverifikasi! Silakan informasikan pengguna.";
            } else {
                $message = "Error: " . mysqli_error($conn);
            }
        } else {
            $message = "Password salah! Silakan coba lagi.";
        }
    } else {
        $message = "Pengguna tidak ditemukan!";
    }
}

// Query untuk mengambil semua pengguna yang belum terverifikasi
$unverifiedQuery = "SELECT username FROM pengguna WHERE is_verified = 0";
$unverifiedResult = mysqli_query($conn, $unverifiedQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Akun - Arsip Surat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-4">Verifikasi Akun</h1>

        <!-- Tampilkan pesan jika ada -->
        <?php if ($message): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>

        <h2>Pengguna yang Perlu Diverifikasi</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($unverifiedResult) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($unverifiedResult)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td>
                            <form action="" method="post" class="d-inline">
                                <input type="hidden" name="username" value="<?php echo htmlspecialchars($row['username']); ?>">
                                <input type="password" name="password" placeholder="Password Dosen" required>
                                <button type="submit" class="btn btn-primary btn-sm">Verifikasi</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2" class="text-center">Tidak ada pengguna yang perlu diverifikasi.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <a href="dashboard_admin.php" class="btn btn-secondary mt-3">Kembali ke Dashboard</a>
    </div>
</body>
</html>

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

//Nomor WhatsApp Admin
$admin_whatsapp_number = "62895348381570";

// Proses pendaftaran pengguna
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil dan amankan input pengguna
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role = mysqli_real_escape_string($conn, $_POST['role']); // 'admin' atau 'dosen'
    $whatsapp = mysqli_real_escape_string($conn, $_POST['whatsapp']); // Nomor WhatsApp

    // Cek apakah sudah ada akun admin yang terdaftar
    if ($role === 'admin') {
        $adminCheckQuery = "SELECT COUNT(*) as count FROM pengguna WHERE role = 'admin'";
        $adminCheckResult = mysqli_query($conn, $adminCheckQuery);
        $adminCount = mysqli_fetch_assoc($adminCheckResult)['count'];

        if ($adminCount > 0) {
            $message = "Hanya satu akun admin yang diperbolehkan.";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert pengguna ke database
            $sql = "INSERT INTO pengguna (username, password, role, is_verified, whatsapp) VALUES ('$username', '$hashed_password', '$role', 1, '$whatsapp')";

            if (mysqli_query($conn, $sql)) {
                $message = "Admin berhasil terdaftar!";
            } else {
                $message = "Error: " . mysqli_error($conn);
            }
        }
    } else {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert pengguna ke database, dengan is_verified = 0 untuk pengguna yang membutuhkan verifikasi
        $sql = "INSERT INTO pengguna (username, password, role, is_verified, whatsapp) VALUES ('$username', '$hashed_password', '$role', 0, '$whatsapp')";

        if (mysqli_query($conn, $sql)) {
            $message = "Registrasi berhasil! Silakan hubungi admin melalui WhatsApp untuk verifikasi akun Anda :";
            $message .= "<a href='https://wa.me/$admin_whatsapp_number?text=Halo%20Admin,%20saya%20butuh%20verifikasi%20akun%20saya.' target='_blank'> 0895346381570</a> .";
        } else {
            $message = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Arsip Surat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-4">Registrasi Pengguna</h1>
        <!-- Tampilkan pesan jika ada -->
        <?php if ($message): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>

        <form action="" method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select name="role" class="form-select" required>
                    <option value="admin">Admin</option>
                    <option value="dosen">Dosen</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="whatsapp" class="form-label">Nomor WhatsApp</label>
                <input type="text" name="whatsapp" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Daftar</button>
        </form>
        <a href="login.php" class="btn btn-secondary mt-3">Kembali ke Login</a>
    </div>
</body>
</html>

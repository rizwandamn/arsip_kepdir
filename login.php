<?php
// Mulai session
session_start();

// Sertakan file koneksi database
require_once 'db.php';
require 'session.php';
// Inisialisasi variabel pesan error
$error = "";

// Cek apakah form login sudah di-submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Query untuk mencari user berdasarkan username dan password
    $sql = "SELECT * FROM pengguna WHERE username = '$username' ;
    -- AND password = '$password'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
         // Jika user ditemukan, cek password menggunakan password_verify
         if (password_verify($password, $row['password'])) {
        // Jika user ditemukan, cek rolenya
        $_SESSION['username'] = $row['username'];
        $_SESSION['role'] = $row['role'];
        $_SESSION['id_user'] = $row['id_user'];

        // Redirect berdasarkan role
        if ($row['role'] == 'admin') {
            header("Location: dashboard_admin.php");
        } elseif ($row['role'] == 'dosen') {
            header("Location: dashboard_dosen.php");
        }
        exit();
    } else {
        // Jika password salah
        $error = "Username atau password salah!";
    }
    } else {
        // Jika username tidak ditemukan
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Arsip Surat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <h3 class="text-center mt-5">Login</h3>
                <?php if($error): ?>
                    <div class="alert alert-danger">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                <form method="post" action="login.php">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>

                <!-- Opsi Registrasi -->
                <p class="mt-3 text-center">Belum punya akun? <a href="registrasi.php">Daftar di sini</a></p>
                
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

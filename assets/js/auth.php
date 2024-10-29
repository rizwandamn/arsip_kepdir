<?php
session_start();
require 'db.php'; // Koneksi ke database

// Cek apakah form telah disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Mencari pengguna dalam database
    $query = "SELECT * FROM user WHERE username = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($result->num_rows > 0) {
        $user = mysqli_fetch_assoc($result);

        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            // Set variabel sesi
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Arahkan pengguna ke dashboard berdasarkan role
            if ($user['role'] === 'admin') {
                header("Location: dashboard_admin.php");
            } else {
                header("Location: dashboard_dosen.php");
            }
            exit();
        } else {
            echo "<div class='alert alert-danger'>Password salah.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Pengguna tidak ditemukan.</div>";
    }

    mysqli_stmt_close($stmt);
}
?>

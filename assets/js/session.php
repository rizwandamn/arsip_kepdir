<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Memulai sesi jika belum dimulai
}


// Fungsi untuk memeriksa apakah pengguna sudah login
function checkLogin() {
    // Cek jika variabel sesi 'username' tidak ada
    if (!isset($_SESSION['username'])) {
        // Jika tidak ada, arahkan ke halaman login
        header("Location: login.php");
        exit();
    }
}

// Fungsi untuk mendapatkan informasi pengguna
function getUserInfo() {
    return [
        'id_user' => $_SESSION['id_user'] ?? null,
        'username' => $_SESSION['username'] ?? null,
        'role' => $_SESSION['role'] ?? null,
    ];
}
?>

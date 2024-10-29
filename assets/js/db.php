<?php
$host = 'localhost'; // Nama host
$user = 'root'; // Username MySQL, biasanya 'root' di server lokal
$password = '123'; // Password MySQL, kosongkan jika default untuk Laragon atau XAMPP
$dbname = 'arsi_surat'; // Nama database

// Membuat koneksi
$conn = mysqli_connect($host, $user, $password, $dbname);

// Memeriksa koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>

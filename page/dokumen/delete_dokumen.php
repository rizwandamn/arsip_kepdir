<?php
session_start();
require 'db.php'; // Koneksi ke database
require 'session.php';
checkLogin(); // Memastikan pengguna telah login

// Pastikan admin sudah login
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Cek apakah ID dokumen diberikan
if (isset($_GET['id'])) {
    $id_dokumen = $_GET['id'];

    // Query untuk menghapus dokumen
    $query = "DELETE FROM dokumen WHERE id_dokumen = ?";
    
    // Persiapkan statement
    $stmt = mysqli_prepare($conn, $query);
    
    // Bind parameter
    mysqli_stmt_bind_param($stmt, 'i', $id_dokumen);
    
    // Eksekusi statement
    if (mysqli_stmt_execute($stmt)) {
        // Redirect kembali ke halaman dashboard setelah berhasil
        header("Location: dashboard_admin.php?message=Dokumen berhasil dihapus");
        exit();
    } else {
        // Jika terjadi error saat menghapus
        echo "Error: " . mysqli_error($conn);
    }
    
    // Tutup statement
    mysqli_stmt_close($stmt);
} else {
    echo "ID dokumen tidak ditemukan.";
}

// Tutup koneksi database
mysqli_close($conn);
?>

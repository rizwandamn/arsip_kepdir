<?php
session_start();
require 'db.php'; // Koneksi ke database
require 'session.php'; // Pastikan ini benar
checkLogin(); // Pastikan fungsi ini ada dalam session.php

// Cek apakah pengguna sudah login dan memiliki role dosen
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'dosen') {
    header("Location: login.php");
    exit();
}

// Ambil ID dokumen dari URL
if (isset($_GET['id'])) {
    $id_dokumen = intval($_GET['id']);
    $id_user = $_SESSION['id_user'];

    // Cek apakah dokumen sudah ditandai oleh user tersebut
    $checkQuery = "SELECT * FROM marked_dokumen WHERE id_user = ? AND id_dokumen = ?";
    $stmt = mysqli_prepare($conn, $checkQuery);
    mysqli_stmt_bind_param($stmt, 'ii', $id_user, $id_dokumen);
    mysqli_stmt_execute($stmt);
    $checkResult = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($checkResult) > 0) {
        // Hapus penandaan jika sudah ada
        $deleteQuery = "DELETE FROM marked_dokumen WHERE id_user = ? AND id_dokumen = ?";
        $deleteStmt = mysqli_prepare($conn, $deleteQuery);
        mysqli_stmt_bind_param($deleteStmt, 'ii', $id_user, $id_dokumen);
        mysqli_stmt_execute($deleteStmt);
        mysqli_stmt_close($deleteStmt);
        $message = "Penandaan dokumen telah dihapus.";
    } else {
        // Tambahkan penandaan baru
        $insertQuery = "INSERT INTO marked_dokumen (id_user, id_dokumen) VALUES (?, ?)";
        $insertStmt = mysqli_prepare($conn, $insertQuery);
        mysqli_stmt_bind_param($insertStmt, 'ii', $id_user, $id_dokumen);
        mysqli_stmt_execute($insertStmt);
        mysqli_stmt_close($insertStmt);
        $message = "Dokumen telah ditandai.";
    }

    // Redirect kembali ke halaman sebelumnya atau beranda dengan pesan
    header("Location: dashboard_dosen.php?message=" . urlencode($message));
    exit();
} else {
    echo "<div class='alert alert-danger'>ID dokumen tidak diberikan.</div>";
}
?>

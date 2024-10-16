<?php
// session_start();
require 'db.php'; // Koneksi ke database
require 'session.php';
checkLogin();

// Ambil ID dokumen dari URL
if (isset($_GET['id'])) {
    $id_dokumen = intval($_GET['id']);
    
    // Ambil data dokumen dari database
    $query = "SELECT * FROM dokumen WHERE id_dokumen = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $id_dokumen);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($result->num_rows > 0) {
        $dokumen = mysqli_fetch_assoc($result);
        $file_path = $dokumen['file_path'];
        
        // Cek apakah file ada
        if (file_exists($file_path)) {
            // Set header untuk download
            header('Content-Description: File Transfer');
            header('Content-Type: application/pdf'); // Ganti sesuai tipe file jika perlu
            header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file_path));
            flush(); // Flush sistem output buffer
            readfile($file_path);
            exit();
        } else {
            echo "<div class='alert alert-danger'>File tidak ditemukan.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Dokumen tidak ditemukan.</div>";
    }
    
    mysqli_stmt_close($stmt);
} else {
    echo "<div class='alert alert-danger'>ID dokumen tidak diberikan.</div>";
}
?>

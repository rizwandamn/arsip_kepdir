<?php
require 'db.php'; // Koneksi ke database
require 'session.php'; // Mengelola sesi pengguna
checkLogin(); // Pastikan pengguna sudah login

// Ambil informasi pengguna dari sesi
$userInfo = getUserInfo();
$id_user = $userInfo['id_user'];

// Ambil data pengguna dari database
$query = "SELECT * FROM user WHERE id_user = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $id_user);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result->num_rows > 0) {
    $user = mysqli_fetch_assoc($result);
} else {
    echo "<div class='alert alert-danger'>Pengguna tidak ditemukan.</div>";
    exit();
}

mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Profil Pengguna</title>
</head>
<body>
    <div class="container mt-5">
        <h2>Profil Pengguna</h2>
        <table class="table table-bordered">
            <tr>
                <th>ID Pengguna</th>
                <td><?php echo htmlspecialchars($user['id_user']); ?></td>
            </tr>
            <tr>
                <th>Username</th>
                <td><?php echo htmlspecialchars($user['username']); ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
            </tr>
            <tr>
                <th>Role</th>
                <td><?php echo htmlspecialchars($user['role']); ?></td>
            </tr>
            <tr>
                <th>Tanggal Bergabung</th>
                <td><?php echo htmlspecialchars($user['created_at']); ?></td>
            </tr>
        </table>
        <a href="dashboard_admin.php" class="btn btn-primary">Kembali ke Dashboard</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</body>
</html>

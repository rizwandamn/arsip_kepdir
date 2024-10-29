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

                <form method="post">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="id" name="id" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" id="submit" class="btn btn-primary w-100">Login</button>
                </form>

                <!-- Opsi Registrasi -->
                <p class="mt-3 text-center">Belum punya akun? <a href="registrasi.php">Daftar di sini</a></p>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function meong2(role) {
            if (role == 'mahasiswa') {
                localStorage.clear();
            } else if (role == 'dosen') {
                window.location.href = 'dashboard_dosen.php';
            } else {
                window.location.href = 'dashboard_admin.php';
            }
        }
        if (localStorage.getItem('auth_token') != null) {
            meong2(localStorage.getItem('role'));
        }
        async function meong(id, pw) {
            const response = await fetch('https://apiteam.v-project.my.id/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        'id': id,
                        'password': pw
                    })
                });

                // Mengecek apakah response berhasil
                if (response.ok) {
                    const arr = await response.json();
                    console.log(arr)
                    // Jika ada token dalam data, simpan ke localStorage dan sessionStorage
                    if (arr.data.token) {
                        localStorage.setItem('auth_token', arr.data.token);
                        localStorage.setItem('nama', arr.data.nama);
                        localStorage.setItem('role', arr.data.role);
                        alert('Login berhasil!');
                        meong2(arr.data.token);
                    } else {
                        alert('Login gagal: Token tidak ditemukan.');
                    }
                } else {
                    alert('Login gagal: Terjadi kesalahan.');
                }
        }

        document.getElementById('submit').addEventListener('click', function (e) {
            e.preventDefault();
            let id = document.getElementById('id').value;
            let pw = document.getElementById('password').value;

            meong(id, pw);
        });
    </script>
</body>
</html>

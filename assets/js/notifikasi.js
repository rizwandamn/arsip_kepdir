// notifikasi.js
function fetchNotifikasi() {
    fetch('notifikasi.php')
        .then(response => response.text())
        .then(data => {
            document.getElementById('notifikasi-container').innerHTML = data;
        })
        .catch(error => console.error('Error fetching notifikasi:', error));
}

// Panggil fungsi fetchNotifikasi setiap 5 detik untuk memperbarui notifikasi
setInterval(fetchNotifikasi, 5000);

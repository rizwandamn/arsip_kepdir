async function fetch_login(id, password) {
    try {
        const url = "https://apiteam.v-project.my.id/api/login";
        const response = await fetch(url, {
            method: "POST",
            headers: {
                "content-type" : 'application/json'
            },
            body: JSON.stringify({ id: id, password: password})
        });
        if(!response.ok) {
            throw new Error(`Response status : ${response.status}`);
        }
        const json = await response.json();
        if (json.data.token != null) {
            localStorage.setItem('auth_token', json.data.token);
            localStorage.setItem('nama', json.data.nama);
            localStorage.setItem('role', json.data.role);
            if (localStorage.getItem('role') == 'mahasiswa') {
                localStorage.clear;
                window.location.reload;
            }
            if (localStorage.getItem('role') == 'staff') {
                window.location.href = '/index.html';
            }
            if (localStorage.getItem('role') == 'dosen') {
                window.location.href = '../../page/dashboard_dosen.php';
            }
        }
    } catch (error) {
        console.error(error.message);
    }
}
document.getElementById('submit').addEventListener('click', function (e) {
    e.preventDefault();
    var id = document.getElementById('id').value;
    var password = document.getElementById('password').value;
    fetch_login(id, password);
})

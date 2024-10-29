document.addEventListener('DOMContentLoaded', function (e) {
    const tokens = localStorage.getItem('auth_token');
    fetch_data(tokens);
    const link = document.getElementById('navlink');
    const name = document.getElementById('navuser');
    if (localStorage.getItem('auth_token') != null) {
        const nama = localStorage.getItem('nama');
        link.setAttribute('href', '/page/auth/logout.html');
        link.textContent = 'Logout';
        name.textContent = nama;
    } else {
        link.setAttribute('href', '/page/auth/login.html');
        link.textContent = 'Login';
        name.textContent = '';
    }
    async function fetch_data(token) {
        try {
            const url = "https://apiteam.v-project.my.id/api/arsip/dokumen";
            const response = await fetch(url, {
                method : 'GET',
                headers: {
                    "content-type" : 'application/json',
                    'Authorization': `Bearer ${token}`,
                }
            });
            if(!response.ok) {
                throw new Error(`Response status : ${response.status}`);
            }
            const json = await response.json();

            console.log(json);
            
        } catch (error) {
            console.error(error.message);
        }
    }
})
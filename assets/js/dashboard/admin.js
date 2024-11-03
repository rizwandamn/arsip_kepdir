async function fetch_dash_admin() {
    const token = localStorage.getItem('auth_token');
    const url = "https://apiteam.v-project.my.id/api/arsip/dokumen/saya";
    const response = await fetch(url, {
        method: "GET",
        headers: {
            "content-type" : 'application/json',
            'Authorization': `Bearer ${token}`,
        }
    })
    if(!response.ok) {
        throw new Error(`Response status : ${response.status}`);
    } else {
        const json = await response.json();
        console.log(json);
    }
}

document.addEventListener('DOMContentLoaded', function (e) {
    if (localStorage.getItem('auth_token') != null) {
        fetch_dash_admin();
    } else {
        window.location.href = "/page/auth/login.html"
    }
})
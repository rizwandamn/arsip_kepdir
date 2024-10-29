const token = localStorage.getItem('auth_token');
async function logout() {
    try {
        const url = "https://apiteam.v-project.my.id/api/logout";
        const response = await fetch(url, {
            method: "POST",
            headers: {
                "content-type" : 'application/json',
                'Authorization': `Bearer ${token}`,
            }
        })
        if(!response.ok) {
            throw new Error(`Response status : ${response.status}`);
        }
        localStorage.clear();
        window.location.reload();
        
    } catch (error) {
        console.error(error.message);
    }
}
document.addEventListener('DOMContentLoaded', function (e) {
    if (token) {
        logout();
    } else {
        window.location.href = "/index.html"
    }
})
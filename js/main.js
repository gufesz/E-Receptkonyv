// Authentication check
async function checkAuth() {
    try {
        const response = await fetch('../php/check_session.php');
        const data = await response.json();
        if (!data.loggedIn) window.location.href = '../php/login.php';
    } catch (error) {
        console.error('Auth error:', error);
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    checkAuth();
});
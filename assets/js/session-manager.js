/**
 * Session Management - Frontend
 * Validasi dan pengecekan session di sisi client
 */

// Cek apakah user masih login (check session timeout)
function checkSessionValidity() {
    const sessionData = localStorage.getItem('userSession');
    
    if (!sessionData) {
        // Session tidak ada, redirect ke login
        if (window.location.pathname.includes('admin') || 
            window.location.pathname.includes('user') ||
            window.location.pathname.includes('booking') ||
            window.location.pathname.includes('profile')) {
            // Redirect ke halaman login
            window.location.href = '/layanan_kesehatan/views/auth/login.php';
        }
        return false;
    }
    
    try {
        const session = JSON.parse(sessionData);
        
        // Cek apakah session sudah expired (15 menit = 900000ms)
        const sessionTimeout = 15 * 60 * 1000;
        const now = Date.now();
        const lastActivity = session.lastActivity || now;
        
        if (now - lastActivity > sessionTimeout) {
            // Session expired
            localStorage.removeItem('userSession');
            console.warn('Session expired');
            window.location.href = '/layanan_kesehatan/views/auth/login.php?expired=1';
            return false;
        }
        
        // Update last activity time
        session.lastActivity = now;
        localStorage.setItem('userSession', JSON.stringify(session));
        
        return true;
    } catch (e) {
        console.error('Session parsing error:', e);
        localStorage.removeItem('userSession');
        return false;
    }
}

// Store session info ketika user login
function storeSessionInfo(userData) {
    const sessionInfo = {
        userId: userData.id,
        email: userData.email,
        role: userData.role,
        loginTime: Date.now(),
        lastActivity: Date.now()
    };
    localStorage.setItem('userSession', JSON.stringify(sessionInfo));
}

// Clear session info ketika user logout
function clearSessionInfo() {
    localStorage.removeItem('userSession');
    sessionStorage.clear();
}

// Get current session info
function getSessionInfo() {
    const sessionData = localStorage.getItem('userSession');
    if (sessionData) {
        try {
            return JSON.parse(sessionData);
        } catch (e) {
            return null;
        }
    }
    return null;
}

// Track user activity untuk refresh timeout counter
document.addEventListener('click', function() {
    const session = getSessionInfo();
    if (session) {
        session.lastActivity = Date.now();
        localStorage.setItem('userSession', JSON.stringify(session));
    }
});

document.addEventListener('keypress', function() {
    const session = getSessionInfo();
    if (session) {
        session.lastActivity = Date.now();
        localStorage.setItem('userSession', JSON.stringify(session));
    }
});

// Check session validity setiap 5 detik
setInterval(function() {
    checkSessionValidity();
}, 5000);

// Check session saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    checkSessionValidity();
});

// Warn user sebelum window ditutup jika masih ada aktivitas
window.addEventListener('beforeunload', function(e) {
    const session = getSessionInfo();
    if (session && !window.location.pathname.includes('login')) {
        // Optional: tambah warning
        // e.preventDefault();
        // e.returnValue = '';
    }
});

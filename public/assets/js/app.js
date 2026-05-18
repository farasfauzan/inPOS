// ============================================
// inPOS - Main JavaScript
// ============================================

const API_BASE = '/api';

// Toast notification
function showToast(message, type = 'success') {
    let container = document.querySelector('.toast-container');
    if (!container) {
        container = document.createElement('div');
        container.className = 'toast-container';
        document.body.appendChild(container);
    }
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.innerHTML = `
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            ${type === 'success' ? '<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>' :
              type === 'error' ? '<circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>' :
              '<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>'}
        </svg>
        <span>${message}</span>
    `;
    container.appendChild(toast);
    setTimeout(() => toast.remove(), 4000);
}

// Format currency
function formatCurrency(amount) {
    return 'Rp ' + parseInt(amount || 0).toLocaleString('id-ID');
}

// Format date
function formatDate(dateStr) {
    const date = new Date(dateStr);
    return date.toLocaleDateString('id-ID', {
        day: '2-digit', month: 'short', year: 'numeric',
        hour: '2-digit', minute: '2-digit'
    });
}

// Format short date
function formatShortDate(dateStr) {
    const date = new Date(dateStr);
    return date.toLocaleDateString('id-ID', {
        day: '2-digit', month: 'short', year: 'numeric'
    });
}

// Get session data
async function getSession() {
    try {
        const res = await fetch(`${API_BASE}/auth/session`);
        const data = await res.json();
        return data;
    } catch (e) {
        return { loggedIn: false };
    }
}

// Logout
async function logout() {
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
        await fetch('/logout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });
        window.location.href = '/login';
    } catch (e) {
        window.location.href = '/login';
    }
}

// Check auth and redirect
async function checkAuth() {
    const session = await getSession();
    if (!session.loggedIn) {
        window.location.href = '/login';
        return null;
    }
    return session.user;
}

// Page permissions by role
const pagePermissions = {
    admin: ['dashboard', 'products', 'pos', 'inventory', 'reports'],
    kasir: ['pos']
};

// Protect page - redirect based on role
async function protectPage() {
    const session = await getSession();
    if (!session.loggedIn) {
        window.location.href = '/login';
        return false;
    }

    const currentPage = window.location.pathname.replace('/', '') || 'dashboard';
    const allowedPages = pagePermissions[session.user.role] || [];

    if (!allowedPages.includes(currentPage)) {
        window.location.href = '/pos';
        return false;
    }

    updateUserUI(session.user);
    filterSidebarByRole(session.user.role);
    return true;
}

// Filter sidebar based on role
function filterSidebarByRole(role) {
    const navItems = document.querySelectorAll('.nav-item');
    navItems.forEach(item => {
        const href = item.getAttribute('href')?.replace('/', '');
        const allowedPages = pagePermissions[role] || [];
        if (!allowedPages.includes(href)) {
            item.style.display = 'none';
        } else {
            item.style.display = '';
        }
    });
}

// Update user UI in sidebar
function updateUserUI(user) {
    const avatar = document.querySelector('.user-avatar');
    const nameEl = document.querySelector('.user-name');
    const roleEl = document.querySelector('.user-role');

    if (avatar && user) {
        avatar.textContent = user.fullName.charAt(0).toUpperCase();
    }
    if (nameEl) nameEl.textContent = user.fullName;
    if (roleEl) roleEl.textContent = user.role === 'admin' ? 'Administrator' : 'Kasir';
}

// Active nav item
function setActiveNav(page) {
    document.querySelectorAll('.nav-item').forEach(item => {
        item.classList.remove('active');
        if (item.getAttribute('href')?.includes(page)) {
            item.classList.add('active');
        }
    });
}

// Simple pagination
function paginate(data, page = 1, perPage = 10) {
    const start = (page - 1) * perPage;
    return data.slice(start, start + perPage);
}

// Amount in words (Indonesian)
function angkaTerbilang(angka) {
    const huruf = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas'];
    if (angka < 12) return huruf[angka];
    if (angka < 20) return huruf[Math.floor(angka - 10)] + ' Belas';
    if (angka < 100) return huruf[Math.floor(angka / 10)] + ' Puluh ' + huruf[angka % 10];
    if (angka < 200) return 'Seratus ' + angkaTerbilang(angka - 100);
    if (angka < 1000) return huruf[Math.floor(angka / 100)] + ' Ratus ' + angkaTerbilang(angka % 100);
    if (angka < 2000) return 'Seribu ' + angkaTerbilang(angka - 1000);
    if (angka < 1000000) return angkaTerbilang(Math.floor(angka / 1000)) + ' Ribu ' + angkaTerbilang(angka % 1000);
    if (angka < 1000000000) return angkaTerbilang(Math.floor(angka / 1000000)) + ' Juta ' + angkaTerbilang(angka % 1000000);
    return angka.toString();
}

// Export to CSV
function exportCSV(data, filename, headers) {
    const csvContent = [headers.join(',')];
    data.forEach(row => {
        csvContent.push(row.map(cell => `"${(cell || '').toString().replace(/"/g, '""')}"`).join(','));
    });
    const blob = new Blob([csvContent.join('\n')], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = filename;
    link.click();
}

// Print element
function printElement(elementId) {
    const printContent = document.getElementById(elementId);
    const printWindow = window.open('', '', 'width=300,height=600');
    printWindow.document.write(`
        <html><head><title>Struk</title>
        <style>
            body { font-family: 'Courier New', monospace; padding: 10px; width: 280px; font-size: 12px; }
            h2 { text-align: center; margin-bottom: 5px; }
            p { margin: 3px 0; }
            hr { border: 1px dashed #000; margin: 5px 0; }
            table { width: 100%; border-collapse: collapse; }
            td { padding: 2px 0; }
            .right { text-align: right; }
            .center { text-align: center; }
            .bold { font-weight: bold; }
            @media print { body { margin: 0; } }
        </style>
        </head><body>${printContent.innerHTML}</body></html>
    `);
    printWindow.document.close();
    printWindow.print();
}

// Alert component
function confirmAlert(title, message, onConfirm) {
    const overlay = document.createElement('div');
    overlay.className = 'modal-overlay show';
    overlay.innerHTML = `
        <div class="modal" style="max-width:400px">
            <div class="modal-header">
                <h3>${title}</h3>
            </div>
            <div class="modal-body">
                <p>${message}</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary btn-sm" onclick="this.closest('.modal-overlay').remove()">Batal</button>
                <button class="btn btn-danger btn-sm" id="confirmBtn">Ya, Hapus</button>
            </div>
        </div>
    `;
    document.body.appendChild(overlay);
    overlay.querySelector('#confirmBtn').onclick = () => {
        onConfirm();
        overlay.remove();
    };
    overlay.onclick = (e) => { if (e.target === overlay) overlay.remove(); };
}

// Generate random color for chart
function getRandomColor() {
    const colors = ['#2c7be5', '#28a745', '#ffc107', '#dc3545', '#17a2b8', '#6f42c1', '#e83e8c', '#fd7e14'];
    return colors[Math.floor(Math.random() * colors.length)];
}

// Loading state
function showLoading(el) {
    el.innerHTML = '<tr><td colspan="10" class="text-center text-muted" style="padding:2rem">Memuat data...</td></tr>';
}

function hideLoading(el) {
    // Already handled by replacing innerHTML
}
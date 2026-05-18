<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - inPOS</title>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/style.css')); ?>">
</head>
<body>
    <div class="app-layout">
        <aside class="sidebar">
            <div class="sidebar-logo">in<span>POS</span></div>
            <nav class="sidebar-nav">
                <a href="/dashboard" class="nav-item active">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
                    <span>Dashboard</span>
                </a>
                <a href="/products" class="nav-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
                    <span>Produk</span>
                </a>
                <a href="/pos" class="nav-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
                    <span>Kasir (POS)</span>
                </a>
                <a href="/inventory" class="nav-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    <span>Inventaris</span>
                </a>
                <a href="/reports" class="nav-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                    <span>Laporan</span>
                </a>
            </nav>
            <div class="sidebar-footer">
                <div class="user-info">
                    <div class="user-avatar">A</div>
                    <div class="user-details">
                        <div class="user-name" style="color:white;font-weight:500;">Admin</div>
                        <small class="user-role">Administrator</small>
                    </div>
                </div>
                <button onclick="logout()" class="btn btn-sm" style="background:rgba(255,255,255,0.1);color:white;width:100%;justify-content:center;">
                    Logout
                </button>
            </div>
        </aside>

        <main class="main-content">
            <header class="top-bar">
                <h1 class="page-title">Dashboard</h1>
                <div class="top-bar-actions">
                    <span style="color:var(--text-secondary);font-size:0.9rem;" id="dateDisplay"></span>
                </div>
            </header>

            <div class="page-content">
                <div id="lowStockAlert" class="low-stock-alert" style="display:none;">
                    <h4>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                        Peringatan Stok Minimum
                    </h4>
                    <p id="lowStockCount">0 produk memiliki stok di bawah batas minimum.</p>
                </div>

                <div class="stats-grid" id="statsGrid">
                    <div class="stat-card">
                        <div class="stat-icon blue">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/></svg>
                        </div>
                        <div class="stat-info">
                            <h3 id="statProducts">-</h3>
                            <p>Total Produk</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon green">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                        </div>
                        <div class="stat-info">
                            <h3 id="statTodaySales">-</h3>
                            <p>Penjualan Hari Ini</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon orange">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                        </div>
                        <div class="stat-info">
                            <h3 id="statTodayTrans">-</h3>
                            <p>Transaksi Hari Ini</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon red">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                        </div>
                        <div class="stat-info">
                            <h3 id="statLowStock">-</h3>
                            <p>Stok Minimum Warning</p>
                        </div>
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div class="card">
                        <div class="card-header">
                            <span class="card-title">Transaksi Terakhir</span>
                            <a href="/pos" class="btn btn-sm btn-primary" style="width:auto;padding:0.4rem 0.75rem;" title="Kasir">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
                            </a>
                        </div>
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Kasir</th>
                                        <th>Total</th>
                                        <th>Waktu</th>
                                    </tr>
                                </thead>
                                <tbody id="recentTransTable">
                                    <tr><td colspan="4" class="text-center text-muted" style="padding:2rem;">Memuat...</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <span class="card-title">Statistik Bulan Ini</span>
                            <a href="/reports" class="btn btn-sm btn-secondary">Lihat Laporan</a>
                        </div>
                        <div style="padding:1.5rem;text-align:center;">
                            <h2 id="monthSales" style="font-size:2rem;color:var(--primary);">-</h2>
                            <p style="color:var(--text-secondary);margin-bottom:1rem;">Total Penjualan</p>
                            <hr style="margin:1rem 0;">
                            <div style="display:flex;justify-content:space-around;">
                                <div>
                                    <h3 id="monthTrans" style="color:var(--text-primary);">-</h3>
                                    <small style="color:var(--text-secondary);">Total Transaksi</small>
                                </div>
                                <div>
                                    <h3 id="avgSale" style="color:var(--text-primary);">-</h3>
                                    <small style="color:var(--text-secondary);">Rata-rata</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="<?php echo e(asset('assets/js/app.js')); ?>"></script>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <script>
        const now = new Date();
        document.getElementById('dateDisplay').textContent = now.toLocaleDateString('id-ID', {
            weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
        });

        async function loadDashboard() {
            const authorized = await protectPage();
            if (!authorized) return;

            try {
                const res = await fetch('/api/dashboard/stats');
                if (!res.ok) throw new Error('Failed to load');

                const data = await res.json();

                document.getElementById('statProducts').textContent = data.products;
                document.getElementById('statTodaySales').textContent = formatCurrency(data.today.sales);
                document.getElementById('statTodayTrans').textContent = data.today.transactions;
                document.getElementById('statLowStock').textContent = data.lowStockAlerts;

                document.getElementById('monthSales').textContent = formatCurrency(data.month.sales);
                document.getElementById('monthTrans').textContent = data.month.transactions;
                const avg = data.month.transactions > 0 ? Math.round(data.month.sales / data.month.transactions) : 0;
                document.getElementById('avgSale').textContent = formatCurrency(avg);

                const tbody = document.getElementById('recentTransTable');
                if (data.recentTransactions.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted" style="padding:2rem;">Belum ada transaksi</td></tr>';
                } else {
                    tbody.innerHTML = data.recentTransactions.map(t => `
                        <tr>
                            <td>#${String(t.transactions_id).padStart(4, '0')}</td>
                            <td>${t.kasir_name}</td>
                            <td>${formatCurrency(t.total_amount)}</td>
                            <td>${formatDate(t.transaction_date)}</td>
                        </tr>
                    `).join('');
                }

                if (data.lowStockAlerts > 0) {
                    document.getElementById('lowStockAlert').style.display = 'block';
                    document.getElementById('lowStockCount').textContent =
                        `${data.lowStockAlerts} produk memiliki stok di bawah batas minimum.`;
                }

            } catch (error) {
                console.error('Dashboard load error:', error);
                showToast('Gagal memuat data dashboard', 'error');
            }
        }

        loadDashboard();
    </script>
</body>
</html>
<?php /**PATH C:\Users\akuna\inPOS-Laravel\resources\views/pages/dashboard.blade.php ENDPATH**/ ?>
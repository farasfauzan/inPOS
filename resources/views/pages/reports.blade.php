<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan - inPOS</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>
<body>
    <div class="app-layout">
        <aside class="sidebar">
            <div class="sidebar-logo">in<span>POS</span></div>
            <nav class="sidebar-nav">
                <a href="/dashboard" class="nav-item">
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
                <a href="/reports" class="nav-item active">
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
                <button onclick="logout()" class="btn btn-sm" style="background:rgba(255,255,255,0.1);color:white;width:100%;justify-content:center;">Logout</button>
            </div>
        </aside>

        <main class="main-content">
            <header class="top-bar">
                <h1 class="page-title">Laporan</h1>
                <div class="top-bar-actions">
                    <button onclick="exportSalesReport()" class="btn btn-sm btn-secondary">Export CSV</button>
                </div>
            </header>

            <div class="page-content">
                <!-- Filter -->
                <div class="card" style="margin-bottom:1rem;">
                    <div class="flex gap-1 items-center" style="flex-wrap:wrap;gap:0.75rem;">
                        <div class="form-group" style="margin:0;">
                            <label style="font-size:0.8rem;">Dari Tanggal</label>
                            <input type="date" id="startDate" onchange="loadReports()" style="padding:0.5rem;border:1px solid var(--border);border-radius:4px;">
                        </div>
                        <div class="form-group" style="margin:0;">
                            <label style="font-size:0.8rem;">Sampai Tanggal</label>
                            <input type="date" id="endDate" onchange="loadReports()" style="padding:0.5rem;border:1px solid var(--border);border-radius:4px;">
                        </div>
                        <div class="form-group" style="margin:0;">
                            <label style="font-size:0.8rem;">Filter</label>
                            <select id="reportType" onchange="loadReports()" style="padding:0.5rem;border:1px solid var(--border);border-radius:4px;">
                                <option value="sales">Penjualan</option>
                                <option value="top">Produk Terlaris</option>
                            </select>
                        </div>
                        <button onclick="loadReports()" class="btn btn-primary btn-sm" style="margin-top:1.2rem;padding:0.4rem 1rem;width:auto;">Tampil</button>
                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="stats-grid" id="summaryStats" style="margin-bottom:1.5rem;">
                    <div class="stat-card">
                        <div class="stat-icon green">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                        </div>
                        <div class="stat-info">
                            <h3 id="totalSalesAmount">-</h3>
                            <p>Total Penjualan</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon blue">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                        </div>
                        <div class="stat-info">
                            <h3 id="totalTransactions">-</h3>
                            <p>Jumlah Transaksi</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon orange">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                        </div>
                        <div class="stat-info">
                            <h3 id="avgTransaction">-</h3>
                            <p>Rata-rata Transaksi</p>
                        </div>
                    </div>
                </div>

                <!-- Sales Report Table -->
                <div class="card" id="salesReportCard">
                    <div class="card-header">
                        <span class="card-title">Laporan Penjualan</span>
                    </div>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>ID Transaksi</th>
                                    <th>Kasir</th>
                                    <th>Metode Bayar</th>
                                    <th>Total</th>
                                    <th>Bayar</th>
                                    <th>Kembalian</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody id="salesTable">
                                <tr><td colspan="8" class="text-center text-muted" style="padding:2rem;">Memuat data...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Top Products Table (hidden by default) -->
                <div class="card" id="topProductsCard" style="display:none;">
                    <div class="card-header">
                        <span class="card-title">Produk Terlaris</span>
                    </div>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Produk</th>
                                    <th>Kategori</th>
                                    <th>Terjual</th>
                                    <th>Total Pendapatan</th>
                                </tr>
                            </thead>
                            <tbody id="topProductsTable">
                                <tr><td colspan="5" class="text-center text-muted" style="padding:2rem;">Memuat data...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Daily Chart -->
                <div class="card mt-2">
                    <div class="card-header">
                        <span class="card-title">Grafik Penjualan 30 Hari Terakhir</span>
                    </div>
                    <div id="chartArea" style="height:280px;padding:1rem;">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="{{ asset('assets/js/app.js') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        let currentReportType = 'sales';
        let currentTransactions = [];

        async function initReports() {
            const authorized = await protectPage();
            if (!authorized) return;

            const today = new Date().toISOString().split('T')[0];
            document.getElementById('startDate').value = today;
            document.getElementById('endDate').value = today;

            loadReports();
            loadDailyChart();
        }

        async function loadReports() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            const reportType = document.getElementById('reportType').value;
            currentReportType = reportType;

            if (reportType === 'sales') {
                document.getElementById('salesReportCard').style.display = 'block';
                document.getElementById('topProductsCard').style.display = 'none';
                await loadSalesReport(startDate, endDate);
            } else {
                document.getElementById('salesReportCard').style.display = 'none';
                document.getElementById('topProductsCard').style.display = 'block';
                await loadTopProducts(startDate, endDate);
            }
        }

        async function loadSalesReport(startDate, endDate) {
            try {
                const params = new URLSearchParams();
                if (startDate) params.append('start_date', startDate);
                if (endDate) params.append('end_date', endDate);

                const res = await fetch(`/api/reports/sales?${params}`);
                const data = await res.json();

                currentTransactions = data.transactions || [];

                const total = parseFloat(data.summary?.total_sales || 0);
                const count = data.summary?.total_transactions || 0;
                document.getElementById('totalSalesAmount').textContent = formatCurrency(total);
                document.getElementById('totalTransactions').textContent = count;
                document.getElementById('avgTransaction').textContent = formatCurrency(count > 0 ? Math.round(total / count) : 0);

                const tbody = document.getElementById('salesTable');
                if (currentTransactions.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted" style="padding:2rem;">Tidak ada data transaksi</td></tr>';
                    return;
                }
                tbody.innerHTML = currentTransactions.map((t, i) => `
                    <tr>
                        <td>${i + 1}</td>
                        <td>#${String(t.transactions_id).padStart(4, '0')}</td>
                        <td>${t.kasir_name}</td>
                        <td><span class="badge badge-info">${t.payment_method}</span></td>
                        <td><strong>${formatCurrency(t.total_amount)}</strong></td>
                        <td>${formatCurrency(t.amount_paid)}</td>
                        <td>${formatCurrency(t.change_amount)}</td>
                        <td>${formatDate(t.transaction_date)}</td>
                    </tr>
                `).join('');
            } catch (e) {
                showToast('Gagal memuat laporan penjualan', 'error');
            }
        }

        async function loadTopProducts(startDate, endDate) {
            try {
                const params = new URLSearchParams();
                if (startDate) params.append('start_date', startDate);
                if (endDate) params.append('end_date', endDate);

                const res = await fetch(`/api/reports/top-products?${params}`);
                const products = await res.json();

                const tbody = document.getElementById('topProductsTable');
                if (products.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted" style="padding:2rem;">Tidak ada data</td></tr>';
                    return;
                }
                tbody.innerHTML = products.map((p, i) => `
                    <tr>
                        <td>${i + 1 === 1 ? '1' : i + 1 === 2 ? '2' : i + 1 === 3 ? '3' : i + 1}</td>
                        <td><strong>${p.name}</strong></td>
                        <td>${p.category_name || '-'}</td>
                        <td>${p.total_sold} item</td>
                        <td><strong>${formatCurrency(p.total_revenue)}</strong></td>
                    </tr>
                `).join('');
            } catch (e) {
                showToast('Gagal memuat laporan produk', 'error');
            }
        }

        let salesChart = null;

        async function loadDailyChart() {
            try {
                const res = await fetch('/api/reports/daily');
                const data = await res.json();

                const canvas = document.getElementById('salesChart');
                if (!canvas) return;

                const labels = data.map(d => {
                    const date = new Date(d.date);
                    return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
                });

                const values = data.map(d => parseFloat(d.total_sales || 0));

                if (salesChart) salesChart.destroy();

                salesChart = new Chart(canvas, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Penjualan',
                            data: values,
                            borderColor: '#2c7be5',
                            backgroundColor: 'rgba(44,123,229,0.1)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointBackgroundColor: '#2c7be5',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: (ctx) => formatCurrency(ctx.raw)
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: { color: 'rgba(255,255,255,0.05)' },
                                ticks: { color: 'rgba(255,255,255,0.4)', font: { size: 11 } }
                            },
                            y: {
                                grid: { color: 'rgba(255,255,255,0.05)' },
                                ticks: {
                                    color: 'rgba(255,255,255,0.4)',
                                    font: { size: 11 },
                                    callback: (val) => formatCurrency(val)
                                }
                            }
                        }
                    }
                });
            } catch (e) {
                console.error('Chart error:', e);
            }
        }

        function exportSalesReport() {
            if (currentReportType !== 'sales' || currentTransactions.length === 0) {
                showToast('Tidak ada data untuk di-export', 'warning');
                return;
            }
            const headers = ['ID', 'Kasir', 'Metode Bayar', 'Total', 'Bayar', 'Kembalian', 'Tanggal'];
            const rows = currentTransactions.map(t => [
                `#${String(t.transactions_id).padStart(4, '0')}`,
                t.kasir_name,
                t.payment_method,
                t.total_amount,
                t.amount_paid,
                t.change_amount,
                formatDate(t.transaction_date)
            ]);
            exportCSV(rows, `laporan_penjualan_${new Date().toISOString().split('T')[0]}.csv`, headers);
        }

        initReports();
    </script>
</body>
</html>
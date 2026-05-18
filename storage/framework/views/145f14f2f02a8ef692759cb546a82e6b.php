<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventaris - inPOS</title>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/style.css')); ?>">
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
                <a href="/inventory" class="nav-item active">
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
                <button onclick="logout()" class="btn btn-sm" style="background:rgba(255,255,255,0.1);color:white;width:100%;justify-content:center;">Logout</button>
            </div>
        </aside>

        <main class="main-content">
            <header class="top-bar">
                <h1 class="page-title">Inventaris Stok</h1>
                <div class="top-bar-actions">
                    <button onclick="openRestockModal()" class="btn btn-primary btn-sm">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Tambah Stok
                    </button>
                </div>
            </header>

            <div class="page-content">
                <div id="lowStockAlert" class="low-stock-alert" style="display:none;">
                    <h4>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                        Peringatan Stok Minimum
                    </h4>
                    <div id="lowStockList"></div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <span class="card-title">Daftar Stok</span>
                        <div class="flex gap-1">
                            <select id="stockFilter" onchange="filterStock()" style="padding:0.5rem;border:1px solid var(--border);border-radius:4px;font-size:0.85rem;">
                                <option value="all">Semua</option>
                                <option value="danger">Stok Rendah</option>
                                <option value="warning">Stok Sedang</option>
                                <option value="normal">Normal</option>
                            </select>
                        </div>
                    </div>
                    <div class="search-bar">
                        <input type="text" id="invSearch" placeholder="Cari produk..." onkeyup="filterStock()">
                    </div>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Produk</th>
                                    <th>Kategori</th>
                                    <th>Stok</th>
                                    <th>Min. Stok</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="inventoryTable">
                                <tr><td colspan="7" class="text-center text-muted" style="padding:2rem;">Memuat data...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Restock Modal -->
    <div id="restockModal" class="modal-overlay" onclick="if(event.target===this)closeRestockModal()">
        <div class="modal">
            <div class="modal-header">
                <h3>Tambah Stok</h3>
                <button class="modal-close" onclick="closeRestockModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="restockForm">
                    <div class="form-group">
                        <label>Produk *</label>
                        <select id="restockProduct" required onchange="updateRestockInfo()">
                            <option value="">-- Pilih Produk --</option>
                        </select>
                    </div>
                    <div id="restockInfo" style="background:var(--light);padding:0.75rem;border-radius:4px;margin-bottom:1rem;font-size:0.85rem;">
                        <p>Pilih produk untuk melihat info stok</p>
                    </div>
                    <div class="form-group">
                        <label>Jumlah Tambah *</label>
                        <input type="number" id="restockQty" min="1" required placeholder="Masukkan jumlah">
                    </div>
                    <div class="form-group">
                        <label>Catatan (opsional)</label>
                        <input type="text" id="restockNote" placeholder="Contoh: Pengiriman supplier">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary btn-sm" onclick="closeRestockModal()">Batal</button>
                <button class="btn btn-success btn-sm" onclick="doRestock()">Tambah Stok</button>
            </div>
        </div>
    </div>

    <!-- History Modal -->
    <div id="historyModal" class="modal-overlay" onclick="if(event.target===this)closeHistoryModal()">
        <div class="modal" style="max-width:600px;">
            <div class="modal-header">
                <h3>Riwayat Stok: <span id="historyProductName"></span></h3>
                <button class="modal-close" onclick="closeHistoryModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr><th>Tanggal</th><th>Jenis</th><th>Jumlah</th><th>Stok Sebelum</th><th>Stok Sesudah</th><th>Catatan</th></tr>
                        </thead>
                        <tbody id="historyTable">
                            <tr><td colspan="6" class="text-center text-muted">Memuat...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary btn-sm" onclick="closeHistoryModal()">Tutup</button>
            </div>
        </div>
    </div>

    <script src="<?php echo e(asset('assets/js/app.js')); ?>"></script>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <script>
        let inventory = [];
        let allProducts = [];

        async function loadInventory() {
            const authorized = await protectPage();
            if (!authorized) return;

            try {
                const [invRes, prodRes] = await Promise.all([
                    fetch('/api/inventory'),
                    fetch('/api/products')
                ]);
                inventory = await invRes.json();
                allProducts = await prodRes.json();
                renderInventory(inventory);
                renderLowStockAlert();
            } catch (e) {
                showToast('Gagal memuat data inventaris', 'error');
            }
        }

        function renderInventory(data) {
            const tbody = document.getElementById('inventoryTable');
            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted" style="padding:2rem;">Tidak ada data</td></tr>';
                return;
            }
            tbody.innerHTML = data.map((p, i) => {
                const statusBadge = p.stock_status === 'danger' ? 'badge-danger' :
                                   p.stock_status === 'warning' ? 'badge-warning' : 'badge-success';
                const statusText = p.stock_status === 'danger' ? 'Rendah' :
                                  p.stock_status === 'warning' ? 'Sedang' : 'Normal';
                return `<tr>
                    <td>${i + 1}</td>
                    <td><strong>${p.name}</strong></td>
                    <td>${p.category_name || '-'}</td>
                    <td>${p.stock} ${p.unit || 'pcs'}</td>
                    <td>${p.min_stock}</td>
                    <td><span class="badge ${statusBadge}">${statusText}</span></td>
                    <td>
                        <button class="btn btn-sm btn-success" onclick="openRestockFor(${p.products_id})" title="Tambah Stok">+</button>
                        <button class="btn btn-sm btn-secondary" onclick="showHistory(${p.products_id}, '${p.name.replace(/'/g, "\\'")}')" title="Riwayat">&#128337;</button>
                    </td>
                </tr>`;
            }).join('');
        }

        function renderLowStockAlert() {
            const lowStock = inventory.filter(p => p.stock_status === 'danger');
            if (lowStock.length > 0) {
                document.getElementById('lowStockAlert').style.display = 'block';
                document.getElementById('lowStockList').innerHTML = `<p style="margin:0;">${lowStock.length} produk perlu segera di restock:</p>
                    <ul style="margin:0.5rem 0 0 1.2rem;padding:0;">${lowStock.slice(0, 5).map(p =>
                        `<li>${p.name} (Stok: ${p.stock}, Min: ${p.min_stock})</li>`
                    ).join('')}${lowStock.length > 5 ? '<li>...dan lainnya</li>' : ''}</ul>`;
            }
        }

        function filterStock() {
            const q = document.getElementById('invSearch').value.toLowerCase();
            const filter = document.getElementById('stockFilter').value;
            let filtered = inventory.filter(p => p.name.toLowerCase().includes(q));
            if (filter !== 'all') {
                filtered = filtered.filter(p => p.stock_status === filter);
            }
            renderInventory(filtered);
        }

        function openRestockModal() {
            const select = document.getElementById('restockProduct');
            select.innerHTML = '<option value="">-- Pilih Produk --</option>' +
                allProducts.map(p => `<option value="${p.products_id}">${p.name} (Stok: ${p.stock})</option>`).join('');
            document.getElementById('restockForm').reset();
            document.getElementById('restockInfo').innerHTML = '<p>Pilih produk untuk melihat info stok</p>';
            document.getElementById('restockModal').classList.add('show');
        }

        function closeRestockModal() {
            document.getElementById('restockModal').classList.remove('show');
        }

        function openRestockFor(productId) {
            openRestockModal();
            document.getElementById('restockProduct').value = productId;
            updateRestockInfo();
        }

        function updateRestockInfo() {
            const productId = parseInt(document.getElementById('restockProduct').value);
            const product = allProducts.find(p => p.products_id === productId);
            if (!product) {
                document.getElementById('restockInfo').innerHTML = '<p>Pilih produk untuk melihat info stok</p>';
                return;
            }
            const status = product.stock <= product.min_stock ? 'text-danger' : '';
            document.getElementById('restockInfo').innerHTML = `
                <div style="display:flex;justify-content:space-between;">
                    <div><strong>${product.name}</strong></div>
                    <div class="${status}">Stok: <strong>${product.stock}</strong> ${product.unit || 'pcs'}</div>
                </div>
                <div style="margin-top:4px;color:var(--text-secondary);">Minimum: ${product.min_stock} ${product.unit || 'pcs'}</div>
            `;
        }

        async function doRestock() {
            const productId = parseInt(document.getElementById('restockProduct').value);
            const qty = parseInt(document.getElementById('restockQty').value);
            const note = document.getElementById('restockNote').value;

            if (!productId || !qty) {
                showToast('Produk dan jumlah wajib diisi', 'warning');
                return;
            }

            try {
                const res = await fetch(`/api/inventory/${productId}/restock`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ quantity: qty, note })
                });

                if (res.ok) {
                    showToast('Stok berhasil ditambahkan');
                    closeRestockModal();
                    loadInventory();
                } else {
                    const err = await res.json();
                    showToast(err.error || 'Gagal menambahkan stok', 'error');
                }
            } catch (e) {
                showToast('Gagal menambahkan stok', 'error');
            }
        }

        async function showHistory(productId, productName) {
            document.getElementById('historyProductName').textContent = productName;
            document.getElementById('historyModal').classList.add('show');
            try {
                const res = await fetch(`/api/inventory/${productId}/history`);
                const history = await res.json();
                const tbody = document.getElementById('historyTable');
                if (history.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">Belum ada riwayat</td></tr>';
                } else {
                    tbody.innerHTML = history.map(h => {
                        const typeBadge = h.change_type === 'sale' ? 'badge-danger' :
                                         h.change_type === 'restock' ? 'badge-success' : 'badge-info';
                        return `<tr>
                            <td>${formatDate(h.created_at)}</td>
                            <td><span class="badge ${typeBadge}">${h.change_type}</span></td>
                            <td class="${h.quantity_change < 0 ? 'text-danger' : 'text-success'}">${h.quantity_change > 0 ? '+' : ''}${h.quantity_change}</td>
                            <td>${h.stock_before}</td>
                            <td>${h.stock_after}</td>
                            <td>${h.note || '-'}</td>
                        </tr>`;
                    }).join('');
                }
            } catch (e) {
                showToast('Gagal memuat riwayat', 'error');
            }
        }

        function closeHistoryModal() {
            document.getElementById('historyModal').classList.remove('show');
        }

        loadInventory();
    </script>
</body>
</html>
<?php /**PATH C:\Users\akuna\inPOS-Laravel\resources\views/pages/inventory.blade.php ENDPATH**/ ?>
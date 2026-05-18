<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir (POS) - inPOS</title>
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
                <a href="/pos" class="nav-item active">
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
                <button onclick="logout()" class="btn btn-sm" style="background:rgba(255,255,255,0.1);color:white;width:100%;justify-content:center;">Logout</button>
            </div>
        </aside>

        <main class="main-content">
            <header class="top-bar">
                <h1 class="page-title">Kasir (Point of Sale)</h1>
                <div class="top-bar-actions">
                    <button onclick="clearCart()" class="btn btn-sm btn-secondary" style="width:auto;padding:0.4rem 0.75rem;" title="Kosongkan Keranjang">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
                    </button>
                </div>
            </header>

            <div class="page-content" style="padding:1rem;">
                <div class="pos-layout">
                    <div class="card" style="display:flex;flex-direction:column;overflow:hidden;">
                        <div class="card-header">
                            <span class="card-title">Pilih Produk</span>
                        </div>
                        <div style="padding:0 0 0.75rem 0;">
                            <input type="text" id="posSearch" placeholder="Cari produk..." style="width:100%;padding:0.75rem;border:1px solid var(--border);border-radius:var(--radius);" onkeyup="filterPosProducts()">
                        </div>
                        <div class="product-grid" id="productGrid" style="flex:1;overflow-y:auto;">
                            <div class="text-center text-muted" style="padding:2rem;">Memuat produk...</div>
                        </div>
                    </div>

                    <div class="cart-panel">
                        <div class="cart-header">
                            <span>Keranjang Belanja</span>
                            <span id="cartCount" style="background:var(--primary);color:white;border-radius:50px;padding:2px 8px;font-size:0.8rem;">0</span>
                        </div>
                        <div class="cart-items" id="cartItems">
                            <div class="text-center text-muted" style="padding:2rem;">Keranjang kosong</div>
                        </div>
                        <div class="cart-summary">
                            <div class="cart-total">
                                <span>TOTAL</span>
                                <span class="amount" id="cartTotal">Rp 0</span>
                            </div>

                            <div class="form-group" style="margin-bottom:0.75rem;">
                                <label style="font-size:0.85rem;">Metode Bayar</label>
                                <select id="paymentMethod" style="padding:0.5rem;border:1px solid var(--border);border-radius:4px;width:100%;">
                                    <option value="cash">Tunai</option>
                                    <option value="debit">Debit</option>
                                    <option value="qris">QRIS</option>
                                </select>
                            </div>

                            <div class="form-group" style="margin-bottom:0.75rem;">
                                <label style="font-size:0.85rem;">Jumlah Bayar *</label>
                                <input type="number" id="amountPaid" placeholder="Masukkan jumlah bayar" onkeyup="updateChange()" style="width:100%;padding:0.5rem;border:1px solid var(--border);border-radius:4px;">
                            </div>

                            <div class="cart-total" style="font-size:0.9rem;">
                                <span>Kembalian</span>
                                <span id="cartChange" style="color:var(--success);">Rp 0</span>
                            </div>

                            <div class="cart-actions">
                                <button class="btn btn-secondary" onclick="clearCart()">Reset</button>
                                <button class="btn btn-success" onclick="processTransaction()" id="payBtn">Bayar Sekarang</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="modal-overlay">
        <div class="modal" style="max-width:400px;">
            <div class="modal-header">
                <h3>Transaksi Berhasil</h3>
                <button class="modal-close" onclick="document.getElementById('successModal').classList.remove('show')">&times;</button>
            </div>
            <div class="modal-body" style="text-align:center;">
                <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#28a745" stroke-width="2">
                    <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
                <h2 id="successAmount" style="color:var(--primary);margin:1rem 0;">Rp 0</h2>
                <p style="color:var(--text-secondary);" id="successChange">Kembalian: Rp 0</p>
                <p style="font-size:0.85rem;color:var(--text-secondary);" id="successTransactionId"></p>
            </div>
            <div class="modal-footer" style="justify-content:center;display:flex;gap:0.5rem;">
                <button class="btn btn-secondary" onclick="printLastReceipt()" style="width:120px;padding:0.5rem 1rem;">Cetak Struk</button>
                <button class="btn btn-primary" onclick="closeSuccessModal()" style="width:120px;padding:0.5rem 1rem;">Selesai</button>
            </div>
        </div>
    </div>

    <!-- Receipt Template -->
    <div id="receiptTemplate" style="display:none;">
        <div style="text-align:center;">
            <h2 style="margin:0;">inPOS</h2>
            <p style="margin:2px 0;">Integrated Point of Sale</p>
            <p style="margin:0;font-size:10px;" id="receiptDate"></p>
            <hr style="border:1px dashed #000;margin:5px 0;">
        </div>
        <table style="width:100%;font-size:11px;">
            <thead><tr><th>Produk</th><th>Qty</th><th>Harga</th></tr></thead>
            <tbody id="receiptItems"></tbody>
        </table>
        <hr style="border:1px dashed #000;margin:5px 0;">
        <table style="width:100%;font-size:11px;">
            <tr><td>Total</td><td style="text-align:right;" id="receiptTotal"></td></tr>
            <tr><td>Bayar</td><td style="text-align:right;" id="receiptPaid"></td></tr>
            <tr><td>Kembalian</td><td style="text-align:right;" id="receiptChange"></td></tr>
        </table>
        <hr style="border:1px dashed #000;margin:5px 0;">
        <p style="text-align:center;font-size:10px;margin:0;">-- Terima Kasih --</p>
    </div>

    <script src="<?php echo e(asset('assets/js/app.js')); ?>"></script>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <script>
        let posProducts = [];
        let cart = [];
        let lastTransaction = null;

        async function initPOS() {
            const authorized = await protectPage();
            if (!authorized) return;

            try {
                const res = await fetch('/api/products');
                posProducts = await res.json();
                renderPosProducts(posProducts);
            } catch (e) {
                showToast('Gagal memuat produk', 'error');
            }
        }

        function getProductImage(name) {
            const map = {
                'nasi goreng': 'nasigoreng.jpg',
                'mie goreng': 'mie-goreng.jpg',
                'ayam geprek': 'ayam-geprek.jpg',
                'es teh manis': 'es-teh-manis.jpg',
                'es jeruk': 'es-jeruk.jpg',
                'kopi hitam': 'kopi-hitam.jpg',
                'pisang goreng': 'pisang-goreng.jpg',
                'tahu crispy': 'tahu-crispy.jpg'
            };
            return map[name.toLowerCase()] || null;
        }

        function renderPosProducts(products) {
            const grid = document.getElementById('productGrid');
            if (products.length === 0) {
                grid.innerHTML = '<p class="text-center text-muted" style="padding:2rem;">Tidak ada produk</p>';
                return;
            }
            grid.innerHTML = products.map(p => {
                const disabled = p.stock <= 0 ? 'out-of-stock' : '';
                const img = getProductImage(p.name);
                const imgTag = img ? `<img src="<?php echo e(asset('assets/images/${img}')); ?>" alt="${p.name}">` : `<div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:1.5rem;">🍽️</div>`;
                return `<div class="product-item ${disabled}" onclick="addToCart(${p.products_id})">
                    <div class="product-img">${imgTag}</div>
                    <div class="product-name" title="${p.name}">${p.name}</div>
                    <div class="product-price">${formatCurrency(p.price)}</div>
                    <div class="product-stock">Stok: ${p.stock}</div>
                </div>`;
            }).join('');
        }

        function filterPosProducts() {
            const q = document.getElementById('posSearch').value.toLowerCase();
            const filtered = posProducts.filter(p => p.name.toLowerCase().includes(q));
            renderPosProducts(filtered);
        }

        function addToCart(productId) {
            const product = posProducts.find(p => p.products_id === productId);
            if (!product || product.stock <= 0) return;

            const existing = cart.find(c => c.id === productId);
            if (existing) {
                if (existing.quantity >= product.stock) {
                    showToast('Stok tidak mencukupi', 'warning');
                    return;
                }
                existing.quantity++;
            } else {
                cart.push({
                    id: product.products_id,
                    name: product.name,
                    price: parseInt(product.price),
                    quantity: 1,
                    maxStock: product.stock
                });
            }
            renderCart();
        }

        function changeQty(productId, delta) {
            const item = cart.find(c => c.id === productId);
            if (!item) return;
            item.quantity += delta;
            if (item.quantity <= 0) {
                cart = cart.filter(c => c.id !== productId);
            } else if (item.quantity > item.maxStock) {
                showToast('Stok tidak mencukupi', 'warning');
                item.quantity = item.maxStock;
            }
            renderCart();
        }

        function removeFromCart(productId) {
            cart = cart.filter(c => c.id !== productId);
            renderCart();
        }

        function renderCart() {
            const itemsEl = document.getElementById('cartItems');
            const total = cart.reduce((sum, c) => sum + c.price * c.quantity, 0);

            if (cart.length === 0) {
                itemsEl.innerHTML = '<div class="text-center text-muted" style="padding:2rem;">Keranjang kosong</div>';
                document.getElementById('cartTotal').textContent = 'Rp 0';
                document.getElementById('cartCount').textContent = '0';
                return;
            }

            itemsEl.innerHTML = cart.map(c => `
                <div class="cart-item">
                    <div class="cart-item-info">
                        <div class="cart-item-name">${c.name}</div>
                        <div class="cart-item-price">${formatCurrency(c.price)}</div>
                    </div>
                    <div class="cart-item-qty">
                        <button class="qty-btn" onclick="changeQty(${c.id}, -1)">-</button>
                        <span style="min-width:20px;text-align:center;">${c.quantity}</span>
                        <button class="qty-btn" onclick="changeQty(${c.id}, 1)">+</button>
                    </div>
                    <div class="cart-item-subtotal">${formatCurrency(c.price * c.quantity)}</div>
                    <button onclick="removeFromCart(${c.id})" style="background:none;border:none;color:var(--danger);cursor:pointer;margin-left:0.5rem;font-size:1.1rem;" title="Hapus">&times;</button>
                </div>
            `).join('');

            document.getElementById('cartTotal').textContent = formatCurrency(total);
            document.getElementById('cartCount').textContent = cart.reduce((s, c) => s + c.quantity, 0);
        }

        function updateChange() {
            const total = cart.reduce((sum, c) => sum + c.price * c.quantity, 0);
            const paid = parseInt(document.getElementById('amountPaid').value) || 0;
            const change = Math.max(0, paid - total);
            document.getElementById('cartChange').textContent = formatCurrency(change);
        }

        function clearCart() {
            cart = [];
            renderCart();
            document.getElementById('amountPaid').value = '';
            document.getElementById('paymentMethod').value = 'cash';
            updateChange();
        }

        async function processTransaction() {
            if (cart.length === 0) {
                showToast('Keranjang masih kosong', 'warning');
                return;
            }

            const total = cart.reduce((sum, c) => sum + c.price * c.quantity, 0);
            const paid = parseInt(document.getElementById('amountPaid').value) || 0;
            const paymentMethod = document.getElementById('paymentMethod').value;

            if (paid < total) {
                showToast('Jumlah bayar kurang dari total', 'warning');
                return;
            }

            const btn = document.getElementById('payBtn');
            btn.disabled = true;
            btn.textContent = 'Memproses...';

            try {
                const items = cart.map(c => ({
                    products_id: c.id,
                    quantity: c.quantity
                }));

                const res = await fetch('/api/transactions', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ items, payment_method: paymentMethod, amount_paid: paid })
                });

                const data = await res.json();

                if (res.ok) {
                    lastTransaction = {
                        items: cart,
                        total,
                        paid,
                        change: paid - total,
                        paymentMethod,
                        id: data.transaction.transactions_id
                    };
                    showSuccessModal(data.transaction);
                    clearCart();

                    const prodRes = await fetch('/api/products');
                    posProducts = await prodRes.json();
                    renderPosProducts(posProducts);
                } else {
                    showToast(data.error || 'Transaksi gagal', 'error');
                }
            } catch (e) {
                showToast('Gagal memproses transaksi', 'error');
            } finally {
                btn.disabled = false;
                btn.textContent = 'Bayar Sekarang';
            }
        }

        function showSuccessModal(transaction) {
            document.getElementById('successAmount').textContent = formatCurrency(lastTransaction.total);
            document.getElementById('successChange').textContent = 'Kembalian: ' + formatCurrency(lastTransaction.change);
            document.getElementById('successTransactionId').textContent = `No. Transaksi: #${String(transaction.transactions_id).padStart(4, '0')}`;
            document.getElementById('successModal').classList.add('show');
        }

        function closeSuccessModal() {
            document.getElementById('successModal').classList.remove('show');
        }

        function printLastReceipt() {
            if (!lastTransaction) return;
            document.getElementById('receiptDate').textContent = new Date().toLocaleString('id-ID');
            document.getElementById('receiptItems').innerHTML = lastTransaction.items.map(i =>
                `<tr><td>${i.name}</td><td>${i.quantity}x</td><td>${formatCurrency(i.price * i.quantity)}</td></tr>`
            ).join('');
            document.getElementById('receiptTotal').textContent = formatCurrency(lastTransaction.total);
            document.getElementById('receiptPaid').textContent = formatCurrency(lastTransaction.paid);
            document.getElementById('receiptChange').textContent = formatCurrency(lastTransaction.change);
            printElement('receiptTemplate');
        }

        initPOS();
    </script>
</body>
</html>
<?php /**PATH C:\Users\akuna\inPOS-Laravel\resources\views/pages/pos.blade.php ENDPATH**/ ?>
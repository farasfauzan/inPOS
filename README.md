# inPOS - Integrated Point of Sale

Sistem POS & Inventaris Bahan Baku berbasis web.

## Persiapan

### 1. Install Dependencies
```bash
cd C:\Users\akuna\inPOS
npm install
```

### 2. Setup Database (MySQL via XAMPP)
1. Buka phpMyAdmin (http://localhost/phpmyadmin)
2. Buat database baru atau import file:
   - Klik "Import" → pilih file `database/schema.sql` → Go

### 3. Generate Password Hash (opsional)
```bash
node scripts/genhash.js
```
Copy output hash ke `database/schema.sql` jika ingin mengganti password default.

### 4. Jalankan Server
```bash
node server.js
```
Akses di http://localhost:3000

### 5. Login Default
- **Username:** admin
- **Password:** admin123

## Struktur Direktori

```
inPOS/
├── server.js              # Entry point Express app
├── config/
│   └── db.js              # Koneksi database
├── routes/                # API routes
│   ├── auth.js            # Login/Logout
│   ├── products.js        # CRUD produk
│   ├── transactions.js    # Transaksi POS
│   ├── inventory.js       # Manajemen stok
│   ├── reports.js         # Laporan
│   ├── dashboard.js       # Statistik
│   └── categories.js      # Kategori
├── middleware/
│   └── auth.js            # Auth middleware
├── database/
│   └── schema.sql         # DDL database
├── public/
│   ├── assets/
│   │   ├── css/style.css  # Styling
│   │   └── js/app.js      # Utility JS
│   └── pages/             # HTML pages
│       ├── login.html
│       ├── dashboard.html
│       ├── products.html
│       ├── pos.html
│       ├── inventory.html
│       └── reports.html
└── scripts/
    └── genhash.js         # Password hash generator
```

## Fitur

- **Dashboard:** Statistik penjualan, stok rendah, transaksi terakhir
- **Produk:** CRUD produk dengan kategori
- **Kasir (POS):** Proses transaksi dengan auto-update stok
- **Inventaris:** Monitor stok, restock, riwayat perubahan
- **Laporan:** Laporan penjualan, produk terlaris, grafik harian
- **Auth:** Login dengan role admin/kasir

## Teknologi

- **Backend:** Node.js + Express
- **Database:** MySQL
- **Frontend:** HTML, CSS, Vanilla JS (tanpa framework)
- **Auth:** Session + bcrypt
# Tabel Rencana Pengujian White Box - inPOS (Ringkasan)

---

## Ringkasan

| Modul | Jumlah Test Case |
|-------|------------------|
| Login Page | 2 |
| Dashboard Page | 2 |
| Produk Page | 1 |
| Kasir Page | 3 |
| Inventaris Page | 1 |
| Laporan Page | 3 |
| RBAC | 3 |
| **Total** | **15** |

---

## 1. Login Page

| Kelas Uji | Butir Uji | SKPL/DPPL | DUP L | Tingkat Pengujian | Jenis Pengujian | Jadwal | Penguji |
|-----------|-----------|-----------|-------|------------------|----------------|--------|--------|
| Login Page | Pengujian Proses Login Admin dan Kasir | SKPL-0001 / DPPL-MST-0001 | DUPL-MST-0001 | Pengujian Sistem | White Box | 12/05/2026 | mhmdrifqi2005 |
| Login Page | Pengujian Session dan Logout | SKPL-0002 / DPPL-MST-0002 | DUPL-MST-0002 | Pengujian Sistem | White Box | 12/05/2026 | mhmdrifqi2005 |

---

## 2. Dashboard Page

| Kelas Uji | Butir Uji | SKPL/DPPL | DUP L | Tingkat Pengujian | Jenis Pengujian | Jadwal | Penguji |
|-----------|-----------|-----------|-------|------------------|----------------|--------|--------|
| Dashboard Page | Pengujian Statistik Dashboard (Hari Ini, Bulan Ini, Total Produk) | SKPL-0003 / DPPL-MST-0003 | DUPL-MST-0003 | Pengujian Sistem | White Box | 12/05/2026 | mhmdrifqi2005 |
| Dashboard Page | Pengujian Alert Stok Minimum dan Transaksi Terakhir | SKPL-0004 / DPPL-MST-0004 | DUPL-MST-0004 | Pengujian Sistem | White Box | 12/05/2026 | mhmdrifqi2005 |

---

## 3. Produk Page

| Kelas Uji | Butir Uji | SKPL/DPPL | DUP L | Tingkat Pengujian | Jenis Pengujian | Jadwal | Penguji |
|-----------|-----------|-----------|-------|------------------|----------------|--------|--------|
| Produk Page | Pengujian CRUD Produk (Tambah, Ubah, Hapus, Tampilkan) | SKPL-0005 / DPPL-MST-0005 | DUPL-MST-0005 | Pengujian Sistem | White Box | 12/05/2026 | mhmdrifqi2005 |

---

## 4. Kasir Page

| Kelas Uji | Butir Uji | SKPL/DPPL | DUP L | Tingkat Pengujian | Jenis Pengujian | Jadwal | Penguji |
|-----------|-----------|-----------|-------|------------------|----------------|--------|--------|
| Kasir Page | Pengujian Proses Transaksi (Tambah ke Keranjang, Hitung Total, Bayar, Kembalian) | SKPL-0006 / DPPL-MST-0006 | DUPL-MST-0006 | Pengujian Sistem | White Box | 12/05/2026 | mhmdrifqi2005 |
| Kasir Page | Pengujian Auto-Decrement Stok dan Stock History | SKPL-0007 / DPPL-MST-0007 | DUPL-MST-0007 | Pengujian Sistem | White Box | 12/05/2026 | mhmdrifqi2005 |
| Kasir Page | Pengujian Pembayaran Multi-Method (Cash, Debit, QRIS) | SKPL-0008 / DPPL-MST-0008 | DUPL-MST-0008 | Pengujian Sistem | White Box | 12/05/2026 | mhmdrifqi2005 |

---

## 5. Inventaris Page

| Kelas Uji | Butir Uji | SKPL/DPPL | DUP L | Tingkat Pengujian | Jenis Pengujian | Jadwal | Penguji |
|-----------|-----------|-----------|-------|------------------|----------------|--------|--------|
| Inventaris Page | Pengujian CRUD Stok (Tampilkan, Restok, Riwayat Perubahan) | SKPL-0009 / DPPL-MST-0009 | DUPL-MST-0009 | Pengujian Sistem | White Box | 12/05/2026 | mhmdrifqi2005 |

---

## 6. Laporan Page

| Kelas Uji | Butir Uji | SKPL/DPPL | DUP L | Tingkat Pengujian | Jenis Pengujian | Jadwal | Penguji |
|-----------|-----------|-----------|-------|------------------|----------------|--------|--------|
| Laporan Page | Pengujian Laporan Penjualan (Filter Tanggal, Total Penjualan, Jumlah Transaksi) | SKPL-0010 / DPPL-MST-0010 | DUPL-MST-0010 | Pengujian Sistem | White Box | 12/05/2026 | mhmdrifqi2005 |
| Laporan Page | Pengujian Grafik Penjualan dan Produk Terlaris | SKPL-0011 / DPPL-MST-0011 | DUPL-MST-0011 | Pengujian Sistem | White Box | 12/05/2026 | mhmdrifqi2005 |
| Laporan Page | Pengujian Export Laporan ke CSV | SKPL-0012 / DPPL-MST-0012 | DUPL-MST-0012 | Pengujian Sistem | White Box | 12/05/2026 | mhmdrifqi2005 |

---

## 7. RBAC

| Kelas Uji | Butir Uji | SKPL/DPPL | DUP L | Tingkat Pengujian | Jenis Pengujian | Jadwal | Penguji |
|-----------|-----------|-----------|-------|------------------|----------------|--------|--------|
| RBAC | Pengujian Hak Akses Admin (Akses Semua Halaman) | SKPL-0013 / DPPL-MST-0013 | DUPL-MST-0013 | Pengujian Sistem | White Box | 12/05/2026 | mhmdrifqi2005 |
| RBAC | Pengujian Hak Akses Kasir (Hanya Akses Kasir Page, Ditolak Lainnya) | SKPL-0014 / DPPL-MST-0014 | DUPL-MST-0014 | Pengujian Sistem | White Box | 12/05/2026 | mhmdrifqi2005 |
| RBAC | Pengujian Middleware API (Inventory, Reports, Dashboard) | SKPL-0015 / DPPL-MST-0015 | DUPL-MST-0015 | Pengujian Sistem | White Box | 12/05/2026 | mhmdrifqi2005 |

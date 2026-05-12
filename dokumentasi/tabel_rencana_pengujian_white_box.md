# Tabel Rencana Pengujian White Box - inPOS

---

## 1. Auth Module

| Kelas Uji | Butir Uji | SKPL/DPPL | DUP L | Tingkat Pengujian | Jenis Pengujian | Jadwal | Penguji |
|-----------|-----------|-----------|-------|------------------|----------------|--------|--------|
| Auth Module | Pengujian Query Login dengan data benar | SKPL-0001 / DPPL-MST-0001 | DUPL-MST-0001 | Pengujian Sistem | White Box | 12/05/2026 | mhmdrifqi2005 |
| Auth Module | Pengujian Query Login dengan data salah | SKPL-0002 / DPPL-MST-0002 | DUPL-MST-0002 | Pengujian Sistem | White Box | 12/05/2026 | mhmdrifqi2005 |
| Auth Module | Pengujian Enkripsi Password dengan bcrypt | SKPL-0003 / DPPL-MST-0003 | DUPL-MST-0003 | Pengujian Sistem | White Box | 12/05/2026 | mhmdrifqi2005 |
| Auth Module | Pengujian Session Management | SKPL-0004 / DPPL-MST-0004 | DUPL-MST-0004 | Pengujian Sistem | White Box | 12/05/2026 | mhmdrifqi2005 |
| Auth Module | Pengujian Logout dan Session Destroy | SKPL-0005 / DPPL-MST-0005 | DUPL-MST-0005 | Pengujian Sistem | White Box | 12/05/2026 | mhmdrifqi2005 |

---

## 2. Dashboard Module

| Kelas Uji | Butir Uji | SKPL/DPPL | DUP L | Tingkat Pengujian | Jenis Pengujian | Jadwal | Penguji |
|-----------|-----------|-----------|-------|------------------|----------------|--------|--------|
| Dashboard Module | Pengujian Query Statistik Hari Ini | SKPL-0006 / DPPL-MST-0006 | DUPL-MST-0006 | Pengujian Sistem | White Box | 12/05/2026 | mhmdrifqi2005 |
| Dashboard Module | Pengujian Query Statistik Bulan Ini | SKPL-0007 / DPPL-MST-0007 | DUPL-MST-0007 | Pengujian Sistem | White Box | 12/05/2026 | mhmdrifqi2005 |
| Dashboard Module | Pengujian Query Total Produk | SKPL-0008 / DPPL-MST-0008 | DUPL-MST-0008 | Pengujian Sistem | White Box | 12/05/2026 | mhmdrifqi2005 |
| Dashboard Module | Pengujian Query Transaksi Terakhir | SKPL-0009 / DPPL-MST-0009 | DUPL-MST-0009 | Pengujian Sistem | White Box | 12/05/2026 | mhmdrifqi2005 |
| Dashboard Module | Pengujian Query Alert Stok Minimum | SKPL-0010 / DPPL-MST-0010 | DUPL-MST-0010 | Pengujian Sistem | White Box | 12/05/2026 | mhmdrifqi2005 |

---

## 3. Products Module

| Kelas Uji | Butir Uji | SKPL/DPPL | DUP L | Tingkat Pengujian | Jenis Pengujian | Jadwal | Penguji |
|-----------|-----------|-----------|-------|------------------|----------------|--------|--------|
| Products Module | Pengujian Query SELECT Semua Produk | SKPL-0011 / DPPL-MST-0011 | DUPL-MST-0011 | Pengujian Sistem | White Box | 12/05/2026 | mhmdrifqi2005 |
| Products Module | Pengujian Query SELECT Produk dengan JOIN Kategori | SKPL-0012 / DPPL-MST-0012 | DUPL-MST-0012 | Pengujian Sistem | White Box | 12/05/2026 | mhmdrifqi2005 |
| Products Module | Pengujian Query INSERT Produk Baru | SKPL-0013 / DPPL-MST-0013 | DUPL-MST-0013 | Pengujian Sistem | White Box | 12/05/2026 | mhmdrifqi2005 |
| Products Module | Pengujian Query UPDATE Produk | SKPL-0014 / DPPL-MST-0014 | DUPL-MST-0014 | Pengujian Sistem | White Box | 12/05/2026 | mhmdrifqi2005 |
| Products Module | Pengujian Query DELETE Produk | SKPL-0015 / DPPL-MST-0015 | DUPL-MST-0015 | Pengujian Sistem | White Box | 12/05/2026 | mhmdrifqi2005 |
| Products Module | Pengujian FK Constraint Categories ke Products | SKPL-0016 / DPPL-MST-0016 | DUPL-MST-0016 | Pengujian Sistem | White Box | 12/05/2026 | mhmdrifqi2005 |

---

## 4. Transactions Module

| Kelas Uji | Butir Uji | SKPL/DPPL | DUP L | Tingkat Pengujian | Jenis Pengujian | Jadwal | Penguji |
|-----------|-----------|-----------|-------|------------------|----------------|--------|--------|
| Transactions Module | Pengujian Query INSERT Transaksi Baru | SKPL-0017 / DPPL-MST-0017 | DUPL-MST-0017 | Pengujian Sistem | White Box | 12/05/2026 | mhmdrifqi2005 |
| Transactions Module | Pengujian Query INSERT Transaction Details | SKPL-0018 / DPPL-MST-0018 | DUPL-MST-0018 | Pengujian Sistem | White Box | 12/05/2026 | mhmdrifqi2005 |
| Transactions Module | Pengujian Logic Subtotal (price × quantity) | SKPL-0019 / DPPL-MST-0019 | DUPL-MST-0019 | Pengujian Sistem | White Box | 12/05/2026 | mhmdrifqi2005 |
| Transactions Module | Pengujian Logic Kembalian (amount_paid - total) | SKPL-0020 / DPPL-MST-0020 | DUPL-MST-0020 | Pengujian Sistem | White Box | 12/05/2026 | mhmdrifqi2005 |
| Transactions Module | Pengujian FK Constraint Users ke Transactions | SKPL-0021 / DPPL-MST-0021 | DUPL-MST-0021 | Pengujian Sistem | White Box | 12/05/2026 | mhmdrifqi2005 |
| Transactions Module | Pengujian FK Constraint Products ke Transaction Details | SKPL-0022 / DPPL-MST-0022 | DUPL-MST-0022 | Pengujian Sistem | White Box | 12/05/2026 | mhmdrifqi2005 |

---

## 5. Inventory Module

| Kelas Uji | Butir Uji | SKPL/DPPL | DUP L | Tingkat Pengujian | Jenis Pengujian | Jadwal | Penguji |
|-----------|-----------|-----------|-------|------------------|----------------|--------|--------|
| Inventory Module | Pengujian Query SELECT Stok Produk | SKPL-0023 / DPPL-MST-0023 | DUPL-MST-0023 | Pengujian Sistem | White Box | 12/05/2026 | mhmdrifqi2005 |
| Inventory Module | Pengujian Query UPDATE Stok (Restock) | SKPL-0024 / DPPL-MST-0024 | DUPL-MST-0024 | Pengujian Sistem | White Box | 12/05/2026 | mhmdrifqi2005 |
| Inventory Module | Pengujian Logic Auto-Decrement Stok saat Transaksi | SKPL-0025 / DPPL-MST-0025 | DUPL-MST-0025 | Pengujian Sistem | White Box | 12/05/2026 | mhmdrifqi2005 |
| Inventory Module | Pengujian Query INSERT Stock History | SKPL-0026 / DPPL-MST-0026 | DUPL-MST-0026 | Pengujian Sistem | White Box | 12/05/2026 | mhmdrifqi2005 |
| Inventory Module | Pengujian FK Constraint Products ke Stock History | SKPL-0027 / DPPL-MST-0027 | DUPL-MST-0027 | Pengujian Sistem | White Box | 12/05/2026 | mhmdrifqi2005 |

---

## 6. Reports Module

| Kelas Uji | Butir Uji | SKPL/DPPL | DUP L | Tingkat Pengujian | Jenis Pengujian | Jadwal | Penguji |
|-----------|-----------|-----------|-------|------------------|----------------|--------|--------|
| Reports Module | Pengujian Query Laporan Penjualan dengan Filter Tanggal | SKPL-0028 / DPPL-MST-0028 | DUPL-MST-0028 | Pengujian Sistem | White Box | 12/05/2026 | mhmdrifqi2005 |
| Reports Module | Pengujian Query Laporan Harian 30 Hari | SKPL-0029 / DPPL-MST-0029 | DUPL-MST-0029 | Pengujian Sistem | White Box | 12/05/2026 | mhmdrifqi2005 |
| Reports Module | Pengujian Query Produk Terlaris (Top 10) | SKPL-0030 / DPPL-MST-0030 | DUPL-MST-0030 | Pengujian Sistem | White Box | 12/05/2026 | mhmdrifqi2005 |
| Reports Module | Pengujian Query Aggregate Total Penjualan | SKPL-0031 / DPPL-MST-0031 | DUPL-MST-0031 | Pengujian Sistem | White Box | 12/05/2026 | mhmdrifqi2005 |
| Reports Module | Pengujian Query Aggregate Jumlah Transaksi | SKPL-0032 / DPPL-MST-0032 | DUPL-MST-0032 | Pengujian Sistem | White Box | 12/05/2026 | mhmdrifqi2005 |

---

## 7. RBAC (Role-Based Access Control)

| Kelas Uji | Butir Uji | SKPL/DPPL | DUP L | Tingkat Pengujian | Jenis Pengujian | Jadwal | Penguji |
|-----------|-----------|-----------|-------|------------------|----------------|--------|--------|
| RBAC | Pengujian middleware restrictPage untuk Admin | SKPL-0033 / DPPL-MST-0033 | DUPL-MST-0033 | Pengujian Sistem | White Box | 12/05/2026 | mhmdrifqi2005 |
| RBAC | Pengujian middleware restrictPage untuk Kasir | SKPL-0034 / DPPL-MST-0034 | DUPL-MST-0034 | Pengujian Sistem | White Box | 12/05/2026 | mhmdrifqi2005 |
| RBAC | Pengujian middleware requireRole pada API Inventory | SKPL-0035 / DPPL-MST-0035 | DUPL-MST-0035 | Pengujian Sistem | White Box | 12/05/2026 | mhmdrifqi2005 |
| RBAC | Pengujian middleware requireRole pada API Reports | SKPL-0036 / DPPL-MST-0036 | DUPL-MST-0036 | Pengujian Sistem | White Box | 12/05/2026 | mhmdrifqi2005 |
| RBAC | Pengujian Akses API dengan Session Kasir (return 403) | SKPL-0037 / DPPL-MST-0037 | DUPL-MST-0037 | Pengujian Sistem | White Box | 12/05/2026 | mhmdrifqi2005 |

---

## Ringkasan Jumlah Test Case

| Modul | Jumlah Test Case |
|-------|------------------|
| Auth Module | 5 |
| Dashboard Module | 5 |
| Products Module | 6 |
| Transactions Module | 6 |
| Inventory Module | 5 |
| Reports Module | 5 |
| RBAC | 5 |
| **Total** | **37** |

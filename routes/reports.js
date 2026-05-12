const express = require('express');
const router = express.Router();

module.exports = (db) => {
    // GET /api/reports/sales - Laporan penjualan
    router.get('/sales', async (req, res) => {
        try {
            const { start_date, end_date } = req.query;
            let query = `
                SELECT t.*, u.full_name as kasir_name,
                (SELECT COUNT(*) FROM transaction_details WHERE transactions_id = t.transactions_id) as total_items
                FROM transactions t
                JOIN users u ON t.users_id = u.users_id
                WHERE 1=1
            `;
            const params = [];

            if (start_date) {
                query += ' AND DATE(t.transaction_date) >= ?';
                params.push(start_date);
            }
            if (end_date) {
                query += ' AND DATE(t.transaction_date) <= ?';
                params.push(end_date);
            }

            query += ' ORDER BY t.transaction_date DESC';

            const [transactions] = await db.query(query, params);

            // Hitung total
            const totalSales = transactions.reduce((sum, t) => sum + parseFloat(t.total_amount), 0);

            res.json({
                transactions,
                summary: {
                    total_transactions: transactions.length,
                    total_sales: totalSales
                }
            });
        } catch (error) {
            console.error('Error fetching sales report:', error);
            res.status(500).json({ error: 'Gagal mengambil laporan penjualan' });
        }
    });

    // GET /api/reports/daily - Laporan harian
    router.get('/daily', async (req, res) => {
        try {
            const [daily] = await db.query(`
                SELECT DATE(transaction_date) as date,
                       COUNT(*) as total_transactions,
                       SUM(total_amount) as total_sales
                FROM transactions
                WHERE transaction_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                GROUP BY DATE(transaction_date)
                ORDER BY date ASC
            `);
            res.json(daily);
        } catch (error) {
            console.error('Error fetching daily report:', error);
            res.status(500).json({ error: 'Gagal mengambil laporan harian' });
        }
    });

    // GET /api/reports/stock - Laporan stok
    router.get('/stock', async (req, res) => {
        try {
            const [products] = await db.query(`
                SELECT p.*, c.name as category_name,
                CASE WHEN p.stock <= p.min_stock THEN 'danger'
                     WHEN p.stock <= p.min_stock * 2 THEN 'warning'
                     ELSE 'normal' END as stock_status
                FROM products p
                LEFT JOIN categories c ON p.categories_id = c.categories_id
                ORDER BY p.stock ASC
            `);

            const [categories] = await db.query('SELECT * FROM categories ORDER BY name');

            res.json({ products, categories });
        } catch (error) {
            console.error('Error fetching stock report:', error);
            res.status(500).json({ error: 'Gagal mengambil laporan stok' });
        }
    });

    // GET /api/reports/top-products - Produk terlaris
    router.get('/top-products', async (req, res) => {
        try {
            const { start_date, end_date } = req.query;
            let query = `
                SELECT p.name, c.name as category_name,
                       SUM(td.quantity) as total_sold,
                       SUM(td.subtotal) as total_revenue
                FROM transaction_details td
                JOIN products p ON td.products_id = p.products_id
                LEFT JOIN categories c ON p.categories_id = c.categories_id
                JOIN transactions t ON td.transactions_id = t.transactions_id
                WHERE 1=1
            `;
            const params = [];

            if (start_date) {
                query += ' AND DATE(t.transaction_date) >= ?';
                params.push(start_date);
            }
            if (end_date) {
                query += ' AND DATE(t.transaction_date) <= ?';
                params.push(end_date);
            }

            query += ' GROUP BY p.products_id ORDER BY total_sold DESC LIMIT 10';

            const [products] = await db.query(query, params);
            res.json(products);
        } catch (error) {
            console.error('Error fetching top products:', error);
            res.status(500).json({ error: 'Gagal mengambil laporan' });
        }
    });

    return router;
};

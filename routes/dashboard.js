const express = require('express');
const router = express.Router();

module.exports = (db) => {
    // GET /api/dashboard/stats - Statistik untuk dashboard
    router.get('/stats', async (req, res) => {
        try {
            const [todayTrans] = await db.query(`
                SELECT COUNT(*) as count, COALESCE(SUM(total_amount), 0) as total
                FROM transactions
                WHERE DATE(transaction_date) = CURDATE()
            `);

            const [monthTrans] = await db.query(`
                SELECT COUNT(*) as count, COALESCE(SUM(total_amount), 0) as total
                FROM transactions
                WHERE MONTH(transaction_date) = MONTH(NOW()) AND YEAR(transaction_date) = YEAR(NOW())
            `);

            const [totalProducts] = await db.query('SELECT COUNT(*) as count FROM products');

            const [lowStock] = await db.query('SELECT COUNT(*) as count FROM products WHERE stock <= min_stock');

            const [recentTrans] = await db.query(`
                SELECT t.*, u.full_name as kasir_name
                FROM transactions t
                JOIN users u ON t.user_id = u.id
                ORDER BY t.transaction_date DESC
                LIMIT 5
            `);

            res.json({
                today: {
                    transactions: todayTrans[0].count,
                    sales: parseFloat(todayTrans[0].total)
                },
                month: {
                    transactions: monthTrans[0].count,
                    sales: parseFloat(monthTrans[0].total)
                },
                products: totalProducts[0].count,
                lowStockAlerts: lowStock[0].count,
                recentTransactions: recentTrans
            });
        } catch (error) {
            console.error('Error fetching dashboard stats:', error);
            res.status(500).json({ error: 'Gagal mengambil statistik' });
        }
    });

    return router;
};
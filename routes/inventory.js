const express = require('express');
const router = express.Router();

module.exports = (db) => {
    // GET /api/inventory - Ambil semua data inventaris (stok)
    router.get('/', async (req, res) => {
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
            res.json(products);
        } catch (error) {
            console.error('Error fetching inventory:', error);
            res.status(500).json({ error: 'Gagal mengambil data inventaris' });
        }
    });

    // GET /api/inventory/alerts - Ambil peringatan stok minimum
    router.get('/alerts', async (req, res) => {
        try {
            const [products] = await db.query(`
                SELECT p.*, c.name as category_name
                FROM products p
                LEFT JOIN categories c ON p.categories_id = c.categories_id
                WHERE p.stock <= p.min_stock
                ORDER BY p.stock ASC
            `);
            res.json(products);
        } catch (error) {
            console.error('Error fetching alerts:', error);
            res.status(500).json({ error: 'Gagal mengambil data peringatan' });
        }
    });

    // PUT /api/inventory/:id/restock - Tambah stok (restock/manual)
    router.put('/:id/restock', async (req, res) => {
        const connection = await db.getConnection();
        try {
            await connection.beginTransaction();

            const { quantity, note } = req.body;
            if (!quantity || quantity <= 0) {
                await connection.rollback();
                return res.status(400).json({ error: 'Jumlah harus lebih dari 0' });
            }

            const [products] = await connection.query('SELECT * FROM products WHERE products_id = ?', [req.params.id]);
            if (products.length === 0) {
                await connection.rollback();
                return res.status(404).json({ error: 'Produk tidak ditemukan' });
            }

            const product = products[0];
            const stockBefore = product.stock;
            const stockAfter = stockBefore + parseInt(quantity);

            await connection.query('UPDATE products SET stock = ? WHERE products_id = ?', [stockAfter, req.params.id]);

            await connection.query(
                `INSERT INTO stock_history (products_id, change_type, quantity_change, stock_before, stock_after, note)
                 VALUES (?, 'restock', ?, ?, ?, ?)`,
                [req.params.id, parseInt(quantity), stockBefore, stockAfter, note || 'Restock']
            );

            await connection.commit();

            const [updated] = await connection.query('SELECT * FROM products WHERE products_id = ?', [req.params.id]);
            res.json({ message: 'Stok berhasil ditambahkan', product: updated[0] });
        } catch (error) {
            await connection.rollback();
            console.error('Error restocking:', error);
            res.status(500).json({ error: 'Gagal menambahkan stok' });
        } finally {
            connection.release();
        }
    });

    // GET /api/inventory/:id/history - Riwayat perubahan stok
    router.get('/:id/history', async (req, res) => {
        try {
            const [history] = await db.query(`
                SELECT * FROM stock_history
                WHERE products_id = ?
                ORDER BY created_at DESC
                LIMIT 50
            `, [req.params.id]);
            res.json(history);
        } catch (error) {
            console.error('Error fetching stock history:', error);
            res.status(500).json({ error: 'Gagal mengambil riwayat stok' });
        }
    });

    return router;
};

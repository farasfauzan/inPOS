const express = require('express');
const router = express.Router();

module.exports = (db) => {
    // GET /api/transactions - Ambil semua transaksi
    router.get('/', async (req, res) => {
        try {
            const [transactions] = await db.query(`
                SELECT t.*, u.full_name as kasir_name
                FROM transactions t
                JOIN users u ON t.user_id = u.id
                ORDER BY t.transaction_date DESC
            `);
            res.json(transactions);
        } catch (error) {
            console.error('Error fetching transactions:', error);
            res.status(500).json({ error: 'Gagal mengambil data transaksi' });
        }
    });

    // GET /api/transactions/:id - Ambil transaksi dengan detail
    router.get('/:id', async (req, res) => {
        try {
            const [transactions] = await db.query(
                'SELECT t.*, u.full_name as kasir_name FROM transactions t JOIN users u ON t.user_id = u.id WHERE t.id = ?',
                [req.params.id]
            );
            if (transactions.length === 0) {
                return res.status(404).json({ error: 'Transaksi tidak ditemukan' });
            }

            const [details] = await db.query(`
                SELECT td.*, p.name as product_name
                FROM transaction_details td
                JOIN products p ON td.product_id = p.id
                WHERE td.transaction_id = ?
            `, [req.params.id]);

            res.json({ ...transactions[0], details });
        } catch (error) {
            console.error('Error fetching transaction:', error);
            res.status(500).json({ error: 'Gagal mengambil data transaksi' });
        }
    });

    // POST /api/transactions - Proses transaksi baru (dengan auto-update stok)
    router.post('/', async (req, res) => {
        const connection = await db.getConnection();
        try {
            await connection.beginTransaction();

            const { items, payment_method, amount_paid } = req.body;
            const userId = req.session.userId;

            if (!items || items.length === 0) {
                await connection.rollback();
                return res.status(400).json({ error: 'Item transaksi wajib ada' });
            }

            let totalAmount = 0;
            const processedItems = [];

            // Validasi stok setiap item
            for (const item of items) {
                const [products] = await connection.query(
                    'SELECT * FROM products WHERE id = ? FOR UPDATE',
                    [item.product_id]
                );

                if (products.length === 0) {
                    await connection.rollback();
                    return res.status(404).json({ error: `Produk ID ${item.product_id} tidak ditemukan` });
                }

                const product = products[0];
                if (product.stock < item.quantity) {
                    await connection.rollback();
                    return res.status(400).json({
                        error: `Stok tidak mencukupi untuk ${product.name}. Stok tersedia: ${product.stock}`
                    });
                }

                const subtotal = product.price * item.quantity;
                totalAmount += subtotal;
                processedItems.push({
                    product_id: item.product_id,
                    quantity: item.quantity,
                    price: product.price,
                    subtotal: subtotal
                });
            }

            const paid = parseFloat(amount_paid) || 0;
            const change = paid - totalAmount;

            if (paid < totalAmount) {
                await connection.rollback();
                return res.status(400).json({ error: 'Jumlah pembayaran kurang dari total' });
            }

            // Insert transaksi
            const [transResult] = await connection.query(
                `INSERT INTO transactions (user_id, total_amount, payment_method, amount_paid, change_amount)
                 VALUES (?, ?, ?, ?, ?)`,
                [userId, totalAmount, payment_method || 'cash', paid, change]
            );
            const transactionId = transResult.insertId;

            // Insert detail & update stok
            for (const item of processedItems) {
                await connection.query(
                    'INSERT INTO transaction_details (transaction_id, product_id, quantity, price, subtotal) VALUES (?, ?, ?, ?, ?)',
                    [transactionId, item.product_id, item.quantity, item.price, item.subtotal]
                );

                const [product] = await connection.query('SELECT stock FROM products WHERE id = ?', [item.product_id]);
                const stockBefore = product[0].stock;
                const stockAfter = stockBefore - item.quantity;

                await connection.query('UPDATE products SET stock = ? WHERE id = ?', [stockAfter, item.product_id]);

                await connection.query(
                    `INSERT INTO stock_history (product_id, change_type, quantity_change, stock_before, stock_after, reference_id)
                     VALUES (?, 'sale', ?, ?, ?, ?)`,
                    [item.product_id, -item.quantity, stockBefore, stockAfter, transactionId]
                );
            }

            await connection.commit();

            const [transaction] = await connection.query(`
                SELECT t.*, u.full_name as kasir_name
                FROM transactions t
                JOIN users u ON t.user_id = u.id
                WHERE t.id = ?
            `, [transactionId]);

            const [details] = await connection.query(`
                SELECT td.*, p.name as product_name
                FROM transaction_details td
                JOIN products p ON td.product_id = p.id
                WHERE td.transaction_id = ?
            `, [transactionId]);

            res.status(201).json({
                message: 'Transaksi berhasil',
                transaction: { ...transaction[0], details }
            });
        } catch (error) {
            await connection.rollback();
            console.error('Error processing transaction:', error);
            res.status(500).json({ error: 'Gagal memproses transaksi' });
        } finally {
            connection.release();
        }
    });

    return router;
};
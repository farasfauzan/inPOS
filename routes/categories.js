const express = require('express');
const router = express.Router();

module.exports = (db) => {
    // GET /api/categories
    router.get('/', async (req, res) => {
        try {
            const [categories] = await db.query('SELECT * FROM categories ORDER BY name');
            res.json(categories);
        } catch (error) {
            console.error('Error fetching categories:', error);
            res.status(500).json({ error: 'Gagal mengambil data kategori' });
        }
    });

    // POST /api/categories
    router.post('/', async (req, res) => {
        try {
            const { name, description } = req.body;
            if (!name) {
                return res.status(400).json({ error: 'Nama kategori wajib diisi' });
            }
            const [result] = await db.query(
                'INSERT INTO categories (name, description) VALUES (?, ?)',
                [name, description || null]
            );
            const [newCat] = await db.query('SELECT * FROM categories WHERE id = ?', [result.insertId]);
            res.status(201).json({ message: 'Kategori berhasil ditambahkan', category: newCat[0] });
        } catch (error) {
            console.error('Error adding category:', error);
            res.status(500).json({ error: 'Gagal menambahkan kategori' });
        }
    });

    // DELETE /api/categories/:id
    router.delete('/:id', async (req, res) => {
        try {
            await db.query('DELETE FROM categories WHERE id = ?', [req.params.id]);
            res.json({ message: 'Kategori berhasil dihapus' });
        } catch (error) {
            console.error('Error deleting category:', error);
            res.status(500).json({ error: 'Gagal menghapus kategori' });
        }
    });

    return router;
};
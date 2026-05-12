const express = require('express');
const bcrypt = require('bcryptjs');
const router = express.Router();

module.exports = (db) => {
    // POST /api/auth/login
    router.post('/login', async (req, res) => {
        try {
            const { username, password } = req.body;

            if (!username || !password) {
                return res.status(400).json({ error: 'Username dan password wajib diisi' });
            }

            const [users] = await db.query(
                'SELECT users_id, username, password, full_name, role FROM users WHERE username = ?',
                [username]
            );

            if (users.length === 0) {
                return res.status(401).json({ error: 'Username atau password salah' });
            }

            const user = users[0];
            const isValid = await bcrypt.compare(password, user.password);

            if (!isValid) {
                return res.status(401).json({ error: 'Username atau password salah' });
            }

            // Set session
            req.session.users_id = user.users_id;
            req.session.username = user.username;
            req.session.fullName = user.full_name;
            req.session.role = user.role;

            return res.json({
                message: 'Login berhasil',
                user: {
                    users_id: user.users_id,
                    username: user.username,
                    fullName: user.full_name,
                    role: user.role
                }
            });
        } catch (error) {
            console.error('Login error:', error);
            return res.status(500).json({ error: 'Terjadi kesalahan server' });
        }
    });

    // POST /api/auth/logout
    router.post('/logout', (req, res) => {
        req.session.destroy((err) => {
            if (err) {
                return res.status(500).json({ error: 'Gagal logout' });
            }
            return res.json({ message: 'Logout berhasil' });
        });
    });

    // GET /api/auth/session - Cek status login
    router.get('/session', (req, res) => {
        if (req.session.users_id) {
            return res.json({
                loggedIn: true,
                user: {
                    users_id: req.session.users_id,
                    username: req.session.username,
                    fullName: req.session.fullName,
                    role: req.session.role
                }
            });
        }
        return res.json({ loggedIn: false });
    });

    return router;
};
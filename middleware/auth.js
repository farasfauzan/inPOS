// Middleware untuk cek apakah user sudah login
function isAuthenticated(req, res, next) {
    if (req.session.users_id) {
        return next();
    }
    res.redirect('/login');
}

// Middleware untuk cek role admin
function isAdmin(req, res, next) {
    if (req.session.role === 'admin') {
        return next();
    }
    res.status(403).json({ error: 'Akses ditolak. Hanya untuk admin.' });
}

// Middleware untuk parse JSON
function jsonParser(req, res, next) {
    if (req.headers['content-type'] && req.headers['content-type'].includes('application/json')) {
        express.json()(req, res, next);
    } else {
        next();
    }
}

module.exports = { isAuthenticated, isAdmin };
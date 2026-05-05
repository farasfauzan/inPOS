// inPOS - Role-Based Access Control

// Pages & features accessible by role
const permissions = {
    admin: ['dashboard', 'products', 'pos', 'inventory', 'reports', 'users'],
    kasir: ['pos']
};

// Check if user has access to a page
function hasAccess(role, page) {
    return permissions[role]?.includes(page) || false;
}

// Middleware for API routes
function requireRole(role) {
    return (req, res, next) => {
        if (!req.session.userId) {
            return res.status(401).json({ error: 'Silakan login terlebih dahulu' });
        }
        if (req.session.role !== role) {
            return res.status(403).json({ error: 'Anda tidak memiliki akses ke fitur ini' });
        }
        next();
    };
}

// Middleware for pages - redirect to pos if kasir
function restrictPage(req, res, next) {
    if (!req.session.userId) {
        return res.redirect('/login');
    }
    const page = req.path.replace('/', '');
    if (!hasAccess(req.session.role, page) && page !== 'pos') {
        // Kasir trying to access restricted page → redirect to POS
        return res.redirect('/pos');
    }
    next();
}

module.exports = { hasAccess, requireRole, restrictPage };
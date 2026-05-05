require('dotenv').config();
const express = require('express');
const session = require('express-session');
const path = require('path');
const mysql = require('mysql2/promise');

const app = express();
const PORT = process.env.PORT || 3000;

// Import routes
const authRoutes = require('./routes/auth');
const productRoutes = require('./routes/products');
const transactionRoutes = require('./routes/transactions');
const inventoryRoutes = require('./routes/inventory');
const reportRoutes = require('./routes/reports');
const dashboardRoutes = require('./routes/dashboard');
const categoryRoutes = require('./routes/categories');
const { restrictPage, requireRole } = require('./middleware/rbac');

// Database connection pool
const db = mysql.createPool({
    host: process.env.DB_HOST || 'localhost',
    user: process.env.DB_USER || 'root',
    password: process.env.DB_PASSWORD || '',
    database: process.env.DB_NAME || 'inpos_db',
    waitForConnections: true,
    connectionLimit: 10,
    queueLimit: 0
});

// Test database connection
async function testConnection() {
    try {
        const connection = await db.getConnection();
        console.log('✅ Database connected successfully');
        connection.release();
        return true;
    } catch (error) {
        console.error('❌ Database connection failed:', error.message);
        return false;
    }
}

// Middleware
app.use(express.json());
app.use(express.urlencoded({ extended: true }));
app.use(express.static(path.join(__dirname, 'public')));

// Session
app.use(session({
    secret: process.env.SESSION_SECRET || 'inpos_secret',
    resave: false,
    saveUninitialized: false,
    cookie: { maxAge: 24 * 60 * 60 * 1000 } // 24 hours
}));

// API Routes
app.use('/api/auth', authRoutes(db));
app.use('/api/products', productRoutes(db));
app.use('/api/transactions', transactionRoutes(db));
app.use('/api/inventory', requireRole('admin'), inventoryRoutes(db));
app.use('/api/reports', requireRole('admin'), reportRoutes(db));
app.use('/api/dashboard', dashboardRoutes(db));
app.use('/api/categories', categoryRoutes(db));

// Serve pages
app.get('/', (req, res) => {
    if (req.session.userId) {
        res.sendFile(path.join(__dirname, 'public/pages/dashboard.html'));
    } else {
        res.redirect('/login');
    }
});

app.get('/login', (req, res) => {
    res.sendFile(path.join(__dirname, 'public/pages/login.html'));
});

app.get('/dashboard', restrictPage, (req, res) => {
    res.sendFile(path.join(__dirname, 'public/pages/dashboard.html'));
});

app.get('/products', restrictPage, (req, res) => {
    res.sendFile(path.join(__dirname, 'public/pages/products.html'));
});

app.get('/pos', restrictPage, (req, res) => {
    res.sendFile(path.join(__dirname, 'public/pages/pos.html'));
});

app.get('/inventory', restrictPage, (req, res) => {
    res.sendFile(path.join(__dirname, 'public/pages/inventory.html'));
});

app.get('/reports', restrictPage, (req, res) => {
    res.sendFile(path.join(__dirname, 'public/pages/reports.html'));
});

// Start server
async function startServer() {
    const connected = await testConnection();
    if (connected) {
        app.listen(PORT, () => {
            console.log(`🚀 inPOS Server running at http://localhost:${PORT}`);
        });
    } else {
        console.log('⚠️  Server started but database connection failed.');
        console.log('   Please ensure MySQL is running and execute database/schema.sql');
        app.listen(PORT, () => {
            console.log(`🚀 inPOS Server running at http://localhost:${PORT}`);
        });
    }
}

startServer();
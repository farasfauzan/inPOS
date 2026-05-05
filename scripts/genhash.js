// Generate bcrypt hash for admin password
// Run: node scripts/genhash.js
// Then copy the output hash to database/schema.sql

const bcrypt = require('bcryptjs');

async function main() {
    const hash = await bcrypt.hash('admin123', 10);
    console.log('Password: admin123');
    console.log('Hash:', hash);
}

main();
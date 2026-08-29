require('dotenv').config({ path: require('path').join(__dirname, '.env'), override: true });
const mongoose = require('mongoose');

console.log('URI used for connection:', process.env.MONGO_URI.substring(0, 50) + '...');
mongoose.connect(process.env.MONGO_URI, { serverSelectionTimeoutMS: 20000 })
  .then(() => { 
    console.log('✅ Connected to MongoDB!'); 
    process.exit(0);
  })
  .catch(err => { 
    console.error('❌ Connection failed:', err.message); 
    process.exit(1); 
  });

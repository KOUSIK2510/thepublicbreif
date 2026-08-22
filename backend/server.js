const path = require('path');
require('dotenv').config({ path: path.join(__dirname, '.env'), override: true });
const express = require('express');
const mongoose = require('mongoose');
const cors = require('cors');
const bcrypt = require('bcrypt');
const jwt = require('jsonwebtoken');

const User = require('./models/User');
const Article = require('./models/Article');

const app = express();
// Allow requests from WordPress (Hostinger) and local dev
const allowedOrigins = [
  process.env.WORDPRESS_URL || '',
  'http://localhost:3000',
  'http://localhost:3001',
  'http://localhost:8080',
];
app.use(cors({
  origin: function (origin, callback) {
    // Allow no-origin requests (mobile apps, curl, WP server-side calls)
    if (!origin) return callback(null, true);
    if (allowedOrigins.some(o => o && origin.startsWith(o))) return callback(null, true);
    // In production allow all origins (WP makes server-to-server calls)
    if (process.env.NODE_ENV === 'production') return callback(null, true);
    callback(new Error('Not allowed by CORS'));
  },
  credentials: true,
}));
app.use(express.json());

const PORT = process.env.PORT || 3001;
const JWT_SECRET = process.env.JWT_SECRET || 'supersecretkey123';
const MONGO_URI = process.env.MONGO_URI || 'mongodb://localhost:27017/thepublicbrief';

// Connect to MongoDB with in-memory fallback
async function initDatabase() {
  try {
    await mongoose.connect(MONGO_URI, { serverSelectionTimeoutMS: 15000 });
    console.log('Connected to MongoDB at', MONGO_URI);
  } catch (err) {
    console.log('Local MongoDB not available. Starting in-memory MongoDB server...');
    try {
      const { MongoMemoryServer } = require('mongodb-memory-server');
      const mongod = await MongoMemoryServer.create();
      const uri = mongod.getUri();
      await mongoose.connect(uri);
      console.log('Connected to in-memory MongoDB at', uri);

      // Auto-seed in-memory DB if empty
      const articleCount = await Article.countDocuments();
      if (articleCount === 0) {
        const seedArticles = [
          {
            title: 'Global Markets Rally Amid Breakthrough Geneva Accord',
            category: 'World',
            byline: 'By Julian Vane',
            imageUrl: 'https://lh3.googleusercontent.com/aida-public/AB6AXuDUFtuVO6QyERg19XUxmsqAKllyPUOBRPrITAQ9vtF7BFceGyLGA04CWqI_n2KCNylRmL1M0C_ZnQRxcx3837jDKHghbA_r9U8ZYvzfEA4Tb-bQ8Euw0S3ukmsbbS0HRvqdoS-k6UzBeJbB0OBuBw18nYeLyx85IZlM5Q35R3Ne7yKPrrkwGFLbqkhkPGmp3xNiLKts8p3Lq_h2f6jBNnYl3nC2HG-J34j0OEjK_W5cn5T_iF_r9DAi',
            body: '<p>World leaders have reached a tentative agreement to stabilize international trade routes through the Red Sea, triggering a positive wave across Asian and European exchanges this morning.</p>',
            status: 'published'
          },
          {
            title: 'EU Proposes Unified Green Energy Grid by 2030',
            category: 'World',
            byline: 'By Anna Fischer',
            imageUrl: 'https://lh3.googleusercontent.com/aida-public/AB6AXuDrTj3zWWM3AekbcHKDAHx1lXXBg_wAeWZRsqC5IXyL3-VYr6h6ZjNkND3SZrIOB7Wzu11sDDmP-yx0Jb2L-OrJFIHIQ3Mk_RBWxGcAtZhuuqp_jbOyWyKrIgHb-pri9Z_fTSYN8JaCVQg9mcYtipWZKqmifEq3uZxqV4TGSsr_4pBzQam159bOZVqBQ5ny1XSy-Ofo1n3P6GE1K_GOPzDxi34BqkNsbfXup6CJck66pJ54StK8PlsP',
            body: '<p>Brussels is moving to centralize renewable energy distribution across member states.</p>',
            status: 'published'
          },
          {
            title: 'Tech Hubs in Southeast Asia See 40% Investment Surge',
            category: 'World',
            byline: 'By Ravi Menon',
            imageUrl: 'https://lh3.googleusercontent.com/aida-public/AB6AXuCYynQkNwfQuoz-Qlwihrnoi4chGB4E3kWh6NKvEDiT2vRdLQ2jXLEgFci_VnrMCDGHeHanI3rsBx_4X1NbP33tw_6CAa7HR1qgaLHLZH3uEicXo3qSjDRvl5a5l2fyNpjXy_rKoS8H8WGz3gW4LvptpbATUbQv8KtGNBEK3QIzsBM_an2vA2dd3o1R7NqHRcYGNIF-CzejK3-kfm8qZbeWuDyVXnryndeauE948K9waLMXDb89jKzS',
            body: '<p>Venture capital flows into Singapore and Jakarta have reached record highs.</p>',
            status: 'published'
          },
          {
            title: 'US-LatAm Trade Partnership Reaches Historic Milestone',
            category: 'World',
            byline: 'By Carlos Reyes',
            imageUrl: 'https://lh3.googleusercontent.com/aida-public/AB6AXuCRTM5ScLdJWVl_rrZ1ugjJs43GWVTOGQy5kri-gqL9P5BMiO9WeQDJjOkcxWqnmIJjyNwEYbwITHR_O1DOyvrTXDjeDACxtkNIzVtAjeetBnzO6K9WMCGxwQao1cJKkknM3TctFefukfIeN0tcx3_FTrRa5t_7S4121TX62DfXtOMtW6EwhytiRourlPIugcr1iOnKnoOEveq0mX0hywNYUW6e1UcsqTF8LTWMV-6LZtjdj9wBZXSw',
            body: '<p>New infrastructure projects across Brazil and Argentina receive a combined $38 billion in backing.</p>',
            status: 'published'
          },
          {
            title: 'The Shifting Dynamics of Urban Policy',
            category: 'Politics',
            byline: 'By Admin',
            imageUrl: 'https://lh3.googleusercontent.com/aida-public/AB6AXuBSDzNxgXIw8fRvHqi57TobEZraNtodOwgSWgTFqZpF8ewFguSNfU7Y1Qb9idR-GhIVPBCidFLNg3hNHBrei-Bb7YElUxeS2xmx9Ab200xsYfzR59jv50klpv2RH9ft-xYYabY9b0ZZXzON2bzadWOCF6iADKlpfvfGix3IiZUGAohn0M4pDRXAyS8bNsJc3Em0ccE0AmFXzCoYFpBIpf1htMk0XIeizT2vJzGlDN6BSzGzExNuAMKu',
            body: '<p>Draft content about urban policy.</p>',
            status: 'pending'
          }
        ];
        await Article.insertMany(seedArticles);
        console.log(`Auto-seeded ${seedArticles.length} default articles into in-memory database.`);
      }

      // Seed default admin user if none exists
      const adminExists = await User.findOne({ role: 'admin' });
      if (!adminExists) {
        const admin = new User({
          fullname: 'Editorial Admin',
          username: 'admin',
          email: 'admin@thepublicbrief.com',
          password: 'Admin@1234',
          role: 'admin',
          newsletter: false
        });
        await admin.save();
        console.log('Default admin user seeded: username=admin, password=Admin@1234');
      }
    } catch (memErr) {
      console.error('Failed to start in-memory MongoDB server:', memErr);
    }
  }
}
initDatabase();


// Middleware to verify JWT
const authMiddleware = (req, res, next) => {
  const token = req.header('Authorization')?.replace('Bearer ', '');
  if (!token) return res.status(401).json({ error: 'Access denied' });
  try {
    const verified = jwt.verify(token, JWT_SECRET);
    req.user = verified;
    next();
  } catch (err) {
    res.status(400).json({ error: 'Invalid token' });
  }
};

const adminMiddleware = (req, res, next) => {
  if (req.user.role !== 'admin') {
    return res.status(403).json({ error: 'Admin access required' });
  }
  next();
};

// --- AUTH ROUTES ---

// Signup
app.post('/api/auth/signup', async (req, res) => {
  try {
    const { fullname, username, email, password, newsletter } = req.body;
    
    // Check if user exists
    const existingUser = await User.findOne({ $or: [{ username }, { email }] });
    if (existingUser) {
      return res.status(400).json({ error: 'Username or email already exists' });
    }

    const user = new User({ fullname, username, email, password, newsletter });
    await user.save();
    
    // Create token
    const token = jwt.sign({ id: user._id, role: user.role }, JWT_SECRET, { expiresIn: '24h' });
    
    res.status(201).json({
      message: 'User created successfully',
      token,
      user: { id: user._id, fullname: user.fullname, username: user.username, email: user.email, role: user.role }
    });
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
});

const { OAuth2Client } = require('google-auth-library');
const googleClient = new OAuth2Client(process.env.GOOGLE_CLIENT_ID);

// Real Google OAuth Login
app.post('/api/auth/google', async (req, res) => {
  try {
    const token = req.body && req.body.token;
    if (!token) return res.status(400).json({ error: 'Token is required' });

    let payload;
    try {
      const ticket = await googleClient.verifyIdToken({
        idToken: token,
        audience: process.env.GOOGLE_CLIENT_ID,
      });
      payload = ticket.getPayload();
    } catch (err) {
      console.error('Google Auth Error:', err);
      return res.status(401).json({ error: 'Invalid Google token' });
    }

    const googleId = payload['sub'];
    const email = payload['email'];
    const name = payload['name'];

    let user = await User.findOne({ googleId: googleId });
    
    if (!user) {
      user = await User.findOne({ email: email });
      if (user) {
        user.googleId = googleId;
        await user.save();
      } else {
        const baseUsername = email.split('@')[0];
        let username = baseUsername;
        let counter = 1;
        while (await User.findOne({ username })) {
          username = `${baseUsername}${counter}`;
          counter++;
        }

        user = new User({
          fullname: name,
          username,
          email: email,
          googleId: googleId,
          role: 'writer' // default role for google signup
        });
        await user.save();
      }
    }

    const jwtToken = jwt.sign(
      { id: user._id, role: user.role },
      JWT_SECRET,
      { expiresIn: '24h' }
    );

    res.json({
      token: jwtToken,
      user: { id: user._id, fullname: user.fullname, username: user.username, email: user.email, role: user.role }
    });
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
});

// Login
app.post('/api/auth/login', async (req, res) => {
  try {
    const { username, password } = req.body;
    const user = await User.findOne({ username });
    if (!user) return res.status(400).json({ error: 'Invalid username or password' });

    const isMatch = await user.comparePassword(password);
    if (!isMatch) return res.status(400).json({ error: 'Invalid username or password' });

    const token = jwt.sign({ id: user._id, role: user.role }, JWT_SECRET, { expiresIn: '24h' });
    
    res.json({
      message: 'Logged in successfully',
      token,
      user: { id: user._id, fullname: user.fullname, username: user.username, email: user.email, role: user.role }
    });
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
});

// Get current authenticated user
app.get('/api/auth/me', authMiddleware, async (req, res) => {
  try {
    const user = await User.findById(req.user.id).select('-password');
    if (!user) return res.status(404).json({ error: 'User not found' });
    res.json({ user: { id: user._id, fullname: user.fullname, username: user.username, email: user.email, role: user.role } });
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
});

// --- ARTICLE ROUTES ---

// Get all published articles (public)
app.get('/api/articles', async (req, res) => {
  try {
    const category = req.query.category;
    const author = req.query.author;
    const query = { status: 'published' };
    if (category) query.category = category;
    if (author) query.byline = new RegExp(author, 'i');
    
    const articles = await Article.find(query).sort({ createdAt: -1 }).populate('author', 'fullname username');
    res.json(articles);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
});

// Get pending articles (Admin only)
app.get('/api/articles/pending', authMiddleware, adminMiddleware, async (req, res) => {
  try {
    const articles = await Article.find({ status: 'pending' }).sort({ createdAt: -1 });
    res.json(articles);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
});

// Submit a new article
app.post('/api/articles', authMiddleware, async (req, res) => {
  try {
    const { title, category, byline, imageUrl, body } = req.body;
    
    // Automatically set status to published if admin, otherwise pending
    const status = req.user.role === 'admin' ? 'published' : 'pending';
    
    let finalByline = byline;
    if (!finalByline) {
      const user = await User.findById(req.user.id);
      finalByline = user && user.fullname ? `By ${user.fullname}` : 'By Anonymous';
    }

    const article = new Article({
      title,
      category,
      byline: finalByline,
      imageUrl,
      body,
      status,
      author: req.user.id
    });

    await article.save();
    res.status(201).json({ message: 'Article submitted', article });
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
});

// Approve an article (Admin only)
app.put('/api/articles/:id/approve', authMiddleware, adminMiddleware, async (req, res) => {
  try {
    const article = await Article.findById(req.params.id);
    if (!article) return res.status(404).json({ error: 'Article not found' });
    
    article.status = 'published';
    await article.save();
    
    res.json({ message: 'Article approved and published', article });
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
});

// --- NEWSLETTER ROUTES ---
app.post('/api/subscribe', async (req, res) => {
  // Simple placeholder for newsletter subscription, could integrate with User model
  // For now we just return success
  const { email } = req.body;
  if (!email) return res.status(400).json({ error: 'Email is required' });
  
  res.json({ message: 'Subscribed successfully' });
});

// Serve static frontend files
app.use(express.static(path.join(__dirname, '..')));
app.use('/portal', express.static(path.join(__dirname, '../portal')));

// Health check endpoint (for Railway/Render uptime monitoring)
app.get('/health', (req, res) => {
  res.json({ status: 'ok', timestamp: new Date().toISOString(), service: 'thepublicbrief-api' });
});

// Always listen (Railway/Render need the server to bind to PORT)
app.listen(PORT, '0.0.0.0', () => {
  console.log(`The Public Brief API running on port ${PORT}`);
  console.log(`Environment: ${process.env.NODE_ENV || 'development'}`);
});

module.exports = app;

const path = require('path');
require('dotenv').config({ path: path.join(__dirname, '.env') });
const express = require('express');
const mongoose = require('mongoose');
const cors = require('cors');
const bcrypt = require('bcrypt');
const jwt = require('jsonwebtoken');

const User = require('./models/User');
const Article = require('./models/Article');

const app = express();
app.use(cors());
app.use(express.json());

const PORT = process.env.PORT || 3001;
const JWT_SECRET = process.env.JWT_SECRET || 'supersecretkey123';
const MONGO_URI = process.env.MONGO_URI || 'mongodb://localhost:27017/thepublicbrief';

// Connect to MongoDB
mongoose.connect(MONGO_URI)
  .then(() => console.log('Connected to MongoDB'))
  .catch(err => console.error('MongoDB connection error:', err));

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

// --- ARTICLE ROUTES ---

// Get all published articles (public)
app.get('/api/articles', async (req, res) => {
  try {
    const category = req.query.category;
    const query = { status: 'published' };
    if (category) query.category = category;
    
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
    
    const article = new Article({
      title,
      category,
      byline: byline || req.user.fullname, // use user's name if no byline provided
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

app.listen(PORT, () => {
  console.log(`Backend server running on port ${PORT}`);
});

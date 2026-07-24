const mongoose = require('mongoose');
const Article = require('./models/Article');
const path = require('path');
require('dotenv').config({ path: path.join(__dirname, '.env') });

const MONGO_URI = process.env.MONGO_URI || 'mongodb://localhost:27017/thepublicbrief';

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

mongoose.connect(MONGO_URI)
  .then(async () => {
    console.log('Connected to MongoDB for seeding');
    // Clear existing
    await Article.deleteMany({});
    console.log('Cleared existing articles');
    
    // Insert new
    await Article.insertMany(seedArticles);
    console.log(`Seeded ${seedArticles.length} articles`);
    
    mongoose.connection.close();
  })
  .catch(err => {
    console.error('Seeding error:', err);
    mongoose.connection.close();
  });

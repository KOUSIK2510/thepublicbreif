const path = require('path');
require('dotenv').config({ path: path.join(__dirname, '.env') });
const mongoose = require('mongoose');
const Article = require('./models/Article');

const MONGO_URI = process.env.MONGO_URI;

const richArticles = [
  {
    title: 'The Paradigm Shift: How Global Alliances Are Realigning in the Digital Age',
    category: 'World',
    byline: 'Jonathan Hayes',
    imageUrl: 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=800&q=80',
    body: '<p>In a stunning series of diplomatic maneuvers, traditional treaties are being rewritten to accommodate the realities of cyber warfare and digital economies. Experts argue we are witnessing the most significant geopolitical realignment since the end of the Cold War.</p><p>The Geneva Accord, signed last month by representatives of forty-two nations, marks the first time that digital infrastructure has been formally classified as critical national security architecture.</p>',
    status: 'published'
  },
  {
    title: 'Congress Reaches Historic Agreement on Clean Energy Mandates',
    category: 'National',
    byline: 'Sarah Jenkins',
    imageUrl: 'https://images.unsplash.com/photo-1466611653911-95081537e5b7?w=800&q=80',
    body: '<p>In an unprecedented late-night session, lawmakers from both sides of the aisle finalized a sweeping legislative package aimed at overhauling the nation\'s energy grid — one of the most ambitious domestic policy achievements in a generation.</p><p>The bill sets legally binding targets for renewable energy generation to reach 60% of the national grid by 2035.</p>',
    status: 'published'
  },
  {
    title: 'Valley FC Clinch Championship Title in Stunning Final-Minute Victory',
    category: 'Sports',
    byline: 'Michael Torres',
    imageUrl: 'https://images.unsplash.com/photo-1489944440615-453fc2b6a9a9?w=800&q=80',
    body: '<p>In what analysts are already calling the most dramatic national final in a decade, Valley FC overturned a two-goal deficit in the dying minutes to claim their first ever national championship. Captain Leon Graves scored the decisive header in the 94th minute.</p>',
    status: 'published'
  },
  {
    title: 'The Celluloid Renaissance: Why Film Photography Is Making Its Grand Return',
    category: 'Entertainment',
    byline: 'Elena Rodriguez',
    imageUrl: 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w=800&q=80',
    body: '<p>A new wave of filmmakers and artists are abandoning digital perfection in favor of analog texture. From Hollywood studios to indie directors, celluloid film is being reclaimed as a deliberate artistic statement in an era of algorithmic content.</p>',
    status: 'published'
  },
  {
    title: 'Astronomers Discover Potentially Habitable Exoplanet System Just 40 Light-Years Away',
    category: 'World',
    byline: 'Dr. Priya Nair',
    imageUrl: 'https://images.unsplash.com/photo-1446941611757-91d2c3bd3d45?w=800&q=80',
    body: '<p>A team of international astronomers has identified a system of three rocky planets orbiting within the habitable zone of a nearby star, raising unprecedented questions about the potential for extraterrestrial life.</p><p>The discovery, published in Nature Astronomy, represents the closest confirmed habitable-zone system ever found.</p>',
    status: 'published'
  },
  {
    title: 'The Quiet Crisis of Democratic Institutions Worldwide',
    category: 'Opinion',
    byline: 'Prof. David Okafor',
    imageUrl: 'https://images.unsplash.com/photo-1529107386315-e1a2ed48a620?w=800&q=80',
    body: '<p>There is a creeping erosion happening in plain sight. Not the loud, dramatic collapse that political theorists once warned of — but something more insidious: the slow normalization of dysfunction, the quiet acceptance of broken systems as simply "how things are."</p>',
    status: 'published'
  },
  {
    title: 'Tech Hubs in Southeast Asia See 40% Investment Surge This Quarter',
    category: 'World',
    byline: 'Ravi Menon',
    imageUrl: 'https://images.unsplash.com/photo-1535303311164-664fc9ec6532?w=800&q=80',
    body: '<p>Venture capital flows into Singapore, Jakarta, and Ho Chi Minh City have reached record highs, with investors pouring over $12 billion into the region in a single quarter — a 40% jump from the same period last year.</p>',
    status: 'published'
  },
  {
    title: 'Global Markets Rally Amid Breakthrough Geneva Accord',
    category: 'World',
    byline: 'Julian Vane',
    imageUrl: 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=800&q=80',
    body: '<p>World leaders have reached a tentative agreement to stabilize international trade routes through the Red Sea, triggering a positive wave across Asian and European exchanges this morning. The Dow Jones rose 2.3% while Asian markets posted their best session in six months.</p>',
    status: 'published'
  },
  {
    title: 'Supreme Court Issues Landmark Ruling on Digital Privacy Rights',
    category: 'National',
    byline: 'Rebecca Walsh',
    imageUrl: 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?w=800&q=80',
    body: '<p>In a 6-3 decision that legal scholars are calling the most significant privacy ruling of the 21st century, the Supreme Court ruled that government agencies must obtain a warrant before accessing any digital communications older than 24 hours.</p>',
    status: 'published'
  },
  {
    title: 'AI Models Now Outperform Human Doctors in Early Cancer Detection',
    category: 'World',
    byline: 'Dr. Aisha Kamara',
    imageUrl: 'https://images.unsplash.com/photo-1576671081837-49000212a370?w=800&q=80',
    body: '<p>A landmark clinical trial across 14 hospitals has demonstrated that an AI diagnostic system can detect early-stage pancreatic and lung cancers with 94% accuracy — surpassing the 78% average accuracy rate of experienced radiologists.</p>',
    status: 'published'
  },
  {
    title: 'New Conservation Bill Proposes Expansion of Protected Federal Lands',
    category: 'National',
    byline: 'Anna Fischer',
    imageUrl: 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=800&q=80',
    body: '<p>The sweeping Conservation and Wilderness Preservation Act, introduced in the Senate yesterday, would add 47 million acres to the national protected lands registry — the largest single expansion since the Antiquities Act of 1906.</p>',
    status: 'published'
  },
  {
    title: 'US-LatAm Trade Partnership Reaches Historic Milestone at Summit',
    category: 'World',
    byline: 'Carlos Reyes',
    imageUrl: 'https://images.unsplash.com/photo-1526304640581-d334cdbbf45e?w=800&q=80',
    body: '<p>New infrastructure projects across Brazil, Argentina, and Colombia will receive a combined $38 billion in backing from the newly ratified Americas Trade and Infrastructure Compact, signed by 19 heads of state at the Panama City Summit.</p>',
    status: 'published'
  }
];

mongoose.connect(MONGO_URI)
  .then(async () => {
    console.log('Connected to MongoDB Atlas');
    await Article.deleteMany({});
    console.log('Cleared old articles');
    const inserted = await Article.insertMany(richArticles);
    console.log(`✅ Successfully seeded ${inserted.length} articles into Atlas!`);
    inserted.forEach(a => console.log(`  [${a.category}] ${a.title.substring(0, 55)}`));
    mongoose.connection.close();
  })
  .catch(err => {
    console.error('Error:', err.message);
    process.exit(1);
  });

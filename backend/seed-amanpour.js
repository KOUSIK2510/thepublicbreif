const path = require('path');
require('dotenv').config({ path: path.join(__dirname, '.env') });
const mongoose = require('mongoose');
const Article = require('./models/Article');

mongoose.connect(process.env.MONGO_URI).then(async () => {
  const articles = [
    {
      title: 'The World Through Amanpour: Why Frontline Journalism Still Matters',
      category: 'Opinion',
      byline: 'By Christiane Amanpour',
      imageUrl: 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/7c/Christiane_Amanpour_June_2008_%28cropped%29.jpg/330px-Christiane_Amanpour_June_2008_%28cropped%29.jpg',
      body: '<p>In a world drowning in noise, the role of the foreign correspondent has never been more vital. From Bosnia to Baghdad, from Gaza to Kabul, I have witnessed how the truth, even when painful, is always preferable to comfortable silence.</p><p>A free press is the foundation of a free society. When journalists are silenced, jailed, or killed, it is democracy itself that bleeds. The world must stand firm in its commitment to press freedom.</p>',
      status: 'published'
    },
    {
      title: 'Gaza, Ukraine, and the Crisis of Accountability: A Reporter\'s Perspective',
      category: 'World',
      byline: 'By Christiane Amanpour',
      imageUrl: 'https://lh3.googleusercontent.com/aida-public/AB6AXuDUFtuVO6QyERg19XUxmsqAKllyPUOBRPrITAQ9vtF7BFceGyLGA04CWqI_n2KCNylRmL1M0C_ZnQRxcx3837jDKHghbA_r9U8ZYvzfEA4Tb-bQ8Euw0S3ukmsbbS0HRvqdoS-k6UzBeJbB0OBuBw18nYeLyx85IZlM5Q35R3Ne7yKPrrkwGFLbqkhkPGmp3xNiLKts8p3Lq_h2f6jBNnYl3nC2HG-J34j0OEjK_W5cn5T_iF_r9DAi',
      body: '<p>Having reported from both conflict zones, I see a disturbing pattern: the international community\'s willingness to hold perpetrators accountable is selective and inconsistent. Justice cannot be applied differently based on geography or political convenience.</p>',
      status: 'published'
    },
    {
      title: 'The Duty to Inform: Journalism in the Age of Disinformation',
      category: 'National',
      byline: 'By Christiane Amanpour',
      imageUrl: 'https://lh3.googleusercontent.com/aida-public/AB6AXuDrTj3zWWM3AekbcHKDAHx1lXXBg_wAeWZRsqC5IXyL3-VYr6h6ZjNkND3SZrIOB7Wzu11sDDmP-yx0Jb2L-OrJFIHIQ3Mk_RBWxGcAtZhuuqp_jbOyWyKrIgHb-pri9Z_fTSYN8JaCVQg9mcYtipWZKqmifEq3uZxqV4TGSsr_4pBzQam159bOZVqBQ5ny1XSy-Ofo1n3P6GE1K_GOPzDxi34BqkNsbfXup6CJck66pJ54StK8PlsP',
      body: '<p>Disinformation is the cancer of the information age. It spreads faster than truth and is increasingly weaponized by authoritarian states. Journalists must be the antidote — not by editorializing, but by relentlessly pursuing verifiable facts.</p>',
      status: 'published'
    }
  ];
  
  const result = await Article.insertMany(articles);
  console.log('Seeded', result.length, 'articles for Christiane Amanpour');
  await mongoose.disconnect();
  process.exit(0);
}).catch(e => { console.error(e); process.exit(1); });

/**
 * The Public Brief — Global App JS
 * Handles: active nav, search overlay, article reader modal, toast
 */

(function () {
  'use strict';

  /* ── Helpers ─────────────────────────────────────────── */
  function $(sel, ctx) { return (ctx || document).querySelector(sel); }
  function $$(sel, ctx) { return Array.from((ctx || document).querySelectorAll(sel)); }

  /* ── Active Nav Highlight ─────────────────────────────── */
  function initActiveNav() {
    const page = location.pathname.split('/').pop() || 'index.html';
    $$('nav a, header nav a').forEach(function (a) {
      const href = (a.getAttribute('href') || '').split('/').pop();
      if (href === page) {
        a.classList.add('nav-active');
        a.style.color = '#b90c25';
        a.style.borderBottomColor = '#b90c25';
      }
    });
  }

  /* ── Search Overlay ───────────────────────────────────── */
  const SEARCH_ARTICLES = [
    { category: 'World Affairs', title: 'The Paradigm Shift: How Global Alliances are Realigning', href: 'index.html' },
    { category: 'Justice', title: 'Supreme Court Hands Down Landmark Ruling on Digital Privacy', href: 'national.html' },
    { category: 'Markets', title: 'European Markets Brace for Impact Ahead of Key Vote', href: 'world.html' },
    { category: 'Politics', title: 'Congress Reaches Historic Agreement on Clean Energy Mandates', href: 'national.html' },
    { category: 'Environment', title: 'New Conservation Bill Proposes Expansion of Protected Federal Lands', href: 'national.html' },
    { category: 'Diplomacy', title: 'Global Markets Rally Amid Breakthrough Geneva Accord', href: 'world.html' },
    { category: 'Technology', title: 'Tech Hubs in Southeast Asia See 40% Investment Surge', href: 'world.html' },
    { category: 'Opinion', title: 'The Quiet Crisis of Democratic Institutions', href: 'opinion.html' },
    { category: 'Sports', title: 'Valley FC Clinch Championship Title in Stunning Final-Minute Victory', href: 'sports.html' },
    { category: 'Film', title: 'The Celluloid Renaissance: Why Film Photography Is Making Its Return', href: 'entertainment.html' },
    { category: 'Science', title: 'Astronomers Discover Potentially Habitable Exoplanet System', href: 'index.html' },
    { category: 'Culture', title: 'The Resurgence of Print Media in a Digital-First Era', href: 'index.html' },
  ];

  function buildSearchOverlay() {
    if ($('#search-overlay')) return;
    const overlay = document.createElement('div');
    overlay.id = 'search-overlay';
    overlay.innerHTML = `
      <div class="search-box">
        <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1rem;">
          <span class="material-symbols-outlined" style="color:#B00020;font-size:1.5rem;">search</span>
          <input id="search-input" type="text" placeholder="Search The Public Brief…" autocomplete="off" autofocus/>
          <button id="search-close" style="background:none;border:none;cursor:pointer;font-size:1.5rem;color:#747878;" title="Close">✕</button>
        </div>
        <div class="search-results" id="search-results"></div>
      </div>`;
    document.body.appendChild(overlay);

    $('#search-close').addEventListener('click', closeSearch);
    overlay.addEventListener('click', function (e) {
      if (e.target === overlay) closeSearch();
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') closeSearch();
    });

    $('#search-input').addEventListener('input', function () {
      const q = this.value.trim().toLowerCase();
      const results = $('#search-results');
      results.innerHTML = '';
      if (!q) return;
      const matches = SEARCH_ARTICLES.filter(function (a) {
        return a.title.toLowerCase().includes(q) || a.category.toLowerCase().includes(q);
      });
      if (!matches.length) {
        results.innerHTML = '<p style="color:#6B7280;font-size:0.9rem;">No results found.</p>';
        return;
      }
      matches.forEach(function (a) {
        const div = document.createElement('div');
        div.className = 'search-result-item';
        div.innerHTML = '<div class="result-category">' + a.category + '</div><div class="result-title">' + a.title + '</div>';
        div.addEventListener('click', function () {
          window.location.href = a.href;
        });
        results.appendChild(div);
      });
    });
  }

  function openSearch() {
    buildSearchOverlay();
    setTimeout(function () {
      $('#search-overlay').classList.add('open');
      var inp = $('#search-input');
      if (inp) inp.focus();
    }, 10);
  }

  function closeSearch() {
    var o = $('#search-overlay');
    if (o) o.classList.remove('open');
  }

  function initSearchTriggers() {
    // Any button/element with data-action="search" or class "search-trigger"
    $$('[data-search-trigger], .search-trigger, button[aria-label="search"]').forEach(function (btn) {
      btn.addEventListener('click', openSearch);
    });
    // Also keyboard shortcut: Ctrl+K / Cmd+K
    document.addEventListener('keydown', function (e) {
      if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        openSearch();
      }
    });
  }

  /* ── Article Reader Modal ─────────────────────────────── */
  const ARTICLE_CONTENT = {
    'The Paradigm Shift: How Global Alliances are Realigning in the Digital Age': {
      category: 'World Affairs',
      byline: 'By Jonathan Hayes  •  Nov 12, 2024  •  8 min read',
      img: 'https://lh3.googleusercontent.com/aida-public/AB6AXuCgfRMAZ9Dllk5B5W_Y1OIyWLGPpRNETN8x_wuIAJSt7rscoIw2pw-srpVPwYzEDips91wHyoEWzhKv2AVyH9uwuRBMrIYnj96EbwwBgGo7KimHUj6VUFos35MfO5P1R4ecT-4dAJnXv1GnQTHKC_z-LOrUTg7V4oBAcbswAqi8epUizzZceasv-wqb_SkEh5P9_AjnMQWnnTrT6C9zMWCcHrMnq_tzOGTBwA_jqPS9MZXibdtS2sT2',
      body: '<p>In a stunning series of diplomatic maneuvers, traditional treaties are being rewritten to accommodate the realities of cyber warfare and digital economies. Experts argue we are witnessing the most significant geopolitical realignment since the end of the Cold War, with technology sectors dictating terms previously reserved for sovereign states.</p><p>The Geneva Accord, signed last month by representatives of forty-two nations, marks the first time that digital infrastructure has been formally classified as critical national security architecture—placing data centers, subsea cables, and AI research facilities under the same protective treaties as military installations.</p><p>"We are no longer debating whether the internet is a public good," said Dr. Miriam Solberg of the Copenhagen Institute for Strategic Studies. "The question is who controls its architecture, and what that means for the balance of power in the 21st century."</p><p>For decades, the United States and European Union maintained a relatively stable digital hegemony through the dominance of their technology corporations. But the rapid rise of state-sponsored AI development programs in China, India, and the Gulf states has introduced new vectors of geopolitical competition that traditional diplomacy was not designed to address.</p><p>The implications are profound: trade agreements now include data-sovereignty clauses; military alliances require interoperability of encrypted communication standards; and the very concept of "national territory" is being renegotiated to include virtual spaces.</p>'
    },
    'Congress Reaches Historic Agreement on Clean Energy Mandates': {
      category: 'National / Politics',
      byline: 'By Sarah Jenkins, Chief Political Correspondent  •  4 hours ago  •  7 min read',
      img: 'https://lh3.googleusercontent.com/aida-public/AB6AXuDwGf62FEFMDDbFpb32yKeoc0wS2R9dxmFv6gC22-ieWiYK8zi0gdnV1zK3RkvyUS7E8BQIP9SyOj8RFBZO0Fi8HPTmpSen6Z9sKDXkRyyYSc6GkNl_YQKKX8Do0tTl32dBZcc4GBVAshhJ1-m_6AcIXIceYXc4leMNDSqHTeMsVMosj-1HBJgAjt-DGPmBcZL5fPaSM1CBOqB95dYN-OCmg_mXBQIt6vDsY6ZwAZ6w9NBR3lhirVvW',
      body: '<p>In an unprecedented late-night session that stretched into the early hours of Thursday morning, lawmakers from both sides of the aisle finalized a sweeping legislative package aimed at overhauling the nation\'s energy grid—one of the most ambitious domestic policy achievements in a generation.</p><p>The bill, formally titled the American Energy Transition and Independence Act, sets legally binding targets for renewable energy generation to reach 60% of the national grid by 2035 and 80% by 2045. It establishes a $240 billion federal fund for grid modernization, with priority allocations for rural communities and former coal-dependent regions.</p><p>"This is the deal that nobody thought was possible twelve months ago," said Senate Majority Leader Patricia Holt in a midnight press conference. "It isn\'t perfect—no legislation ever is—but it is historic, and it is ours."</p><p>Corporate compliance mandates will require all publicly traded utilities to submit transition roadmaps within 18 months. Consumer incentive programs will expand EV tax credits, rooftop solar rebates, and home battery storage subsidies, with income-scaled support ensuring that lower-income households benefit proportionally.</p><p>Opposition from oil-state senators was ultimately overcome through a series of targeted provisions: a ten-year phase-out timeline for existing fossil fuel infrastructure (rather than an immediate ban), and a $45 billion "energy worker transition" fund providing retraining, pension bridging, and relocation assistance.</p>'
    },
    'Valley FC Clinch Championship Title in Stunning Final-Minute Victory': {
      category: 'Sports / Championship',
      byline: 'By Michael Torres  •  2 hours ago  •  6 min read',
      img: 'https://lh3.googleusercontent.com/aida-public/AB6AXuBLOkWz-LVcikMzBmDewscN0zIcxRT3lU5Pt5uhUEMOAlG5o7qlu_lbIL_KtKGvidmaQt0r9lN-OOylSbZKTe_vPcdE5jIG9zee_p6UeHHv2MMorIqCzh3idTUeUB3sZru-j1zRD6Yfd7VHATbMY301I8I4EBZX4RTBWziq9dN3Q2mFAPILrJSOQ6UpT3oyCFM-z8LOsYiXBpKmM9-6vYga14stcFrgbFI2o4Z5ejKWrY9WWulus6o3',
      body: '<p>In what analysts are already calling the most dramatic national final in a decade, Valley FC overturned a two-goal deficit in the dying minutes to claim their first ever national championship. Captain Leon Graves scored the decisive header in the 94th minute, sending thousands of fans into collective euphoria.</p><p>The match, played in front of a sold-out 68,000-seat stadium, began as a catastrophe for Valley FC. By the 67th minute, they trailed 2–0 against the heavily favoured Northgate Athletic, a club with three consecutive titles and a squad valued at three times their opponents\'.</p><p>"We never stopped believing," said Graves, visibly emotional in the post-match interview. "Our manager told us at half-time: forty-five minutes is enough to change everything. And it was."</p><p>A defensive error by Northgate in the 71st minute gifted Valley striker Emeka Osei his first goal of the night. The equaliser came from midfielder Rosa Pelletier—a thunderous left-foot drive from 25 yards that drew gasps across the stadium. And then, in the dying seconds of five minutes of added time, Graves rose above the Northgate defence to meet a corner from the right and power his header into the bottom-left corner.</p><p>The final whistle brought scenes of joyous pandemonium: players collapsed on the turf, supporters flooded the lower tiers, and manager Damiano Berardi raised his arms toward the sky.</p>'
    },
    'The Celluloid Renaissance: Why Film Photography Is Making Its Grand Return': {
      category: 'Entertainment / Film',
      byline: 'By Elena Rodriguez, Arts Critic  •  Nov 13, 2024  •  9 min read',
      img: 'https://lh3.googleusercontent.com/aida-public/AB6AXuDrTj3zWWM3AekbcHKDAHx1lXXBg_wAeWZRsqC5IXyL3-VYr6h6ZjNkND3SZrIOB7Wzu11sDDmP-yx0Jb2L-OrJFIHIQ3Mk_RBWxGcAtZhuuqp_jbOyWyKrIgHb-pri9Z_fTSYN8JaCVQg9mcYtipWZKqmifEq3uZxqV4TGSsr_4pBzQam159bOZVqBQ5ny1XSy-Ofo1n3P6GE1K_GOPzDxi34BqkNsbfXup6CJck66pJ54StK8PlsP',
      body: '<p>A new wave of filmmakers and artists are abandoning digital perfection in favor of analog texture. From Hollywood studios to indie directors, celluloid film is being reclaimed as a deliberate artistic statement in an era of algorithmic content.</p><p>Kodak\'s Rochester facility, which came within weeks of closure in 2012, has reopened a second production line to meet surging demand. Fujifilm reports a 340% increase in 35mm stock orders over the past three years. And in Sundance submission data, films shot on film have risen from 4% of entries in 2018 to over 19% in 2024.</p><p>"There\'s a tactility to film that you cannot replicate in post-production," says director Marcus Osei, whose debut feature <em>The Weight of Blue</em> was shot entirely on Super 16mm. "When you load a roll and you only have thirty-six frames, every single decision matters. That constraint is liberating."</p><p>The trend extends beyond professional filmmaking. Photography collectives in Berlin, Tokyo, and New York report record membership. Darkroom rental studios are opening in cities that hadn\'t seen chemical development since the 1990s. A new generation of practitioners, many of whom grew up shooting exclusively on smartphones, are discovering the pleasure of the latent image—of not knowing exactly what you\'ve captured until the negative is developed.</p><p>Critics of the movement note that analog film is neither sustainable nor democratic: a single roll costs fifteen dollars to buy and another fifteen to develop, creating a barrier that digital photography entirely eliminated. But proponents argue that the permanence of the physical negative—the irreducibility of the silver halide crystal—carries a weight that metadata-stripped JPEG files simply cannot match.</p>'
    }
  };

  function buildArticleModal() {
    if ($('#article-modal')) return;
    const modal = document.createElement('div');
    modal.id = 'article-modal';
    modal.innerHTML = `
      <div class="modal-content">
        <button class="modal-close" id="modal-close-btn" title="Close article">✕</button>
        <img class="modal-img" id="modal-img" src="" alt=""/>
        <span class="modal-category" id="modal-category"></span>
        <h2 class="modal-headline" id="modal-headline"></h2>
        <p class="modal-byline" id="modal-byline"></p>
        <div class="modal-body" id="modal-body"></div>
      </div>`;
    document.body.appendChild(modal);

    $('#modal-close-btn').addEventListener('click', closeArticleModal);
    modal.addEventListener('click', function (e) {
      if (e.target === modal) closeArticleModal();
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') closeArticleModal();
    });
  }

  function openArticleModal(title) {
    buildArticleModal();
    const data = ARTICLE_CONTENT[title] || {
      category: 'The Public Brief',
      byline: 'Editorial Team',
      img: '',
      body: '<p>Full article content coming soon. Thank you for your interest in The Public Brief.</p>'
    };
    $('#modal-category').textContent = data.category;
    $('#modal-headline').textContent = title;
    $('#modal-byline').textContent = data.byline;
    $('#modal-body').innerHTML = data.body;
    var img = $('#modal-img');
    if (data.img) {
      img.src = data.img;
      img.style.display = 'block';
    } else {
      img.style.display = 'none';
    }
    setTimeout(function () {
      $('#article-modal').classList.add('open');
      document.body.style.overflow = 'hidden';
    }, 10);
  }

  function closeArticleModal() {
    var m = $('#article-modal');
    if (m) m.classList.remove('open');
    document.body.style.overflow = '';
  }

  function initArticleCards() {
    $$('article[class*="group"], article.clickable-article, .article-card').forEach(function (card) {
      // Find headline
      var h = card.querySelector('h1, h2, h3, h4');
      if (!h) return;
      card.style.cursor = 'pointer';
      card.addEventListener('click', function (e) {
        // Don't intercept admin action buttons
        if (e.target.closest('button, a[href]')) return;
        openArticleModal(h.textContent.trim());
      });
    });
  }

  /* ── Toast ────────────────────────────────────────────── */
  function buildToast() {
    if ($('#pb-toast')) return;
    var t = document.createElement('div');
    t.id = 'pb-toast';
    t.innerHTML = '<span class="material-symbols-outlined toast-icon">check_circle</span><span id="pb-toast-msg"></span>';
    document.body.appendChild(t);
  }

  window.showPBToast = function (msg) {
    buildToast();
    $('#pb-toast-msg').textContent = msg;
    $('#pb-toast').classList.add('show');
    setTimeout(function () { $('#pb-toast').classList.remove('show'); }, 3000);
  };

  /* ── Auth State ───────────────────────────────────────── */
  function checkAuthState() {
    try {
      var user = JSON.parse(localStorage.getItem('pb_user') || 'null');
      // If admin is on a non-admin page, add admin link to header
      if (user && user.role === 'admin') {
        var headers = $$('header');
        headers.forEach(function (h) {
          if (!h.querySelector('.admin-link')) {
            var a = document.createElement('a');
            a.href = 'admin.html';
            a.className = 'admin-link';
            a.style.cssText = 'font-size:12px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#B00020;text-decoration:none;padding:4px 10px;border:1px solid #B00020;border-radius:3px;margin-left:8px;';
            a.textContent = 'Admin';
            var loginLink = h.querySelector('a[href="login.html"]');
            if (loginLink) loginLink.parentNode.insertBefore(a, loginLink.nextSibling);
          }
        });
      }
    } catch(e) {}
  }

  /* ── Wire search icon in all headers ─────────────────── */
  function wireSearchIcons() {
    // Look for any Material Icon button with 'search' text or search icon span
    $$('.material-symbols-outlined').forEach(function (icon) {
      if (icon.textContent.trim() === 'search') {
        var btn = icon.closest('button') || icon.parentElement;
        if (btn && !btn.dataset.searchWired) {
          btn.dataset.searchWired = '1';
          btn.addEventListener('click', function(e) {
            e.preventDefault();
            openSearch();
          });
        }
      }
    });
  }

  /* ── Newsletter Subscriptions ──────────────────────────── */
  function initNewsletterForms() {
    // Wire all newsletter/email forms on the page
    document.querySelectorAll('form').forEach(function(form) {
      // Only target forms that have an email input but are NOT login/signup forms
      var emailInput = form.querySelector('input[type="email"]');
      if (!emailInput) return;
      // Skip forms that already have a known ID (login/signup)
      if (form.id === 'login-form' || form.id === 'signup-form') return;

      form.addEventListener('submit', function(e) {
        e.preventDefault();
        var email = emailInput.value.trim();
        if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
          emailInput.style.borderColor = '#B00020';
          setTimeout(function(){ emailInput.style.borderColor = ''; }, 2000);
          return;
        }
        var btn = form.querySelector('button');
        var origText = btn ? btn.textContent : '';
        if (btn) btn.textContent = '...';

        fetch('http://localhost:3001/api/subscribe', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ email: email })
        }).then(function(res) {
          if (!res.ok) throw new Error('Subscription failed');
          return res.json();
        }).then(function() {
          window.showPBToast('✉ You\'re subscribed! Welcome to The Daily Briefing.');
          form.reset();
        }).catch(function(err) {
          window.showPBToast('Error subscribing. Try again later.');
        }).finally(function() {
          if (btn) btn.textContent = origText;
        });
      });
    });

    // Wire inline email+arrow-button pairs (not inside a <form>)
    document.querySelectorAll('input[type="email"]').forEach(function(inp) {
      if (inp.form) return; // already handled above
      var btn = inp.nextElementSibling;
      if (!btn) return;
      btn.addEventListener('click', function() {
        var email = inp.value.trim();
        if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
          inp.style.borderColor = '#B00020';
          setTimeout(function(){ inp.style.borderColor = ''; }, 2000);
          return;
        }
        var subs = JSON.parse(localStorage.getItem('pb_newsletter_subs') || '[]');
        if (!subs.includes(email)) { subs.push(email); localStorage.setItem('pb_newsletter_subs', JSON.stringify(subs)); }
        window.showPBToast('✓ You\'re subscribed! Welcome to The Daily Briefing.');
        inp.value = '';
        btn.innerHTML = '<span class="material-symbols-outlined text-[20px]">check</span>';
        btn.disabled = true;
      });
    });
  }

  /* ── Subscribe buttons → signup.html ───────────────────── */
  function wireSubscribeButtons() {
    document.querySelectorAll('a, button').forEach(function(el) {
      if (el.textContent.trim().toUpperCase() === 'SUBSCRIBE' && !el.dataset.subWired) {
        el.dataset.subWired = '1';
        el.addEventListener('click', function(e) {
          // Only redirect if it's not inside a newsletter form
          if (!el.closest('form')) {
            e.preventDefault();
            window.location.href = 'signup.html';
          }
        });
      }
    });
  }

  /* 🚀 Dynamic Backend Data Fetching 🚀 */
  /* 🚀 Dynamic Backend Data Fetching 🚀 */
  function loadDynamicArticles() {
    var grid = document.getElementById('article-grid');
    if (!grid) return; // Not on a page with an article grid

    // Determine category based on the URL
    var pathname = window.location.pathname;
    var filename = pathname.substring(pathname.lastIndexOf('/') + 1);
    var category = filename.replace('.html', '') || '';
    if (category === 'index' || category === '') return; // handled by loadHomeArticles

    var url = 'http://localhost:3001/api/articles';
    if (category) {
      category = category.charAt(0).toUpperCase() + category.slice(1);
      url += '?category=' + category;
    }

    grid.innerHTML = '<div class="col-span-full text-center text-meta-text py-12">Loading articles...</div>';

    fetch(url)
      .then(function(res) { return res.json(); })
      .then(function(articles) {
        grid.innerHTML = '';
        if (articles.length === 0) {
          grid.innerHTML = '<div class="col-span-full text-center text-meta-text py-12">No articles found in this category.</div>';
          return;
        }

        articles.forEach(function(art) {
          var articleHtml = `
            <article class="group flex flex-col gap-4 cursor-pointer" onclick="openArticleModal('${art.title}')">
              <div class="aspect-[16/9] overflow-hidden border border-separator w-full">
                <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="${art.imageUrl || ''}" alt="${art.title}"/>
              </div>
              <div class="flex flex-col gap-2">
                <span class="text-breaking-red font-label-sm text-label-sm uppercase tracking-wider font-bold">${art.category}</span>
                <h3 class="text-headline-md font-headline-md text-primary group-hover:underline decoration-breaking-red underline-offset-4 leading-snug">${art.title}</h3>
                <div class="flex items-center gap-2 text-meta-text text-sm">
                  <span class="font-semibold">${art.byline}</span>
                </div>
              </div>
            </article>
          `;
          grid.insertAdjacentHTML('beforeend', articleHtml);

          if (!window.WORLD_ARTICLES) window.WORLD_ARTICLES = {};
          window.WORLD_ARTICLES[art.title] = { category: art.category, byline: art.byline, img: art.imageUrl, body: art.body };
        });
      })
      .catch(function(err) {
        console.error('Failed to load articles', err);
        grid.innerHTML = '<div class="col-span-full text-center text-breaking-red py-12">Error loading articles from the backend.</div>';
      });
  }

  function loadHomeArticles() {
    var latestContainer = document.getElementById('home-latest-news');
    var centerContainer = document.getElementById('home-center-column');
    var trendingContainer = document.getElementById('home-trending');
    
    if (!latestContainer || !centerContainer || !trendingContainer) return;

    fetch('http://localhost:3001/api/articles')
      .then(function(res) { return res.json(); })
      .then(function(articles) {
        if (articles.length === 0) return;

        // Populate WORLD_ARTICLES so the modal works
        articles.forEach(function(art) {
          if (!window.WORLD_ARTICLES) window.WORLD_ARTICLES = {};
          window.WORLD_ARTICLES[art.title] = { category: art.category, byline: art.byline, img: art.imageUrl, body: art.body };
        });

        // Latest News (Left column) - Take first 4
        latestContainer.innerHTML = '';
        articles.slice(0, 4).forEach(function(art, idx) {
          if (idx > 0) latestContainer.insertAdjacentHTML('beforeend', '<hr class="border-separator"/>');
          var html = `
            <article class="group cursor-pointer" onclick="openArticleModal('${art.title}')">
              <div class="flex items-center text-meta-text font-label-sm text-label-sm mb-1 uppercase tracking-widest space-x-2">
                <span class="text-breaking-red font-bold">${art.category}</span><span>•</span><span>Recent</span>
              </div>
              <h3 class="text-body-lg font-body-lg font-semibold text-primary group-hover:text-breaking-red transition-colors leading-tight">${art.title}</h3>
            </article>
          `;
          latestContainer.insertAdjacentHTML('beforeend', html);
        });

        // Center Column (Hero + 2 sub features)
        centerContainer.innerHTML = '';
        if (articles.length > 4) {
          var hero = articles[4];
          var heroHtml = `
            <article class="group cursor-pointer" onclick="openArticleModal('${hero.title}')">
              <div class="w-full aspect-video mb-4 overflow-hidden border border-separator">
                <img class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-700 ease-out" src="${hero.imageUrl || ''}" alt=""/>
              </div>
              <div class="flex items-center text-meta-text font-label-sm text-label-sm mb-3 uppercase tracking-widest space-x-3">
                <span class="text-breaking-red font-bold">${hero.category}</span>
                <span>By ${hero.byline}</span>
              </div>
              <h2 class="text-display-lg-mobile md:text-display-lg font-display-lg text-primary mb-4 group-hover:text-secondary-container transition-colors leading-tight">${hero.title}</h2>
            </article>
            <hr class="border-separator border-t-2"/>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6" id="home-sub-features"></div>
          `;
          centerContainer.insertAdjacentHTML('beforeend', heroHtml);

          var subContainer = document.getElementById('home-sub-features');
          articles.slice(5, 7).forEach(function(art) {
            var subHtml = `
              <article class="group cursor-pointer flex flex-col" onclick="openArticleModal('${art.title}')">
                <div class="w-full aspect-[16/9] mb-3 overflow-hidden border border-separator">
                  <img class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500" src="${art.imageUrl || ''}" alt=""/>
                </div>
                <span class="text-breaking-red font-label-sm text-label-sm uppercase tracking-widest font-bold mb-1">${art.category}</span>
                <h3 class="text-headline-md font-headline-md text-primary leading-tight group-hover:underline decoration-breaking-red decoration-2 underline-offset-4">${art.title}</h3>
              </article>
            `;
            subContainer.insertAdjacentHTML('beforeend', subHtml);
          });
        }

        // Trending (Right column)
        trendingContainer.innerHTML = '';
        articles.slice(7, 11).forEach(function(art, idx) {
          var number = (idx + 1).toString().padStart(2, '0');
          var classes = idx === 0 ? "relative pl-10 group cursor-pointer" : "relative pl-10 group cursor-pointer pt-6 border-t border-separator";
          var positionClass = idx === 0 ? "top-0" : "top-6";
          var html = `
            <li class="${classes}" onclick="openArticleModal('${art.title}')">
              <span class="absolute left-0 ${positionClass} text-headline-xl font-headline-xl text-outline-variant font-bold opacity-50 group-hover:text-breaking-red group-hover:opacity-100 transition-all leading-none">${number}</span>
              <div class="flex flex-col">
                <span class="text-breaking-red font-label-sm text-label-sm uppercase tracking-widest font-bold mb-1">${art.category}</span>
                <h4 class="text-body-md font-body-md font-semibold text-primary leading-snug group-hover:text-secondary-container transition-colors">${art.title}</h4>
              </div>
            </li>
          `;
          trendingContainer.insertAdjacentHTML('beforeend', html);
        });
      });
  }

  // Expose load function if pages want to call it manually
  window.loadDynamicArticles = loadDynamicArticles;
  window.loadHomeArticles = loadHomeArticles;

  /* ── Init ─────────────────────────────────────────────── */
  document.addEventListener('DOMContentLoaded', function () {
    initActiveNav();
    initSearchTriggers();
    wireSearchIcons();
    initArticleCards();
    checkAuthState();
    buildToast();
    initNewsletterForms();
    wireSubscribeButtons();
    loadDynamicArticles(); // Trigger data load on category pages
    loadHomeArticles(); // Trigger data load on homepage
  });

})();

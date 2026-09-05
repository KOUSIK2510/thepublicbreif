<?php
/**
 * Author Profile Page Template
 * URL: /author-profile/?writer=Author+Name
 *
 * Template Name: Author Profile
 */
get_header();

$author_name = isset($_GET['writer']) ? sanitize_text_field($_GET['writer']) : '';

// ── Find the WordPress user by display name ──────────────────────────────────
$wp_user   = null;
$author_id = 0;
if ( $author_name ) {
    $users = get_users([
        'search'         => '*' . $author_name . '*',
        'search_columns' => ['display_name'],
        'number'         => 1,
    ]);
    if ( ! empty($users) ) {
        $wp_user   = $users[0];
        $author_id = $wp_user->ID;
    }
}

// ── Pull author meta ─────────────────────────────────────────────────────────
$author_role     = $author_id ? get_user_meta($author_id, 'tpb_author_title', true) : '';
$author_desc     = $author_id ? get_user_meta($author_id, 'description', true) : '';
$ext_json        = $author_id ? get_user_meta($author_id, 'tpb_external_articles', true) : '';
$external_items  = [];
if ( $ext_json ) {
    $decoded = json_decode($ext_json, true);
    if ( is_array($decoded) ) $external_items = $decoded;
}
$author_role = $author_role ?: 'Contributor';

// ── WP article query ─────────────────────────────────────────────────────────
$args = [
    'post_type'      => ['post', 'tpb_article'],
    'post_status'    => 'publish',
    'posts_per_page' => 20,
];
if ( $author_id ) {
    $args['author'] = $author_id;
} else {
    $args['meta_query'] = [[
        'key'     => 'tpb_byline',
        'value'   => $author_name,
        'compare' => 'LIKE',
    ]];
}
$author_posts = $author_name ? new WP_Query($args) : null;
?>

<style>
/* ── Author Profile Page Styles ─────────────────────── */
.ap-wrap { font-family: 'Inter', sans-serif; }
.ap-playfair { font-family: 'Playfair Display', serif !important; }

/* HERO CARD */
.ap-hero {
  background: #0d0d0d;
  padding: 64px 24px 72px;
}
.ap-hero-inner {
  max-width: 900px; margin: 0 auto;
  display: flex; align-items: flex-start;
  gap: 48px;
}
.ap-avatar {
  width: 120px; height: 120px; border-radius: 50%;
  background: #b90c25;
  display: flex; align-items: center; justify-content: center;
  font-family: 'Playfair Display', serif;
  font-size: 2.8rem; font-weight: 700; color: #fff;
  flex-shrink: 0;
  border: 3px solid rgba(255,255,255,.15);
  overflow: hidden;
}
.ap-avatar img { width: 100%; height: 100%; object-fit: cover; }
.ap-hero-body { flex: 1; }
.ap-eyebrow {
  display: inline-block;
  font-size: 11px; font-weight: 700;
  letter-spacing: .35em; text-transform: uppercase;
  color: #e05a6a;
  margin-bottom: 12px;
}
.ap-name {
  font-family: 'Playfair Display', serif;
  font-size: clamp(2rem, 4vw, 3rem);
  font-weight: 800; line-height: 1.1;
  color: #fff; margin-bottom: 8px;
}
.ap-role {
  font-size: 12px; font-weight: 700;
  letter-spacing: .2em; text-transform: uppercase;
  color: #e05a6a; margin-bottom: 20px;
}
.ap-bio {
  font-size: 16px; line-height: 1.8;
  color: rgba(255,255,255,.65);
  margin-bottom: 20px; max-width: 580px;
}
.ap-wiki-link {
  display: inline-flex; align-items: center; gap: 6px;
  font-size: 13px; font-weight: 600;
  color: rgba(255,255,255,.5);
  text-decoration: none;
  transition: color .2s;
}
.ap-wiki-link:hover { color: #fff; }

/* SECTION */
.ap-section { padding: 72px 24px; }
.ap-section-inner { max-width: 1100px; margin: 0 auto; }
.ap-section-hd {
  display: flex; align-items: center; gap: 16px;
  margin-bottom: 40px;
  padding-bottom: 16px;
  border-bottom: 1px solid #e5e7eb;
}
.ap-section-eyebrow {
  font-size: 11px; font-weight: 700;
  letter-spacing: .35em; text-transform: uppercase;
  color: #b90c25;
  padding-bottom: 4px;
  border-bottom: 2px solid #b90c25;
}
.ap-section-title {
  font-family: 'Playfair Display', serif;
  font-size: 22px; font-weight: 700; color: #0d0d0d;
}
.ap-count {
  margin-left: auto;
  font-size: 12px; font-weight: 600;
  color: #6b7280;
  background: #f3f3f4;
  padding: 4px 12px;
}

/* ARTICLE GRID */
.ap-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 28px;
}
.ap-card {
  background: #fff;
  border: 1px solid #e5e7eb;
  border-top: 3px solid #b90c25;
  display: flex; flex-direction: column;
  transition: box-shadow .25s, transform .25s;
  text-decoration: none; color: inherit;
  overflow: hidden;
}
.ap-card:hover { box-shadow: 0 10px 36px rgba(0,0,0,.09); transform: translateY(-3px); }
.ap-card-img { aspect-ratio: 16/9; overflow: hidden; }
.ap-card-img img { width: 100%; height: 100%; object-fit: cover; transition: transform .5s; }
.ap-card:hover .ap-card-img img { transform: scale(1.04); }
.ap-card-body { padding: 20px; display: flex; flex-direction: column; gap: 8px; flex: 1; }
.ap-cat {
  font-size: 10px; font-weight: 700;
  letter-spacing: .15em; text-transform: uppercase; color: #b90c25;
}
.ap-card-title {
  font-family: 'Playfair Display', serif;
  font-size: 17px; font-weight: 700; line-height: 1.35; color: #0d0d0d;
}
.ap-card-excerpt { font-size: 13.5px; line-height: 1.65; color: #6b7280; }
.ap-card-meta {
  margin-top: auto; padding-top: 12px;
  border-top: 1px solid #f0f0f0;
  font-size: 12px; color: #9ca3af;
}

/* EXTERNAL ARTICLES */
.ap-ext-section { background: #f7f7f7; }
.ap-ext-list { display: flex; flex-direction: column; gap: 16px; }
.ap-ext-item {
  display: flex; align-items: flex-start; gap: 20px;
  background: #fff; border: 1px solid #e5e7eb;
  border-left: 4px solid #b90c25;
  padding: 20px 24px;
  text-decoration: none; color: inherit;
  transition: box-shadow .2s, transform .2s;
}
.ap-ext-item:hover { box-shadow: 0 6px 24px rgba(0,0,0,.07); transform: translateX(4px); }
.ap-ext-pub-badge {
  font-size: 11px; font-weight: 700;
  letter-spacing: .15em; text-transform: uppercase;
  color: #fff; background: #b90c25;
  padding: 4px 10px; white-space: nowrap; flex-shrink: 0;
  align-self: flex-start; margin-top: 2px;
}
.ap-ext-body { flex: 1; }
.ap-ext-title {
  font-family: 'Playfair Display', serif;
  font-size: 18px; font-weight: 700;
  line-height: 1.3; color: #0d0d0d;
  margin-bottom: 6px;
}
.ap-ext-date { font-size: 12px; color: #9ca3af; }
.ap-ext-arrow {
  align-self: center; color: #b90c25;
  font-size: 20px; flex-shrink: 0;
}

/* EMPTY STATE */
.ap-empty {
  text-align: center; padding: 48px 24px;
  color: #6b7280; font-style: italic;
}

/* NO AUTHOR */
.ap-no-author {
  text-align: center; padding: 96px 24px;
  color: #b90c25; font-size: 18px;
}

@media (max-width: 900px) {
  .ap-hero-inner { flex-direction: column; align-items: center; text-align: center; gap: 28px; }
  .ap-bio { max-width: 100%; }
  .ap-grid { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 600px) {
  .ap-grid { grid-template-columns: 1fr; }
  .ap-section { padding: 48px 20px; }
  .ap-hero { padding: 48px 20px; }
}
</style>

<div class="ap-wrap">

<?php if ( ! $author_name ) : ?>
  <div class="ap-no-author">No author specified.</div>

<?php else : ?>

  <!-- ── HERO ──────────────────────────────────────────── -->
  <section class="ap-hero">
    <div class="ap-hero-inner">

      <!-- Avatar -->
      <div class="ap-avatar" id="ap-avatar">
        <img id="ap-avatar-img" src="" alt="" style="display:none;"/>
        <span id="ap-initials"><?php
          $parts = explode(' ', trim($author_name));
          echo esc_html(strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : '')));
        ?></span>
      </div>

      <!-- Info -->
      <div class="ap-hero-body">
        <span class="ap-eyebrow">The Public Brief — Author</span>
        <h1 class="ap-name ap-playfair"><?php echo esc_html($author_name); ?></h1>
        <p class="ap-role"><?php echo esc_html($author_role); ?></p>
        <p class="ap-bio" id="ap-bio">Loading biography…</p>
        <a id="ap-wiki-link" href="#" target="_blank" rel="noopener" class="ap-wiki-link" style="display:none;">
          <span class="material-symbols-outlined" style="font-size:18px;">menu_book</span>
          Read more on Wikipedia
        </a>
      </div>

    </div>
  </section>

  <!-- ── ARTICLES ON THE PUBLIC BRIEF ──────────────────── -->
  <section class="ap-section" style="background:#fff;">
    <div class="ap-section-inner">

      <div class="ap-section-hd">
        <div>
          <div class="ap-section-eyebrow" style="margin-bottom:6px;">Portfolio</div>
          <div class="ap-section-title ap-playfair">Articles on The Public Brief</div>
        </div>
        <?php if ($author_posts && $author_posts->found_posts > 0) : ?>
          <span class="ap-count"><?php echo $author_posts->found_posts; ?> Article<?php echo $author_posts->found_posts !== 1 ? 's' : ''; ?></span>
        <?php endif; ?>
      </div>

      <?php if ($author_posts && $author_posts->have_posts()) : ?>
        <div class="ap-grid">
          <?php while ($author_posts->have_posts()) : $author_posts->the_post();
            $cats     = get_the_category();
            $cat_name = $cats ? $cats[0]->name : 'Article';
          ?>
          <a class="ap-card" href="<?php the_permalink(); ?>">
            <?php if (has_post_thumbnail()) : ?>
              <div class="ap-card-img">
                <?php the_post_thumbnail('medium_large'); ?>
              </div>
            <?php endif; ?>
            <div class="ap-card-body">
              <span class="ap-cat"><?php echo esc_html($cat_name); ?></span>
              <div class="ap-card-title ap-playfair"><?php the_title(); ?></div>
              <div class="ap-card-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 18); ?></div>
              <div class="ap-card-meta"><?php echo get_the_date('d M Y'); ?></div>
            </div>
          </a>
          <?php endwhile; wp_reset_postdata(); ?>
        </div>
      <?php else : ?>
        <div class="ap-empty">No published articles on The Public Brief yet.</div>
      <?php endif; ?>

    </div>
  </section>

  <!-- ── ALSO PUBLISHED IN (EXTERNAL) ──────────────────── -->
  <?php if ( ! empty($external_items) ) : ?>
  <section class="ap-section ap-ext-section">
    <div class="ap-section-inner">

      <div class="ap-section-hd">
        <div>
          <div class="ap-section-eyebrow" style="margin-bottom:6px;">Beyond The Brief</div>
          <div class="ap-section-title ap-playfair">Also Published In</div>
        </div>
        <span class="ap-count"><?php echo count($external_items); ?> External Article<?php echo count($external_items) !== 1 ? 's' : ''; ?></span>
      </div>

      <div class="ap-ext-list">
        <?php foreach ($external_items as $item) :
          $title       = isset($item['title'])       ? $item['title']       : 'Untitled';
          $url         = isset($item['url'])         ? $item['url']         : '#';
          $publication = isset($item['publication']) ? $item['publication'] : 'External';
          $date        = isset($item['date'])        ? $item['date']        : '';
          $date_fmt    = $date ? date('d M Y', strtotime($date)) : '';
        ?>
        <a class="ap-ext-item" href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener">
          <span class="ap-ext-pub-badge"><?php echo esc_html($publication); ?></span>
          <div class="ap-ext-body">
            <div class="ap-ext-title ap-playfair"><?php echo esc_html($title); ?></div>
            <?php if ($date_fmt) : ?>
              <div class="ap-ext-date"><?php echo esc_html($date_fmt); ?></div>
            <?php endif; ?>
          </div>
          <span class="material-symbols-outlined ap-ext-arrow">open_in_new</span>
        </a>
        <?php endforeach; ?>
      </div>

    </div>
  </section>
  <?php endif; ?>

<?php endif; ?>
</div><!-- /.ap-wrap -->

<script>
(function() {
  var name = <?php echo json_encode($author_name); ?>;
  if (!name) return;

  var bioEl      = document.getElementById('ap-bio');
  var imgEl      = document.getElementById('ap-avatar-img');
  var initialsEl = document.getElementById('ap-initials');
  var wikiLink   = document.getElementById('ap-wiki-link');

  var customBio = <?php echo json_encode($author_desc); ?>;
  var fallbackBio = 'Independent journalist and contributor to The Public Brief, committed to credible, fact-based reporting on issues shaping Indian society and public discourse.';

  // Initial Bio setup: Use custom WordPress bio if available, otherwise fallback
  bioEl.textContent = (customBio && customBio.trim() !== '') ? customBio : fallbackBio;

  fetch('https://en.wikipedia.org/api/rest_v1/page/summary/' + encodeURIComponent(name))
    .then(function(r) { if (!r.ok) throw new Error(); return r.json(); })
    .then(function(d) {
      // If Wikipedia returns a disambiguation page, ignore it completely
      if (d.type === 'disambiguation') return;

      // Only overwrite the bio with Wikipedia if the user didn't write a custom bio in WordPress
      if ((!customBio || customBio.trim() === '') && d.extract) {
        bioEl.textContent = d.extract;
      }

      // Photo
      if (d.thumbnail && d.thumbnail.source) {
        imgEl.src = d.thumbnail.source;
        imgEl.alt = name;
        imgEl.style.display = 'block';
        initialsEl.style.display = 'none';
      }

      // Wikipedia link
      if (d.content_urls && d.content_urls.desktop) {
        wikiLink.href = d.content_urls.desktop.page;
        wikiLink.style.display = 'inline-flex';
      }
    })
    .catch(function() {
      // Do nothing on error, the bioEl is already set correctly above
    });
})();
</script>

<?php get_footer(); ?>

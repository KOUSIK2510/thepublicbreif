<?php
/**
 * Author Profile Page Template
 * URL: /author-profile/?name=Author+Name
 *
 * Template Name: Author Profile
 */
get_header();

$author_name = isset($_GET['name']) ? sanitize_text_field($_GET['name']) : '';
?>

<main class="flex-grow w-full max-w-[1280px] mx-auto px-4 md:px-16 py-12">

  <?php if (!$author_name) : ?>
    <p class="text-breaking-red text-center py-24 text-xl">No author name provided.</p>
  <?php else : ?>

  <!-- Author Profile Card -->
  <div class="bg-white border border-separator rounded-2xl p-8 md:p-12 mb-16 shadow-sm flex flex-col md:flex-row gap-8 items-start">
    <div id="author-avatar-wrap" class="w-32 h-32 md:w-48 md:h-48 rounded-full overflow-hidden bg-surface-container shrink-0 border-4 border-surface-gray shadow-inner flex items-center justify-center">
      <img id="author-img" src="" alt="<?php echo esc_attr($author_name); ?>" style="display:none;width:100%;height:100%;object-fit:cover;" />
      <div id="author-initials" style="font-family:'Playfair Display',serif;font-size:3rem;font-weight:700;color:#000;"></div>
    </div>
    <div class="flex flex-col flex-grow">
      <h1 style="font-family:'Playfair Display',serif;font-size:3rem;font-weight:700;color:#000;margin-bottom:0.5rem;">
        <?php echo esc_html($author_name); ?>
      </h1>
      <p style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#B00020;margin-bottom:1.5rem;">Columnist / Contributor</p>
      <p id="author-bio" style="font-size:1.1rem;line-height:1.75;color:#444748;margin-bottom:1.5rem;">Loading biography...</p>
      <a id="author-wiki-link" href="#" target="_blank" rel="noopener"
         style="display:none;align-items:center;gap:8px;color:#000;font-weight:600;text-decoration:none;">
        <span class="material-symbols-outlined" style="font-size:20px;">menu_book</span> Read more on Wikipedia
      </a>
    </div>
  </div>

  <!-- WordPress Articles by this Author -->
  <?php
  // Find WP user by display name
  $wp_users = get_users(['search' => '*' . $author_name . '*', 'search_columns' => ['display_name']]);
  $author_id = !empty($wp_users) ? $wp_users[0]->ID : 0;

  $args = [
    'post_type'      => ['post', 'tpb_article'],
    'post_status'    => 'publish',
    'posts_per_page' => 12,
  ];
  if ($author_id) {
    $args['author'] = $author_id;
  } else {
    // Fallback: search by byline meta
    $args['meta_query'] = [[
      'key'     => 'tpb_byline',
      'value'   => $author_name,
      'compare' => 'LIKE',
    ]];
  }
  $author_posts = new WP_Query($args);
  ?>

  <h2 style="font-family:'Playfair Display',serif;font-size:1.75rem;font-weight:700;color:#000;border-bottom:1px solid #E5E7EB;padding-bottom:1rem;margin-bottom:2rem;">
    Articles by this Author
  </h2>

  <?php if ($author_posts->have_posts()) : ?>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
    <?php while ($author_posts->have_posts()) : $author_posts->the_post();
      $cats = get_the_category();
      $cat_name = $cats ? $cats[0]->name : 'News';
      $byline = get_post_meta(get_the_ID(), 'tpb_byline', true) ?: 'By ' . get_the_author();
    ?>
    <article class="group flex flex-col bg-white border border-separator rounded-xl overflow-hidden hover:shadow-lg transition-all duration-300">
      <?php if (has_post_thumbnail()) : ?>
        <div class="overflow-hidden" style="aspect-ratio:16/9;">
          <a href="<?php the_permalink(); ?>">
            <?php the_post_thumbnail('medium_large', ['style' => 'width:100%;height:100%;object-fit:cover;transition:transform 0.5s;']); ?>
          </a>
        </div>
      <?php endif; ?>
      <div style="padding:1.5rem;display:flex;flex-direction:column;gap:0.75rem;flex-grow:1;">
        <span style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#B00020;"><?php echo esc_html($cat_name); ?></span>
        <h3 style="font-family:'Playfair Display',serif;font-size:1.2rem;font-weight:700;line-height:1.4;color:#000;">
          <a href="<?php the_permalink(); ?>" style="text-decoration:none;color:inherit;"><?php the_title(); ?></a>
        </h3>
        <p style="font-size:0.875rem;color:#444748;"><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
        <div style="margin-top:auto;padding-top:1rem;border-top:1px solid #E5E7EB;font-size:0.75rem;color:#6B7280;"><?php echo get_the_date(); ?></div>
      </div>
    </article>
    <?php endwhile; wp_reset_postdata(); ?>
  </div>
  <?php else : ?>
    <p style="color:#6B7280;font-style:italic;">No published articles found for this author yet.</p>
  <?php endif; ?>

  <?php endif; ?>
</main>

<script>
(function() {
  var name = <?php echo json_encode($author_name); ?>;
  if (!name) return;

  var initials = name.split(' ').map(function(n){return n[0];}).join('').substring(0,2).toUpperCase();
  document.getElementById('author-initials').textContent = initials;

  // Wikipedia API
  fetch('https://en.wikipedia.org/api/rest_v1/page/summary/' + encodeURIComponent(name))
    .then(function(r) { if (!r.ok) throw new Error(); return r.json(); })
    .then(function(d) {
      if (d.extract) document.getElementById('author-bio').textContent = d.extract;
      if (d.thumbnail && d.thumbnail.source) {
        var img = document.getElementById('author-img');
        img.src = d.thumbnail.source;
        img.style.display = 'block';
        document.getElementById('author-initials').style.display = 'none';
      }
      if (d.content_urls && d.content_urls.desktop) {
        var link = document.getElementById('author-wiki-link');
        link.href = d.content_urls.desktop.page;
        link.style.display = 'inline-flex';
      }
    })
    .catch(function() {
      document.getElementById('author-bio').textContent = 'Independent journalist and contributor to The Public Brief. They cover critical issues shaping the modern world with in-depth analysis and compelling perspectives.';
    });
})();
</script>

<?php get_footer(); ?>

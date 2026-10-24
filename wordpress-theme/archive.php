<?php get_header(); ?>

<?php
$cat       = get_queried_object();
$cat_slug  = $cat ? $cat->slug : '';
// Display 'India' for the national category
$name_map  = [
  'national'      => 'India',
  'world'         => 'World',
  'sports'        => 'Sports',
  'entertainment' => 'Entertainment',
  'opinion'       => 'Opinion',
];
$cat_name  = $name_map[$cat_slug] ?? ($cat ? $cat->name : 'Articles');

// Dynamic category briefings
$tickers = [
  'sports' => [
    'badge' => 'Sports Desk',
    'icon'  => 'sports_soccer',
    'items' => [
      'Premier League: Man City 2–1 Arsenal at Etihad',
      'IND vs AUS: Border-Gavaskar Trophy updates from Adelaide',
      'ATP Finals: Alcaraz advances to Semifinals in straight sets',
      'Champions League: Quarterfinal draw announced',
      'Cricket World Cup: Squad rosters confirmed'
    ]
  ],
  'world' => [
    'badge' => 'Global Dispatch',
    'icon'  => 'public',
    'items' => [
      'Geneva Accords: Diplomatic talks establish safe maritime corridor',
      'UN General Assembly convenes emergency session on regional security',
      'Global energy markets transition as renewable investments set record',
      'Bilateral trade pact agreed between major European & Asian economies',
      'International Climate Summit concludes with historic adaptation pact'
    ]
  ],
  'entertainment' => [
    'badge' => 'Culture & Arts',
    'icon'  => 'movie',
    'items' => [
      'The Celluloid Renaissance: Why analog film is dominating festivals',
      'Cannes Film Festival unveils prestigious official selection',
      'Global Box Office: International cinema captures record market share',
      'Indie Music Spotlight: Emerging singer-songwriters redefining the sound',
      'National Arts & Literature Foundation announces fellowship laureates'
    ]
  ],
  'national' => [
    'badge' => 'India Briefing',
    'icon'  => 'flag',
    'items' => [
      'Parliament opens session with debates on public infrastructure & tech',
      'Supreme Court constitutional bench deliberates key governance directive',
      'High-speed rail corridor Phase II construction milestones cleared',
      'Reserve Bank monetary policy panel issues quarterly economic outlook',
      'Digital public infrastructure expansion brings rural health coverage'
    ]
  ],
];
$ticker = $tickers[$cat_slug] ?? null;
?>

<?php if ($ticker) : ?>
<!-- Category Ticker Briefing -->
<div class="bg-primary text-white relative overflow-hidden border-b border-gray-800" style="height:38px;">
  <div class="flex items-center h-full max-w-[1280px] mx-auto px-4 md:px-12">
    <div class="bg-breaking-red text-white px-3.5 h-full flex items-center gap-1.5 font-bold uppercase tracking-widest flex-shrink-0" style="font-size:11px;">
      <span class="material-symbols-outlined" style="font-size:14px;"><?php echo esc_attr($ticker['icon']); ?></span>
      <?php echo esc_html($ticker['badge']); ?>
    </div>
    <div class="ticker-wrap flex-1 h-full flex items-center border-l border-gray-700 pl-4 overflow-hidden">
      <div class="ticker">
        <?php foreach ($ticker['items'] as $item) : ?>
          <span class="ticker-item text-[12px] font-medium text-gray-200">
            <?php echo esc_html($item); ?>
            &nbsp;<span class="text-breaking-red">◆</span>&nbsp;
          </span>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="hidden md:flex items-center border-l border-gray-700 h-full pl-4 flex-shrink-0 text-[11px] text-gray-400 font-medium">
      <?php echo date('D, d M Y'); ?>
    </div>
  </div>
</div>
<?php endif; ?>

<main class="flex-grow w-full max-w-[1280px] mx-auto px-4 md:px-12 py-8 md:py-12">

  <?php
  // Collect all queried posts to build rich editorial magazine hierarchy
  $posts_list = [];
  if (have_posts()) {
    while (have_posts()) {
      the_post();
      $byline     = get_post_meta(get_the_ID(), 'tpb_byline', true) ?: 'By ' . get_the_author();
      $clean_name = trim(str_replace('By ', '', $byline));
      $read_time  = get_post_meta(get_the_ID(), 'tpb_read_time', true) ?: '5 min read';
      $posts_list[] = [
        'id'         => get_the_ID(),
        'title'      => get_the_title(),
        'permalink'  => get_permalink(),
        'excerpt'    => get_the_excerpt(),
        'date'       => get_the_date(),
        'byline'     => $byline,
        'clean_name' => $clean_name,
        'read_time'  => $read_time,
        'thumb'      => get_the_post_thumbnail(get_the_ID(), 'large', ['class' => 'w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700']),
        'has_thumb'  => has_post_thumbnail(),
        'author_id'  => get_the_author_meta('ID'),
      ];
    }
  }
  $post_count = count($posts_list);
  ?>

  <?php if ($post_count > 0) : ?>

    <!-- 1. Lead Hero Story -->
    <?php $p0 = $posts_list[0]; ?>
    <section class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-10 pb-10 md:pb-12 border-b border-separator mb-10 md:mb-12">
      <!-- Image (7 cols) -->
      <div class="lg:col-span-7">
        <?php if ($p0['has_thumb']) : ?>
          <a href="<?php echo esc_url($p0['permalink']); ?>" class="block overflow-hidden group border border-separator/60 shadow-sm relative">
            <div class="relative aspect-[16/10] overflow-hidden bg-gray-100">
              <?php echo $p0['thumb']; ?>
              <span class="absolute top-4 left-4 bg-breaking-red text-white text-[11px] font-bold uppercase tracking-widest px-3 py-1 shadow-md">
                Featured <?php echo esc_html($cat_name); ?>
              </span>
            </div>
          </a>
        <?php endif; ?>
      </div>

      <!-- Lead Info (5 cols) -->
      <div class="lg:col-span-5 flex flex-col justify-center">
        <div class="flex items-center gap-3 mb-3">
          <span class="text-breaking-red font-label-sm text-label-sm uppercase tracking-widest font-bold"><?php echo esc_html($cat_name); ?></span>
          <span class="text-meta-text text-sm">&bull;</span>
          <span class="text-meta-text text-sm"><?php echo esc_html($p0['date']); ?></span>
        </div>
        <h1 class="text-2xl sm:text-3xl lg:text-[2.2rem] font-display-lg text-primary mb-4 leading-[1.2]" style="font-family:'Playfair Display',serif;">
          <a href="<?php echo esc_url($p0['permalink']); ?>" class="hover:text-breaking-red transition-colors">
            <?php echo esc_html($p0['title']); ?>
          </a>
        </h1>
        <p class="text-[15px] sm:text-base font-body-lg text-on-surface-variant leading-relaxed mb-6 line-clamp-3 md:line-clamp-4">
          <?php echo esc_html($p0['excerpt']); ?>
        </p>
        <div class="flex items-center gap-3 pt-4 border-t border-separator/80 text-sm text-meta-text">
          <?php if ($p0['author_id']) : ?>
            <a href="<?php echo esc_url(home_url('/author-profile/?writer=' . urlencode($p0['clean_name']))); ?>">
              <?php echo get_avatar($p0['author_id'], 40, '', '', ['class' => 'rounded-full border border-separator']); ?>
            </a>
          <?php endif; ?>
          <div>
            <a href="<?php echo esc_url(home_url('/author-profile/?writer=' . urlencode($p0['clean_name']))); ?>"
               class="font-semibold text-primary hover:text-breaking-red transition-colors block leading-tight">
              <?php echo esc_html($p0['byline']); ?>
            </a>
            <span class="text-xs text-meta-text"><?php echo esc_html($p0['read_time']); ?></span>
          </div>
        </div>
      </div>
    </section>

    <!-- 2. Secondary Spotlight Stories (Posts 2 & 3) -->
    <?php if ($post_count >= 2) : ?>
      <section class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 pb-10 md:pb-12 border-b border-separator mb-10 md:mb-12">
        <?php for ($i = 1; $i < min(3, $post_count); $i++) : $p = $posts_list[$i]; ?>
          <article class="group bg-surface-gray border border-separator p-5 sm:p-6 flex flex-col sm:flex-row gap-5 hover:shadow-md transition-all">
            <?php if ($p['has_thumb']) : ?>
              <a href="<?php echo esc_url($p['permalink']); ?>" class="sm:w-2/5 aspect-[16/10] sm:aspect-square overflow-hidden flex-shrink-0 bg-gray-200">
                <?php echo get_the_post_thumbnail($p['id'], 'medium', ['class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-500']); ?>
              </a>
            <?php endif; ?>
            <div class="flex flex-col justify-between flex-grow">
              <div>
                <span class="text-breaking-red text-[11px] font-bold uppercase tracking-widest block mb-1.5"><?php echo esc_html($cat_name); ?></span>
                <h2 class="text-lg sm:text-xl font-bold text-primary leading-snug mb-2 group-hover:text-breaking-red transition-colors" style="font-family:'Playfair Display',serif;">
                  <a href="<?php echo esc_url($p['permalink']); ?>"><?php echo esc_html($p['title']); ?></a>
                </h2>
                <p class="text-sm text-on-surface-variant line-clamp-2 mb-3 leading-relaxed"><?php echo esc_html($p['excerpt']); ?></p>
              </div>
              <div class="text-xs text-meta-text flex items-center gap-2 pt-2 border-t border-separator/60">
                <span class="font-semibold text-primary"><?php echo esc_html($p['byline']); ?></span>
                <span>&bull;</span>
                <span><?php echo esc_html($p['read_time']); ?></span>
              </div>
            </div>
          </article>
        <?php endfor; ?>
      </section>
    <?php endif; ?>

    <!-- 3. Magazine Stream (8 cols) + Curated Sidebar (4 cols) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">

      <!-- Left 8 cols: Editorial Stream -->
      <div class="lg:col-span-8 flex flex-col gap-6">
        <div class="border-b-2 border-primary pb-2 flex items-center justify-between">
          <h3 class="text-primary font-bold uppercase tracking-wider text-sm flex items-center gap-2">
            <span class="w-2.5 h-2.5 bg-breaking-red inline-block"></span>
            Latest in <?php echo esc_html($cat_name); ?>
          </h3>
          <span class="text-xs text-meta-text">Curated reporting</span>
        </div>

        <?php if ($post_count > 3) : ?>
          <div class="divide-y divide-separator">
            <?php for ($i = 3; $i < $post_count; $i++) : $p = $posts_list[$i]; ?>
              <article class="group py-6 first:pt-2 flex flex-col sm:flex-row gap-5 items-start">
                <?php if ($p['has_thumb']) : ?>
                  <a href="<?php echo esc_url($p['permalink']); ?>" class="w-full sm:w-48 aspect-[16/10] overflow-hidden flex-shrink-0 border border-separator/60 bg-gray-100">
                    <?php echo get_the_post_thumbnail($p['id'], 'medium', ['class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-500']); ?>
                  </a>
                <?php endif; ?>
                <div class="flex-grow flex flex-col justify-between">
                  <div>
                    <div class="flex items-center gap-2 text-xs text-meta-text mb-1">
                      <span class="text-breaking-red font-bold uppercase tracking-widest"><?php echo esc_html($cat_name); ?></span>
                      <span>&bull;</span>
                      <span><?php echo esc_html($p['date']); ?></span>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-primary leading-snug group-hover:text-breaking-red transition-colors mb-2" style="font-family:'Playfair Display',serif;">
                      <a href="<?php echo esc_url($p['permalink']); ?>"><?php echo esc_html($p['title']); ?></a>
                    </h3>
                    <p class="text-sm text-on-surface-variant leading-relaxed line-clamp-2 mb-3"><?php echo esc_html($p['excerpt']); ?></p>
                  </div>
                  <div class="text-xs text-meta-text flex items-center gap-2">
                    <a href="<?php echo esc_url(home_url('/author-profile/?writer=' . urlencode($p['clean_name']))); ?>" class="font-semibold text-primary hover:text-breaking-red transition-colors">
                      <?php echo esc_html($p['byline']); ?>
                    </a>
                    <span>&bull;</span>
                    <span><?php echo esc_html($p['read_time']); ?></span>
                  </div>
                </div>
              </article>
            <?php endfor; ?>
          </div>
        <?php else : ?>
          <p class="text-meta-text text-sm py-6 italic">You have viewed all featured dispatches in this section.</p>
        <?php endif; ?>

        <!-- Pagination -->
        <div class="mt-8 pt-6 border-t border-separator">
          <?php tpb_pagination(); ?>
        </div>
      </div>

      <!-- Right 4 cols: Curated Sidebar -->
      <aside class="lg:col-span-4 flex flex-col gap-8">

        <!-- Trending in Category -->
        <div class="bg-white border border-separator p-6 shadow-sm">
          <div class="border-b-2 border-primary pb-2 mb-4 flex items-center justify-between">
            <h4 class="text-primary font-bold uppercase tracking-wider text-xs flex items-center gap-2">
              <span class="material-symbols-outlined text-breaking-red text-[16px]">trending_up</span>
              Trending in <?php echo esc_html($cat_name); ?>
            </h4>
          </div>
          <div class="space-y-4">
            <?php
            $trending_args = [
              'post_type'      => ['post','tpb_article'],
              'posts_per_page' => 5,
              'category_name'  => $cat_slug,
              'post_status'    => 'publish',
            ];
            $trending_q = new WP_Query($trending_args);
            $rank = 0;
            if ($trending_q->have_posts()) :
              while ($trending_q->have_posts()) : $trending_q->the_post();
                $rank++;
            ?>
              <div class="flex items-start gap-3 pb-3 border-b border-separator/60 last:border-0 last:pb-0 group">
                <span class="text-lg font-black text-breaking-red font-mono leading-none pt-0.5">0<?php echo $rank; ?></span>
                <div>
                  <h5 class="text-sm font-semibold text-primary leading-snug group-hover:text-breaking-red transition-colors">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                  </h5>
                  <span class="text-[11px] text-meta-text mt-1 block"><?php echo get_the_date('M j, Y'); ?></span>
                </div>
              </div>
            <?php
              endwhile;
              wp_reset_postdata();
            endif;
            ?>
          </div>
        </div>

        <!-- Curated Category Briefing Card -->
        <div class="bg-primary text-white p-6 relative overflow-hidden">
          <div class="absolute -right-4 -bottom-4 opacity-10">
            <span class="material-symbols-outlined" style="font-size:120px;">newspaper</span>
          </div>
          <span class="bg-breaking-red text-white text-[10px] font-bold uppercase tracking-widest px-2.5 py-0.5 inline-block mb-3">Newsletter</span>
          <h4 class="text-xl font-bold mb-2" style="font-family:'Playfair Display',serif;">The Public Brief: <?php echo esc_html($cat_name); ?></h4>
          <p class="text-gray-300 text-xs leading-relaxed mb-4">
            Independent, verified reporting and essential analysis from our <?php echo esc_html($cat_name); ?> desk delivered straight to your inbox.
          </p>
          <form class="space-y-2" onsubmit="event.preventDefault(); alert('Thank you for subscribing to The Public Brief!');">
            <input type="email" placeholder="Enter your email address" required
                   class="w-full px-3 py-2 text-xs text-black bg-white focus:outline-none focus:ring-2 focus:ring-breaking-red" />
            <button type="submit"
                    class="w-full bg-breaking-red hover:bg-secondary-container text-white py-2 text-xs font-bold uppercase tracking-wider transition-colors">
              Subscribe Free
            </button>
          </form>
        </div>

        <!-- Section Trust Statement -->
        <div class="border border-separator p-5 bg-surface-gray text-xs text-meta-text leading-relaxed">
          <div class="flex items-center gap-2 text-primary font-bold uppercase tracking-wider text-[11px] mb-2">
            <span class="material-symbols-outlined text-breaking-red text-[16px]">verified</span>
            Verified Journalism
          </div>
          Every story published in our <?php echo esc_html($cat_name); ?> section undergoes strict editorial verification and fact-checking. We accept no sponsored bias.
        </div>

      </aside>

    </div>

  <?php else : ?>
    <div class="text-center py-24">
      <span class="material-symbols-outlined text-gray-300 text-5xl mb-3 block">article</span>
      <p class="text-primary font-bold text-lg mb-1">No articles found in <?php echo esc_html($cat_name); ?></p>
      <p class="text-meta-text text-sm">Please check back soon for our latest reporting and dispatches.</p>
    </div>
  <?php endif; ?>

</main>

<?php get_footer(); ?>

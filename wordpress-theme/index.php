<?php get_header(); ?>

<!-- Breaking News Ticker -->
<div class="bg-primary text-white relative overflow-hidden" style="height:36px;">
  <div class="flex items-center h-full">
    <div class="bg-breaking-red text-white px-4 h-full flex items-center gap-2 font-bold uppercase tracking-widest flex-shrink-0" style="min-width:130px;font-size:11px;">
      <span class="material-symbols-outlined" style="font-size:14px;">notifications_active</span>
      Breaking
    </div>
    <div class="ticker-wrap flex-1 h-full flex items-center border-l border-gray-700">
      <div class="ticker">
        <?php
        $ticker_args = [ 'post_type' => ['post','tpb_article'], 'posts_per_page' => 8, 'post_status' => 'publish' ];
        $ticker_query = new WP_Query($ticker_args);
        while ($ticker_query->have_posts()) : $ticker_query->the_post(); ?>
          <span class="ticker-item" style="font-size:12px;font-weight:500;">
            <a href="<?php the_permalink(); ?>" class="hover:text-breaking-red transition-colors"><?php the_title(); ?></a>
            &nbsp;<span style="color:#4B5563;">◆</span>&nbsp;
          </span>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>
    </div>
    <div class="hidden md:flex items-center border-l border-gray-700 h-full px-4 flex-shrink-0" style="font-size:11px;color:#9CA3AF;">
      <?php echo date('D, d M Y'); ?>
    </div>
  </div>
</div>

<!-- Main Content -->
<main class="flex-grow w-full max-w-[1280px] mx-auto px-4 md:px-12 py-8 md:py-16">

  <?php
  // ── Hero / Featured Story ─────────────────────────────────────────────────
  $hero_args = [ 'post_type' => ['post','tpb_article'], 'posts_per_page' => 1, 'post_status' => 'publish' ];
  $hero_query = new WP_Query($hero_args);
  if ($hero_query->have_posts()) : $hero_query->the_post();
    $cats = get_the_category();
    $cat_name = $cats ? $cats[0]->name : 'News';
    $byline = get_post_meta(get_the_ID(), 'tpb_byline', true) ?: 'By ' . get_the_author();
    $clean_name = trim(str_replace('By ', '', $byline));
    $read_time = get_post_meta(get_the_ID(), 'tpb_read_time', true) ?: '5 min read';
  ?>
  <section class="grid grid-cols-1 lg:grid-cols-12 gap-6 md:gap-12 mb-8 md:mb-16 pb-8 md:pb-16 border-b border-separator">
    <!-- Featured image -->
    <div class="lg:col-span-7">
      <?php if (has_post_thumbnail()) : ?>
        <a href="<?php the_permalink(); ?>" class="block overflow-hidden group">
          <div class="relative aspect-[16/9] overflow-hidden">
            <?php the_post_thumbnail('large', ['class' => 'w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700']); ?>
          </div>
        </a>
      <?php endif; ?>
    </div>

    <!-- Hero text -->
    <div class="lg:col-span-5 flex flex-col justify-center">
      <span class="text-breaking-red font-label-sm text-label-sm uppercase tracking-widest font-bold mb-3 block"><?php echo esc_html($cat_name); ?></span>
      <h1 class="text-[24px] md:text-4xl font-display-lg text-primary mb-3 md:mb-5 leading-tight" style="font-family:'Playfair Display',serif;">
        <a href="<?php the_permalink(); ?>" class="hover:text-breaking-red transition-colors"><?php the_title(); ?></a>
      </h1>
      <p class="text-[15px] md:text-body-lg font-body-lg text-on-surface-variant leading-relaxed mb-4 md:mb-6"><?php echo esc_html(get_the_excerpt()); ?></p>
      <div class="flex items-center gap-3 text-sm text-meta-text">
        <?php if (get_the_author_meta('ID')) : ?>
          <a href="<?php echo esc_url(home_url('/author-profile/?writer=' . urlencode($clean_name))); ?>">
            <?php echo get_avatar(get_the_author_meta('ID'), 44, '', '', ['class' => 'rounded-full']); ?>
          </a>
        <?php endif; ?>
        <div>
          <a href="<?php echo esc_url(home_url('/author-profile/?writer=' . urlencode($clean_name))); ?>"
             class="font-semibold text-primary hover:text-breaking-red transition-colors block">
            <?php echo esc_html($byline); ?>
          </a>
          <span><?php echo get_the_date(); ?> &bull; <?php echo esc_html($read_time); ?></span>
        </div>
      </div>
    </div>
  </section>
  <?php endif; wp_reset_postdata(); ?>

  <!-- Section Headers & Article Grids -->
  <?php
  // Display 'India' label for the national category, keep slug as 'national'
  $sections = [
    'national'      => 'India',
    'world'         => 'World',
    'sports'        => 'Sports',
    'entertainment' => 'Entertainment',
    'opinion'       => 'Opinion',
  ];
  $section_index = 0;
  foreach ($sections as $slug => $display_name) :
    $section_args = [
      'post_type'      => ['post','tpb_article'],
      'posts_per_page' => 3,
      'post_status'    => 'publish',
      'category_name'  => $slug,
    ];
    $section_query = new WP_Query($section_args);
    if (!$section_query->have_posts()) continue;
    $section_index++;
  ?>

  <?php if ($section_index === 3) : // Support strip after 2nd section ?>
  <section class="mb-8 md:mb-12 py-5 px-4 md:px-6 flex flex-col items-center justify-between gap-4 text-center md:text-left md:flex-row" style="background:linear-gradient(135deg,#1a0005 0%,#3d0010 100%);border-top:2px solid #B00020;">
    <div class="flex items-center gap-4">
      <span class="material-symbols-outlined text-breaking-red" style="font-size:32px;">favorite</span>
      <div>
        <h3 class="text-white font-bold uppercase tracking-widest" style="font-size:14px;">Support Our Journalism</h3>
        <p class="text-gray-400" style="font-size:12px;margin-top:2px;">Independent journalism depends on readers like you.</p>
      </div>
    </div>
    <a href="<?php echo esc_url(home_url('/support')); ?>" class="bg-breaking-red text-white px-6 py-2 font-label-lg text-label-lg uppercase tracking-wider hover:bg-secondary-container transition-colors flex-shrink-0">Donate Now</a>
  </section>
  <?php endif; ?>

  <section class="mb-10 md:mb-16">
    <div class="flex items-center justify-between mb-8">
      <h2 class="text-headline-lg font-headline-lg text-primary border-b-2 border-breaking-red pb-2 inline-block"><?php echo esc_html($display_name); ?></h2>
      <a href="<?php echo esc_url(home_url('/category/' . $slug)); ?>"
         class="text-breaking-red font-label-sm text-label-sm uppercase tracking-wider hover:underline">More →</a>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 md:gap-8">
      <?php while ($section_query->have_posts()) : $section_query->the_post();
        $byline = get_post_meta(get_the_ID(), 'tpb_byline', true) ?: 'By ' . get_the_author();
        $clean_name = trim(str_replace('By ', '', $byline));
      ?>
      <article class="group flex flex-col gap-0 clickable-article">
        <?php if (has_post_thumbnail()) : ?>
          <div class="overflow-hidden aspect-[16/9] mb-4">
            <a href="<?php the_permalink(); ?>">
              <?php the_post_thumbnail('medium', ['class' => 'w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500']); ?>
            </a>
          </div>
        <?php endif; ?>
        <div class="flex flex-col gap-2">
          <span class="text-breaking-red font-label-sm text-label-sm uppercase tracking-widest font-bold"><?php echo esc_html($display_name); ?></span>
          <h3 class="text-[18px] md:text-headline-md font-headline-md text-primary leading-tight group-hover:text-breaking-red transition-colors" style="font-family:'Playfair Display',serif;">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
          </h3>
          <p class="text-meta-text text-sm">
            <a href="<?php echo esc_url(home_url('/author-profile/?writer=' . urlencode($clean_name))); ?>"
               class="hover:text-breaking-red transition-colors font-semibold"><?php echo esc_html($byline); ?></a>
            &bull; <?php echo get_the_date(); ?>
          </p>
        </div>
      </article>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
  </section>
  <?php endforeach; ?>

</main>

<?php get_footer(); ?>

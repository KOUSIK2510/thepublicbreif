<?php get_header(); ?>

<!-- Breaking News Ticker -->
<div class="bg-primary text-white py-2 px-4 md:px-12 flex items-center gap-4 overflow-hidden">
  <span class="shrink-0 bg-breaking-red text-white font-label-sm text-label-sm uppercase tracking-widest px-3 py-1 mr-4">Breaking</span>
  <div class="ticker-wrap flex-1">
    <div class="ticker">
      <?php
      $ticker_args = [ 'post_type' => ['post','tpb_article'], 'posts_per_page' => 8, 'post_status' => 'publish' ];
      $ticker_query = new WP_Query($ticker_args);
      while ($ticker_query->have_posts()) : $ticker_query->the_post(); ?>
        <span class="ticker-item text-sm font-label-sm">
          <a href="<?php the_permalink(); ?>" class="hover:text-breaking-red transition-colors"><?php the_title(); ?></a>
          &nbsp;&bull;&nbsp;
        </span>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
  </div>
</div>

<!-- Main Content -->
<main class="flex-grow w-full max-w-[1280px] mx-auto px-4 md:px-12 py-16">

  <?php
  // ── Hero / Featured Story ─────────────────────────────────────────────────
  $hero_args = [ 'post_type' => ['post','tpb_article'], 'posts_per_page' => 1, 'post_status' => 'publish' ];
  $hero_query = new WP_Query($hero_args);
  if ($hero_query->have_posts()) : $hero_query->the_post();
    $cats = get_the_category();
    $cat_name = $cats ? $cats[0]->name : 'News';
    $byline = get_post_meta(get_the_ID(), 'tpb_byline', true) ?: 'By ' . get_the_author();
    $read_time = get_post_meta(get_the_ID(), 'tpb_read_time', true) ?: '5 min read';
  ?>
  <section class="grid grid-cols-1 lg:grid-cols-12 gap-12 mb-16 pb-16 border-b border-separator">
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
      <h1 class="text-display-lg-mobile md:text-4xl font-display-lg text-primary mb-5 leading-tight">
        <a href="<?php the_permalink(); ?>" class="hover:text-breaking-red transition-colors"><?php the_title(); ?></a>
      </h1>
      <p class="text-body-lg font-body-lg text-on-surface-variant leading-relaxed mb-6"><?php echo esc_html(get_the_excerpt()); ?></p>
      <div class="flex items-center gap-3 text-sm text-meta-text">
        <?php if (get_the_author_meta('ID')) : ?>
          <a href="<?php echo esc_url(home_url('/author-profile/?name=' . urlencode(get_the_author()))); ?>">
            <?php echo get_avatar(get_the_author_meta('ID'), 44, '', '', ['class' => 'rounded-full']); ?>
          </a>
        <?php endif; ?>
        <div>
          <a href="<?php echo esc_url(home_url('/author-profile/?name=' . urlencode(get_the_author()))); ?>"
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
  $sections = ['National', 'World', 'Sports', 'Entertainment', 'Opinion'];
  foreach ($sections as $section) :
    $section_args = [
      'post_type'      => ['post','tpb_article'],
      'posts_per_page' => 3,
      'post_status'    => 'publish',
      'category_name'  => strtolower($section),
    ];
    $section_query = new WP_Query($section_args);
    if (!$section_query->have_posts()) continue;
  ?>
  <section class="mb-16">
    <div class="flex items-center justify-between mb-8">
      <h2 class="text-headline-lg font-headline-lg text-primary border-b-2 border-breaking-red pb-2 inline-block"><?php echo esc_html($section); ?></h2>
      <a href="<?php echo esc_url(home_url('/category/' . strtolower($section))); ?>"
         class="text-breaking-red font-label-sm text-label-sm uppercase tracking-wider hover:underline">More →</a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <?php while ($section_query->have_posts()) : $section_query->the_post();
        $byline = get_post_meta(get_the_ID(), 'tpb_byline', true) ?: 'By ' . get_the_author();
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
          <span class="text-breaking-red font-label-sm text-label-sm uppercase tracking-widest font-bold"><?php echo esc_html($section); ?></span>
          <h3 class="text-headline-md font-headline-md text-primary leading-tight group-hover:text-breaking-red transition-colors">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
          </h3>
          <p class="text-meta-text text-sm">
            <a href="<?php echo esc_url(home_url('/author-profile/?name=' . urlencode(get_the_author()))); ?>"
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

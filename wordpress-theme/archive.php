<?php get_header(); ?>

<?php
$cat       = get_queried_object();
$cat_name  = $cat ? $cat->name : 'Articles';
$cat_slug  = $cat ? $cat->slug : '';
$desc_map  = [
  'national'      => 'In-depth reporting on Indian politics, economy, and society.',
  'world'         => 'Global news coverage — diplomacy, conflict, and international affairs.',
  'sports'        => 'Live scores, match reports, and sports analysis.',
  'entertainment' => 'Film, music, arts, and culture from around the world.',
  'opinion'       => 'Perspectives that challenge, essays that illuminate, debates that matter.',
];
$desc = $desc_map[$cat_slug] ?? bloginfo('description');
?>

<!-- Page Header Banner -->
<div class="bg-primary text-white py-14 px-4 md:px-12 text-center">
  <p class="text-breaking-red font-label-sm text-label-sm uppercase tracking-widest mb-3">Section</p>
  <h1 class="text-display-lg-mobile md:text-display-lg font-display-lg text-white mb-4"><?php echo esc_html($cat_name); ?></h1>
  <p class="text-on-primary-container text-body-lg font-body-lg max-w-2xl mx-auto"><?php echo esc_html($desc); ?></p>
</div>

<main class="flex-grow w-full max-w-[1280px] mx-auto px-4 md:px-12 py-16">

  <?php if (have_posts()) : ?>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
    <?php while (have_posts()) : the_post();
      $byline    = get_post_meta(get_the_ID(), 'tpb_byline', true) ?: 'By ' . get_the_author();
      $read_time = get_post_meta(get_the_ID(), 'tpb_read_time', true) ?: '5 min read';
    ?>
    <article class="group flex flex-col bg-white border border-separator overflow-hidden hover:shadow-lg transition-shadow duration-300 clickable-article">
      <?php if (has_post_thumbnail()) : ?>
        <div class="overflow-hidden aspect-[16/9]">
          <a href="<?php the_permalink(); ?>">
            <?php the_post_thumbnail('medium_large', ['class' => 'w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500']); ?>
          </a>
        </div>
      <?php endif; ?>
      <div class="p-6 flex flex-col gap-3 flex-grow">
        <span class="text-breaking-red font-label-sm text-label-sm uppercase tracking-widest font-bold"><?php echo esc_html($cat_name); ?></span>
        <h2 class="text-headline-md font-headline-md text-primary leading-tight group-hover:text-breaking-red transition-colors">
          <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h2>
        <p class="text-on-surface-variant text-body-md font-body-md leading-relaxed line-clamp-3"><?php echo esc_html(get_the_excerpt()); ?></p>
        <div class="mt-auto pt-4 border-t border-separator flex items-center gap-2 text-sm text-meta-text">
          <a href="<?php echo esc_url(home_url('/author-profile/?name=' . urlencode(get_the_author()))); ?>"
             class="font-semibold hover:text-breaking-red transition-colors">
            <?php echo esc_html($byline); ?>
          </a>
          <span>&bull;</span>
          <span><?php echo get_the_date(); ?></span>
          <span>&bull;</span>
          <span><?php echo esc_html($read_time); ?></span>
        </div>
      </div>
    </article>
    <?php endwhile; ?>
  </div>

  <!-- Pagination -->
  <?php tpb_pagination(); ?>

  <?php else : ?>
    <p class="text-meta-text text-center py-24 text-lg">No articles found in this section yet.</p>
  <?php endif; ?>

</main>

<?php get_footer(); ?>

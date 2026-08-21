<?php
/**
 * Search Results — The Public Brief
 */
get_header();
$query = get_search_query();
$count = $wp_query->found_posts;
?>

<!-- Search Header Banner -->
<div class="bg-primary text-white py-14 px-4 md:px-12 text-center">
  <p class="text-breaking-red font-label-sm text-label-sm uppercase tracking-widest mb-3">Search Results</p>
  <h1 class="text-display-lg-mobile md:text-display-lg font-display-lg text-white mb-4">
    "<?php echo esc_html( $query ); ?>"
  </h1>
  <p class="text-on-primary-container text-body-lg font-body-lg">
    <?php echo esc_html( $count ); ?> <?php echo $count === 1 ? 'result' : 'results'; ?> found
  </p>
</div>

<main class="flex-grow w-full max-w-[1280px] mx-auto px-4 md:px-12 py-16">

  <!-- Search Again -->
  <div class="max-w-2xl mx-auto mb-16">
    <?php tpb_search_form(); ?>
  </div>

  <?php if ( have_posts() ) : ?>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
    <?php while ( have_posts() ) : the_post();
      $cats      = get_the_category();
      $cat_name  = $cats ? $cats[0]->name : 'News';
      $byline    = get_post_meta( get_the_ID(), 'tpb_byline', true ) ?: 'By ' . get_the_author();
      $read_time = get_post_meta( get_the_ID(), 'tpb_read_time', true ) ?: tpb_read_time( get_the_content() );
    ?>
    <article class="group flex flex-col bg-white border border-separator overflow-hidden hover:shadow-lg transition-shadow duration-300">
      <?php if ( has_post_thumbnail() ) : ?>
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
        <p class="text-on-surface-variant text-body-md font-body-md leading-relaxed line-clamp-3">
          <?php echo esc_html( get_the_excerpt() ); ?>
        </p>
        <div class="mt-auto pt-4 border-t border-separator flex items-center gap-2 text-sm text-meta-text">
          <span class="font-semibold"><?php echo esc_html($byline); ?></span>
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
  <div class="text-center py-24">
    <p class="text-meta-text text-xl mb-8">No results found for "<?php echo esc_html($query); ?>".</p>
    <p class="text-on-surface-variant mb-12">Try a different keyword, or browse by section:</p>
    <div class="flex flex-wrap justify-center gap-4">
      <?php foreach (['National','World','Sports','Entertainment','Opinion'] as $s) : ?>
        <a href="<?php echo esc_url( home_url('/category/' . strtolower($s)) ); ?>"
           class="px-6 py-2 border-2 border-primary text-primary font-label-lg text-label-lg uppercase tracking-wider hover:bg-primary hover:text-white transition-colors">
          <?php echo esc_html($s); ?>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

</main>

<?php get_footer(); ?>

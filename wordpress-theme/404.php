<?php
/**
 * 404 Not Found — The Public Brief
 */
get_header();
?>

<main class="flex-grow w-full max-w-[1280px] mx-auto px-4 md:px-12 py-32 flex flex-col items-center justify-center text-center">

  <!-- Giant 404 -->
  <div style="font-family:'Playfair Display',serif;font-size:clamp(6rem,20vw,14rem);font-weight:700;line-height:1;color:#000;letter-spacing:-0.04em;margin-bottom:0;">404</div>

  <!-- Divider -->
  <div class="w-24 h-1 bg-breaking-red mx-auto mb-8"></div>

  <!-- Heading -->
  <h1 class="text-headline-xl font-headline-xl text-primary mb-4">Page Not Found</h1>

  <!-- Subtext -->
  <p class="text-body-lg font-body-lg text-on-surface-variant max-w-lg mx-auto mb-10">
    The story you're looking for may have moved, been updated, or never existed. Try searching our archive or head back to the homepage.
  </p>

  <!-- Actions -->
  <div class="flex flex-col sm:flex-row gap-4 justify-center">
    <a href="<?php echo esc_url( home_url('/') ); ?>"
       class="bg-primary text-white px-8 py-3 font-label-lg text-label-lg uppercase tracking-wider hover:bg-breaking-red transition-colors">
      ← Back to Homepage
    </a>
    <a href="<?php echo esc_url( home_url('/?s=') ); ?>"
       class="border-2 border-primary text-primary px-8 py-3 font-label-lg text-label-lg uppercase tracking-wider hover:bg-primary hover:text-white transition-colors">
      Search Archive
    </a>
  </div>

  <!-- Recent Articles -->
  <div class="mt-20 w-full">
    <h2 class="text-headline-md font-headline-md text-primary mb-8 border-b border-separator pb-4">Recent Stories</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-left">
      <?php
      $recent = new WP_Query([
          'post_type'      => ['post', 'tpb_article'],
          'posts_per_page' => 3,
          'post_status'    => 'publish',
      ]);
      while ( $recent->have_posts() ) : $recent->the_post();
        $cats     = get_the_category();
        $cat_name = $cats ? $cats[0]->name : 'News';
      ?>
      <article class="group flex flex-col gap-2 border border-separator p-6 hover:shadow-md transition-shadow">
        <span class="text-breaking-red font-label-sm text-label-sm uppercase tracking-widest font-bold"><?php echo esc_html($cat_name); ?></span>
        <h3 class="font-headline-md text-headline-md text-primary group-hover:text-breaking-red transition-colors">
          <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>
        <p class="text-meta-text text-sm"><?php echo get_the_date(); ?></p>
      </article>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
  </div>

</main>

<?php get_footer(); ?>

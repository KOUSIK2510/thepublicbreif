<?php get_header(); ?>

<main class="flex-grow w-full max-w-[1280px] mx-auto px-4 md:px-12 py-8 md:py-16">
  <?php while (have_posts()) : the_post();
    $cats      = get_the_category();
    $cat_name  = $cats ? $cats[0]->name : 'News';
    $byline    = get_post_meta(get_the_ID(), 'tpb_byline', true) ?: 'By ' . get_the_author();
    $clean_name = trim(str_replace('By ', '', $byline));
    $read_time = get_post_meta(get_the_ID(), 'tpb_read_time', true) ?: '5 min read';
  ?>

  <article class="max-w-3xl mx-auto">
    <!-- Category Label -->
    <span class="text-breaking-red font-label-sm text-label-sm uppercase tracking-widest font-bold mb-4 block"><?php echo esc_html($cat_name); ?></span>

    <!-- Headline -->
    <h1 class="text-display-lg-mobile md:text-display-lg font-display-lg text-primary mb-6 leading-tight"><?php the_title(); ?></h1>

    <!-- Author + Meta -->
    <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-6 md:mb-8 pb-6 md:pb-8 border-b border-separator">
      <?php echo get_avatar(get_the_author_meta('ID'), 52, '', '', ['class' => 'rounded-full border-2 border-separator']); ?>
      <div>
        <a href="<?php echo esc_url(home_url('/author-profile/?writer=' . urlencode($clean_name))); ?>"
           class="font-label-lg text-label-lg font-bold text-primary hover:text-breaking-red transition-colors block">
          <?php echo esc_html($byline); ?>
        </a>
        <p class="text-meta-text text-sm"><?php echo get_the_date(); ?> &bull; <?php echo esc_html($read_time); ?></p>
      </div>
    </div>

    <!-- Featured Image -->
    <?php if (has_post_thumbnail()) : ?>
      <figure class="mb-8">
        <?php the_post_thumbnail('full', ['class' => 'w-full aspect-[16/9] object-cover']); ?>
      </figure>
    <?php endif; ?>

    <!-- Body -->
    <div class="prose max-w-none text-[17px] md:text-[19px] leading-relaxed article-body text-black">
      <?php the_content(); ?>
    </div>

    <!-- Tags -->
    <?php $tags = get_the_tags(); if ($tags) : ?>
      <div class="mt-12 pt-8 border-t border-separator flex flex-wrap gap-2">
        <?php foreach ($tags as $tag) : ?>
          <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>"
             class="px-4 py-2 border border-separator text-label-sm font-label-sm text-meta-text hover:bg-breaking-red hover:text-white hover:border-breaking-red transition-colors uppercase tracking-wider">
            <?php echo esc_html($tag->name); ?>
          </a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <!-- Author Bio Card -->
    <div class="mt-8 md:mt-12 p-5 md:p-6 border border-separator bg-surface-gray flex flex-col sm:flex-row gap-4 items-center sm:items-start text-center sm:text-left">
      <?php echo get_avatar(get_the_author_meta('ID'), 72, '', '', ['class' => 'rounded-full shrink-0']); ?>
      <div>
        <a href="<?php echo esc_url(home_url('/author-profile/?writer=' . urlencode($clean_name))); ?>"
           class="font-headline-md text-headline-md text-primary font-bold hover:text-breaking-red transition-colors block mb-1">
          <?php the_author(); ?>
        </a>
        <p class="text-meta-text text-sm"><?php the_author_meta('description'); ?></p>
        <a href="<?php echo esc_url(home_url('/author-profile/?writer=' . urlencode($clean_name))); ?>"
           class="mt-3 inline-block text-breaking-red font-label-sm text-label-sm uppercase tracking-wider hover:underline">
          View all articles →
        </a>
      </div>
    </div>
  </article>

  <?php endwhile; ?>
</main>

<style>
.article-body { font-family: Georgia, 'Times New Roman', serif !important; color: #000000 !important; }
.article-body p { font-family: Georgia, 'Times New Roman', serif !important; color: #000000 !important; font-size: 1.15rem; line-height: 1.9; margin-bottom: 1.5rem; }
.article-body li { font-family: Georgia, 'Times New Roman', serif !important; color: #000000 !important; line-height: 1.8; margin-bottom: 0.5rem; }
.article-body h2 { font-family: 'Playfair Display',serif; font-size: 1.75rem; font-weight: 700; margin: 2rem 0 1rem; color: #000; }
.article-body h3 { font-family: 'Playfair Display',serif; font-size: 1.4rem; font-weight: 600; margin: 1.5rem 0 0.75rem; color: #000; }
.article-body blockquote { border-left: 4px solid #B00020; padding-left: 1.5rem; font-style: italic; color: #000; font-size: 1.2rem; margin: 2rem 0; }
.article-body a { color: #B00020; text-decoration: underline; }
</style>

<?php get_footer(); ?>

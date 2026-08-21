<?php
/**
 * Single Article Template — Custom Post Type: tpb_article
 * The Public Brief
 */
get_header();
?>

<main class="flex-grow w-full max-w-[1280px] mx-auto px-4 md:px-12 py-16">

  <?php while ( have_posts() ) : the_post();
    $cats      = get_the_category();
    $cat_name  = $cats ? $cats[0]->name : 'News';
    $cat_link  = $cats ? get_category_link( $cats[0]->term_id ) : home_url('/');
    $byline    = get_post_meta( get_the_ID(), 'tpb_byline', true ) ?: 'By ' . get_the_author();
    $read_time = get_post_meta( get_the_ID(), 'tpb_read_time', true ) ?: tpb_read_time( get_the_content() );
  ?>

  <!-- Breadcrumb (visible + SEO) -->
  <nav class="flex items-center gap-2 text-meta-text text-sm mb-8 font-label-sm" aria-label="Breadcrumb">
    <a href="<?php echo esc_url( home_url('/') ); ?>" class="hover:text-breaking-red transition-colors">Home</a>
    <span class="material-symbols-outlined text-[16px]">chevron_right</span>
    <a href="<?php echo esc_url( $cat_link ); ?>" class="hover:text-breaking-red transition-colors"><?php echo esc_html($cat_name); ?></a>
    <span class="material-symbols-outlined text-[16px]">chevron_right</span>
    <span class="text-on-surface-variant line-clamp-1"><?php the_title(); ?></span>
  </nav>

  <article class="max-w-3xl mx-auto" itemscope itemtype="https://schema.org/NewsArticle">

    <!-- Category Label -->
    <span class="text-breaking-red font-label-sm text-label-sm uppercase tracking-widest font-bold mb-4 block"
          itemprop="articleSection"><?php echo esc_html($cat_name); ?></span>

    <!-- Headline -->
    <h1 class="text-display-lg-mobile md:text-display-lg font-display-lg text-primary mb-6 leading-tight"
        itemprop="headline"><?php the_title(); ?></h1>

    <!-- Excerpt / Deck -->
    <?php if ( get_the_excerpt() ) : ?>
    <p class="text-xl text-on-surface-variant leading-relaxed mb-8 font-body-lg border-l-4 border-breaking-red pl-5 italic">
      <?php echo esc_html( get_the_excerpt() ); ?>
    </p>
    <?php endif; ?>

    <!-- Author + Meta -->
    <div class="flex items-center gap-4 mb-8 pb-8 border-b border-separator"
         itemprop="author" itemscope itemtype="https://schema.org/Person">
      <?php echo get_avatar( get_the_author_meta('ID'), 52, '', '', ['class' => 'rounded-full border-2 border-separator']); ?>
      <div>
        <a href="<?php echo esc_url( home_url('/author-profile/?name=' . urlencode( get_the_author() )) ); ?>"
           class="font-label-lg text-label-lg font-bold text-primary hover:text-breaking-red transition-colors block"
           itemprop="name">
          <?php echo esc_html($byline); ?>
        </a>
        <p class="text-meta-text text-sm">
          <time datetime="<?php echo esc_attr( get_the_date('c') ); ?>"
                itemprop="datePublished"><?php echo get_the_date(); ?></time>
          &bull;
          <span><?php echo esc_html($read_time); ?></span>
        </p>
      </div>
      <!-- Share Buttons -->
      <div class="ml-auto flex items-center gap-3">
        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>"
           target="_blank" rel="noopener"
           class="w-9 h-9 border border-separator flex items-center justify-center text-on-surface-variant hover:text-breaking-red hover:border-breaking-red transition-colors"
           aria-label="Share on X">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.73-8.835L1.254 2.25H8.08l4.253 5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
        </a>
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>"
           target="_blank" rel="noopener"
           class="w-9 h-9 border border-separator flex items-center justify-center text-on-surface-variant hover:text-breaking-red hover:border-breaking-red transition-colors"
           aria-label="Share on Facebook">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
        </a>
        <button onclick="navigator.clipboard.writeText('<?php echo esc_js(get_permalink()); ?>').then(()=>alert('Link copied!'))"
                class="w-9 h-9 border border-separator flex items-center justify-center text-on-surface-variant hover:text-breaking-red hover:border-breaking-red transition-colors"
                aria-label="Copy link">
          <span class="material-symbols-outlined text-[18px]">link</span>
        </button>
      </div>
    </div>

    <!-- Featured Image -->
    <?php if ( has_post_thumbnail() ) : ?>
      <figure class="mb-10" itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
        <?php the_post_thumbnail('tpb-hero', ['class' => 'w-full aspect-[16/9] object-cover', 'itemprop' => 'url']); ?>
        <?php $caption = get_the_post_thumbnail_caption(); if ($caption) : ?>
          <figcaption class="text-meta-text text-sm mt-3 italic"><?php echo esc_html($caption); ?></figcaption>
        <?php endif; ?>
      </figure>
    <?php endif; ?>

    <!-- Body -->
    <div class="prose max-w-none text-body-lg font-body-lg text-on-surface-variant leading-relaxed article-body"
         itemprop="articleBody">
      <?php the_content(); ?>
    </div>

    <!-- Tags -->
    <?php $tags = get_the_tags(); if ($tags) : ?>
      <div class="mt-12 pt-8 border-t border-separator flex flex-wrap gap-2">
        <span class="font-label-sm text-label-sm text-meta-text uppercase tracking-wider mr-2 self-center">Tags:</span>
        <?php foreach ($tags as $tag) : ?>
          <a href="<?php echo esc_url( get_tag_link($tag->term_id) ); ?>"
             class="px-4 py-2 border border-separator text-label-sm font-label-sm text-meta-text hover:bg-breaking-red hover:text-white hover:border-breaking-red transition-colors uppercase tracking-wider">
            <?php echo esc_html($tag->name); ?>
          </a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <!-- Author Bio Card -->
    <div class="mt-12 p-8 border border-separator bg-surface-gray flex gap-6 items-start">
      <?php echo get_avatar( get_the_author_meta('ID'), 80, '', '', ['class' => 'rounded-full shrink-0 border-2 border-separator']); ?>
      <div>
        <a href="<?php echo esc_url( home_url('/author-profile/?name=' . urlencode( get_the_author() )) ); ?>"
           class="font-headline-md text-headline-md text-primary font-bold hover:text-breaking-red transition-colors block mb-1">
          <?php the_author(); ?>
        </a>
        <p class="text-meta-text text-sm mb-4"><?php the_author_meta('description'); ?></p>
        <a href="<?php echo esc_url( home_url('/author-profile/?name=' . urlencode( get_the_author() )) ); ?>"
           class="text-breaking-red font-label-sm text-label-sm uppercase tracking-wider hover:underline">
          View all articles →
        </a>
      </div>
    </div>

  </article>

  <?php endwhile; ?>

  <!-- Related Articles -->
  <?php
  global $post;
  $cats = get_the_category( $post->ID );
  if ( $cats ) :
    $related_args = [
        'post_type'      => ['post', 'tpb_article'],
        'posts_per_page' => 3,
        'post_status'    => 'publish',
        'post__not_in'   => [$post->ID],
        'category__in'   => [$cats[0]->term_id],
        'orderby'        => 'rand',
    ];
    $related = new WP_Query($related_args);
    if ( $related->have_posts() ) :
  ?>
  <section class="max-w-5xl mx-auto mt-20 pt-16 border-t border-separator">
    <h2 class="text-headline-lg font-headline-lg text-primary mb-8 border-b-2 border-breaking-red pb-3 inline-block">
      More from <?php echo esc_html($cats[0]->name); ?>
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <?php while ($related->have_posts()) : $related->the_post();
        $r_cats = get_the_category();
        $r_cat  = $r_cats ? $r_cats[0]->name : 'News';
      ?>
      <article class="group flex flex-col gap-3">
        <?php if (has_post_thumbnail()) : ?>
          <div class="overflow-hidden aspect-[16/9] mb-2">
            <a href="<?php the_permalink(); ?>">
              <?php the_post_thumbnail('tpb-card', ['class' => 'w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500']); ?>
            </a>
          </div>
        <?php endif; ?>
        <span class="text-breaking-red font-label-sm text-label-sm uppercase tracking-widest font-bold"><?php echo esc_html($r_cat); ?></span>
        <h3 class="font-headline-md text-headline-md text-primary group-hover:text-breaking-red transition-colors leading-tight">
          <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>
        <p class="text-meta-text text-sm"><?php echo get_the_date(); ?></p>
      </article>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
  </section>
  <?php endif; endif; ?>

</main>

<style>
.article-body p            { margin-bottom: 1.5rem; }
.article-body h2           { font-family:'Playfair Display',serif; font-size:1.75rem; font-weight:700; margin:2.5rem 0 1rem; color:#000; }
.article-body h3           { font-family:'Playfair Display',serif; font-size:1.4rem; font-weight:600; margin:2rem 0 0.75rem; color:#000; }
.article-body blockquote   { border-left:4px solid #B00020; padding:1rem 1.5rem; font-style:italic; color:#000; font-size:1.2rem; margin:2.5rem 0; background:#f9f9f9; }
.article-body a            { color:#B00020; text-decoration:underline; }
.article-body a:hover      { color:#000; }
.article-body img          { width:100%; height:auto; margin:2rem 0; }
.article-body figcaption   { font-size:0.875rem; color:#6B7280; font-style:italic; margin-top:0.5rem; }
.article-body ul           { list-style:disc; padding-left:2rem; margin-bottom:1.5rem; }
.article-body ol           { list-style:decimal; padding-left:2rem; margin-bottom:1.5rem; }
.article-body li           { margin-bottom:0.5rem; }
.article-body strong       { color:#000; font-weight:700; }
</style>

<?php get_footer(); ?>

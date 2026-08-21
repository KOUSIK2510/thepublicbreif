<?php
/**
 * The Public Brief — functions.php
 * Theme setup, asset enqueueing, menus, custom post types, SEO & fields
 */

// ─── Theme Setup ──────────────────────────────────────────────────────────────
function tpb_theme_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'gallery', 'caption', 'navigation-widgets' ] );
    add_theme_support( 'custom-logo' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'responsive-embeds' );
    add_image_size( 'tpb-hero', 1200, 675, true );
    add_image_size( 'tpb-card', 600, 338, true );

    register_nav_menus([
        'primary' => __( 'Primary Navigation', 'thepublicbrief' ),
        'footer'  => __( 'Footer Navigation', 'thepublicbrief' ),
    ]);
}
add_action( 'after_setup_theme', 'tpb_theme_setup' );

// ─── SEO: Remove default <title> injection so we fully control it ──────────────
remove_action( 'wp_head', '_wp_render_title_tag', 1 );

// ─── SEO: Meta Tags (Title, Description, Open Graph, Twitter Cards) ───────────
function tpb_seo_meta_tags() {
    global $post;

    // ── Build title, description & image ──────────────────────────────────────
    $site_name    = get_bloginfo( 'name' );          // "The Public Brief"
    $site_url     = home_url( '/' );
    $site_desc    = get_bloginfo( 'description' );   // tagline
    $default_img  = get_template_directory_uri() . '/assets/img/og-default.jpg';

    if ( is_singular() && isset( $post ) ) {
        $title       = get_the_title( $post ) . ' — ' . $site_name;
        $desc        = wp_strip_all_tags( get_the_excerpt( $post ) );
        if ( empty( $desc ) ) {
            $desc = wp_trim_words( strip_tags( $post->post_content ), 30, '…' );
        }
        $canonical   = get_permalink( $post );
        $og_type     = 'article';
        $image_url   = has_post_thumbnail( $post )
                        ? get_the_post_thumbnail_url( $post, 'tpb-hero' )
                        : $default_img;
        $pub_date    = get_the_date( 'c', $post );
        $mod_date    = get_the_modified_date( 'c', $post );
        $author_name = get_the_author_meta( 'display_name', $post->post_author );
        $byline      = get_post_meta( $post->ID, 'tpb_byline', true ) ?: 'By ' . $author_name;
        $cats        = get_the_category( $post->ID );
        $section     = $cats ? $cats[0]->name : 'News';
    } elseif ( is_category() ) {
        $cat         = get_queried_object();
        $title       = $cat->name . ' News — ' . $site_name;
        $desc        = $cat->description ?: 'Latest ' . $cat->name . ' news from ' . $site_name . '.';
        $canonical   = get_category_link( $cat->term_id );
        $og_type     = 'website';
        $image_url   = $default_img;
    } elseif ( is_search() ) {
        $title       = 'Search: ' . get_search_query() . ' — ' . $site_name;
        $desc        = 'Search results for "' . esc_html( get_search_query() ) . '" on ' . $site_name . '.';
        $canonical   = home_url( '/?s=' . urlencode( get_search_query() ) );
        $og_type     = 'website';
        $image_url   = $default_img;
    } else {
        $title       = is_front_page() ? $site_name . ' — Independent Journalism' : wp_title( '—', false, 'right' ) . $site_name;
        $desc        = $site_desc;
        $canonical   = is_front_page() ? $site_url : get_pagenum_link();
        $og_type     = 'website';
        $image_url   = $default_img;
    }

    // Sanitise
    $title     = esc_attr( $title );
    $desc      = esc_attr( wp_trim_words( $desc, 35, '…' ) );
    $canonical = esc_url( $canonical );
    $image_url = esc_url( $image_url );

    echo "\n<!-- The Public Brief SEO Meta Tags -->\n";

    // ── Standard meta ─────────────────────────────────────────────────────────
    echo '<title>' . $title . "</title>\n";
    echo '<meta name="description" content="' . $desc . "\" />\n";
    echo '<link rel="canonical" href="' . $canonical . "\" />\n";
    echo '<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1" />' . "\n";

    // ── Open Graph ────────────────────────────────────────────────────────────
    echo '<meta property="og:type"        content="' . esc_attr( $og_type ) . "\" />\n";
    echo '<meta property="og:title"       content="' . $title . "\" />\n";
    echo '<meta property="og:description" content="' . $desc . "\" />\n";
    echo '<meta property="og:url"         content="' . $canonical . "\" />\n";
    echo '<meta property="og:site_name"   content="' . esc_attr( $site_name ) . "\" />\n";
    echo '<meta property="og:image"       content="' . $image_url . "\" />\n";
    echo '<meta property="og:image:width"  content="1200" />' . "\n";
    echo '<meta property="og:image:height" content="675" />' . "\n";
    echo '<meta property="og:locale"      content="en_US" />' . "\n";
    if ( isset( $pub_date ) ) {
        echo '<meta property="article:published_time" content="' . esc_attr( $pub_date ) . "\" />\n";
        echo '<meta property="article:modified_time"  content="' . esc_attr( $mod_date ) . "\" />\n";
        echo '<meta property="article:section"        content="' . esc_attr( $section ) . "\" />\n";
    }

    // ── Twitter Cards ─────────────────────────────────────────────────────────
    echo '<meta name="twitter:card"        content="summary_large_image" />' . "\n";
    echo '<meta name="twitter:site"        content="@thepublicbrief" />' . "\n";
    echo '<meta name="twitter:creator"     content="@thepublicbrief" />' . "\n";
    echo '<meta name="twitter:title"       content="' . $title . "\" />\n";
    echo '<meta name="twitter:description" content="' . $desc . "\" />\n";
    echo '<meta name="twitter:image"       content="' . $image_url . "\" />\n";

    echo "<!-- /The Public Brief SEO Meta Tags -->\n\n";
}
add_action( 'wp_head', 'tpb_seo_meta_tags', 1 );

// ─── SEO: JSON-LD Structured Data (NewsArticle schema) ────────────────────────
function tpb_json_ld_schema() {
    global $post;
    if ( ! is_singular() || ! isset( $post ) ) return;

    $site_name  = get_bloginfo( 'name' );
    $site_url   = home_url( '/' );
    $logo_url   = get_template_directory_uri() . '/assets/img/logo.png';

    $cats       = get_the_category( $post->ID );
    $section    = $cats ? $cats[0]->name : 'News';
    $byline     = get_post_meta( $post->ID, 'tpb_byline', true ) ?: 'By ' . get_the_author_meta( 'display_name', $post->post_author );
    $author     = preg_replace('/^By\s+/i', '', $byline ); // Strip "By "
    $image_url  = has_post_thumbnail( $post ) ? get_the_post_thumbnail_url( $post, 'tpb-hero' ) : '';
    $excerpt    = wp_trim_words( strip_tags( $post->post_content ), 35, '…' );

    $schema = [
        '@context'         => 'https://schema.org',
        '@type'            => 'NewsArticle',
        'headline'         => get_the_title( $post ),
        'description'      => $excerpt,
        'datePublished'    => get_the_date( 'c', $post ),
        'dateModified'     => get_the_modified_date( 'c', $post ),
        'articleSection'   => $section,
        'url'              => get_permalink( $post ),
        'mainEntityOfPage' => [ '@type' => 'WebPage', '@id' => get_permalink( $post ) ],
        'author'           => [ '@type' => 'Person', 'name' => $author ],
        'publisher'        => [
            '@type' => 'Organization',
            'name'  => $site_name,
            'url'   => $site_url,
            'logo'  => [
                '@type'  => 'ImageObject',
                'url'    => $logo_url,
                'width'  => 300,
                'height' => 60,
            ],
        ],
    ];

    if ( $image_url ) {
        $schema['image'] = [
            '@type'  => 'ImageObject',
            'url'    => $image_url,
            'width'  => 1200,
            'height' => 675,
        ];
    }

    echo '<script type="application/ld+json">' . "\n";
    echo wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
    echo "\n" . '</script>' . "\n";
}
add_action( 'wp_head', 'tpb_json_ld_schema', 2 );

// ─── SEO: BreadcrumbList JSON-LD ──────────────────────────────────────────────
function tpb_breadcrumb_schema() {
    global $post;
    if ( ! is_singular() || ! isset( $post ) ) return;

    $items = [
        [ '@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => home_url( '/' ) ],
    ];
    $cats  = get_the_category( $post->ID );
    if ( $cats ) {
        $items[] = [
            '@type'    => 'ListItem',
            'position' => 2,
            'name'     => $cats[0]->name,
            'item'     => get_category_link( $cats[0]->term_id ),
        ];
    }
    $items[] = [
        '@type'    => 'ListItem',
        'position' => count( $items ) + 1,
        'name'     => get_the_title( $post ),
        'item'     => get_permalink( $post ),
    ];

    $schema = [
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => $items,
    ];

    echo '<script type="application/ld+json">' . "\n";
    echo wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
    echo "\n" . '</script>' . "\n";
}
add_action( 'wp_head', 'tpb_breadcrumb_schema', 3 );

// ─── SEO: Organization JSON-LD (sitewide) ─────────────────────────────────────
function tpb_organization_schema() {
    if ( ! is_front_page() ) return;
    $schema = [
        '@context' => 'https://schema.org',
        '@type'    => 'NewsMediaOrganization',
        'name'     => get_bloginfo( 'name' ),
        'url'      => home_url( '/' ),
        'logo'     => [
            '@type'  => 'ImageObject',
            'url'    => get_template_directory_uri() . '/assets/img/logo.png',
            'width'  => 300,
            'height' => 60,
        ],
        'sameAs'   => [
            'https://twitter.com/thepublicbrief',
            'https://www.instagram.com/thepublicbrief',
        ],
    ];
    echo '<script type="application/ld+json">' . "\n";
    echo wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
    echo "\n" . '</script>' . "\n";
}
add_action( 'wp_head', 'tpb_organization_schema', 4 );

// ─── SEO: Disable WP emoji bloat (performance) ────────────────────────────────
remove_action( 'wp_head',             'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles',     'print_emoji_styles' );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles',  'print_emoji_styles' );

// ─── SEO: Remove unnecessary WP head bloat ────────────────────────────────────
remove_action( 'wp_head', 'wp_generator' );              // Hide WP version
remove_action( 'wp_head', 'wlwmanifest_link' );          // Windows Live Writer
remove_action( 'wp_head', 'rsd_link' );                  // Really Simple Discovery
add_filter( 'the_generator', '__return_empty_string' );  // Extra version hiding

// ─── Enqueue Assets ───────────────────────────────────────────────────────────
function tpb_enqueue_assets() {
    // Google Fonts
    wp_enqueue_style( 'tpb-google-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap',
        [], null );

    // Tailwind CDN (same as original site)
    wp_enqueue_script( 'tailwind-cdn',
        'https://cdn.tailwindcss.com?plugins=forms,container-queries',
        [], null, false );

    // Theme stylesheet
    wp_enqueue_style( 'tpb-main-styles',
        get_template_directory_uri() . '/assets/css/styles.css',
        [ 'tpb-google-fonts' ], '1.0.0' );

    // Tailwind config (inline)
    wp_add_inline_script( 'tailwind-cdn', '
        tailwind.config={darkMode:"class",theme:{extend:{colors:{
            "separator":"#E5E7EB","background":"#f9f9f9","breaking-red":"#B00020",
            "meta-text":"#6B7280","on-primary":"#ffffff","surface-container-lowest":"#ffffff",
            "surface-container":"#eeeeee","surface-container-high":"#e8e8e8",
            "surface-gray":"#F9FAFB","on-surface":"#1a1c1c","primary":"#000000",
            "secondary":"#b90c25","secondary-container":"#dd2e3a","surface":"#f9f9f9",
            "on-primary-container":"#858383","surface-tint":"#5f5e5e","accent-amber":"#D97706",
            "on-surface-variant":"#444748","surface-container-low":"#f3f3f4","outline-variant":"#c4c7c7"
        },fontFamily:{
            "label-lg":["Inter"],"body-lg":["Inter"],"label-sm":["Inter"],
            "display-lg":["Playfair Display"],"headline-lg":["Playfair Display"],
            "headline-xl":["Playfair Display"],"headline-md":["Playfair Display"],
            "display-lg-mobile":["Playfair Display"],"body-md":["Inter"]
        },fontSize:{
            "label-lg":["14px",{"lineHeight":"20px","letterSpacing":"0.05em","fontWeight":"600"}],
            "body-lg":["18px",{"lineHeight":"30px","fontWeight":"400"}],
            "label-sm":["12px",{"lineHeight":"16px","fontWeight":"500"}],
            "display-lg":["48px",{"lineHeight":"56px","letterSpacing":"-0.02em","fontWeight":"700"}],
            "headline-lg":["28px",{"lineHeight":"36px","fontWeight":"700"}],
            "headline-xl":["36px",{"lineHeight":"44px","fontWeight":"700"}],
            "headline-md":["24px",{"lineHeight":"32px","fontWeight":"600"}],
            "display-lg-mobile":["32px",{"lineHeight":"40px","fontWeight":"700"}],
            "body-md":["16px",{"lineHeight":"26px","fontWeight":"400"}]
        }}}};
    ', 'after' );

    // Theme JS
    wp_enqueue_script( 'tpb-theme',
        get_template_directory_uri() . '/assets/js/theme.js',
        [], '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'tpb_enqueue_assets' );

// ─── Custom Post Type: Article ─────────────────────────────────────────────────
function tpb_register_cpt() {
    $labels = [
        'name'               => 'Articles',
        'singular_name'      => 'Article',
        'add_new'            => 'Add Article',
        'add_new_item'       => 'Add New Article',
        'edit_item'          => 'Edit Article',
        'view_item'          => 'View Article',
        'search_items'       => 'Search Articles',
        'not_found'          => 'No articles found',
        'menu_name'          => 'Articles',
    ];
    register_post_type( 'tpb_article', [
        'labels'              => $labels,
        'public'              => true,
        'has_archive'         => true,
        'show_in_rest'        => true,
        'supports'            => [ 'title', 'editor', 'thumbnail', 'author', 'excerpt', 'custom-fields' ],
        'taxonomies'          => [ 'category' ],
        'menu_icon'           => 'dashicons-media-text',
        'rewrite'             => [ 'slug' => 'articles' ],
    ]);
}
add_action( 'init', 'tpb_register_cpt' );

// ─── Register Categories (Sections) ───────────────────────────────────────────
function tpb_register_categories() {
    $sections = [ 'National', 'World', 'Sports', 'Entertainment', 'Opinion' ];
    foreach ( $sections as $section ) {
        if ( ! term_exists( $section, 'category' ) ) {
            wp_insert_term( $section, 'category' );
        }
    }
}
add_action( 'init', 'tpb_register_categories' );

// ─── Custom Meta Box: Byline & Read Time ──────────────────────────────────────
function tpb_add_meta_boxes() {
    add_meta_box( 'tpb_article_meta', 'Article Details',
        'tpb_meta_box_html', [ 'post', 'tpb_article' ], 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'tpb_add_meta_boxes' );

function tpb_meta_box_html( $post ) {
    $byline    = get_post_meta( $post->ID, 'tpb_byline', true );
    $read_time = get_post_meta( $post->ID, 'tpb_read_time', true );
    wp_nonce_field( 'tpb_meta_nonce', 'tpb_meta_nonce' );
    echo '<p><label><strong>Byline (e.g. "By John Smith")</strong><br>';
    echo '<input type="text" name="tpb_byline" value="' . esc_attr( $byline ) . '" style="width:100%;margin-top:4px;" /></label></p>';
    echo '<p><label><strong>Read Time (e.g. "8 min read")</strong><br>';
    echo '<input type="text" name="tpb_read_time" value="' . esc_attr( $read_time ) . '" style="width:100%;margin-top:4px;" /></label></p>';
}

function tpb_save_meta( $post_id ) {
    if ( ! isset( $_POST['tpb_meta_nonce'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['tpb_meta_nonce'], 'tpb_meta_nonce' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( isset( $_POST['tpb_byline'] ) )
        update_post_meta( $post_id, 'tpb_byline', sanitize_text_field( $_POST['tpb_byline'] ) );
    if ( isset( $_POST['tpb_read_time'] ) )
        update_post_meta( $post_id, 'tpb_read_time', sanitize_text_field( $_POST['tpb_read_time'] ) );
}
add_action( 'save_post', 'tpb_save_meta' );

// ─── Helper: Get Category Label Color ─────────────────────────────────────────
function tpb_category_color( $cat_name ) {
    return '#B00020'; // Breaking red for all categories
}

// ─── Pagination helper ────────────────────────────────────────────────────────
function tpb_pagination() {
    echo '<div class="flex justify-center gap-4 mt-12 pb-8">';
    echo paginate_links([
        'type'      => 'list',
        'prev_text' => '&larr; Previous',
        'next_text' => 'Next &rarr;',
    ]);
    echo '</div>';
}

// ─── Helper: Estimated read time ──────────────────────────────────────────────
function tpb_read_time( $content ) {
    $word_count = str_word_count( strip_tags( $content ) );
    $minutes    = max( 1, (int) ceil( $word_count / 200 ) );
    return $minutes . ' min read';
}

// ─── SEO: XML Sitemap (WP 5.5+ built-in — just enable CPT) ───────────────────
add_filter( 'wp_sitemaps_post_types', function( $post_types ) {
    // Ensure our custom post type appears in /wp-sitemap.xml
    if ( post_type_exists( 'tpb_article' ) ) {
        $post_types['tpb_article'] = get_post_type_object( 'tpb_article' );
    }
    return $post_types;
} );

// ─── SEO: Robots.txt additions via WP filter ──────────────────────────────────
add_filter( 'robots_txt', function( $output, $public ) {
    if ( $public ) {
        $output .= "\nSitemap: " . home_url( '/wp-sitemap.xml' ) . "\n";
    }
    return $output;
}, 10, 2 );

// ─── Helper: Render Search Form ───────────────────────────────────────────────
function tpb_search_form() {
    ?>
    <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex items-center border-b-2 border-breaking-red">
        <input
            type="search"
            name="s"
            value="<?php echo esc_attr( get_search_query() ); ?>"
            placeholder="Search articles, topics, authors…"
            class="flex-1 py-3 px-2 text-lg text-primary bg-transparent outline-none font-body-lg"
            id="tpb-search-input"
            autocomplete="off"
        />
        <button type="submit" class="p-2 text-breaking-red hover:text-primary transition-colors" aria-label="Search">
            <span class="material-symbols-outlined text-[28px]">search</span>
        </button>
    </form>
    <?php
}

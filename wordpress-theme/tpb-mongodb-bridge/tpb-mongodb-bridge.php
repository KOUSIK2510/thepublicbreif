<?php
/**
 * Plugin Name: TPB MongoDB Bridge
 * Plugin URI:  https://github.com/KOUSIK2510/thepublicbreif
 * Description: Bridges The Public Brief Node.js/MongoDB API with WordPress. Syncs articles from MongoDB into WordPress posts and provides shortcodes to display live API content.
 * Version:     1.0.0
 * Author:      The Public Brief
 * License:     GPL2
 * Text Domain: tpb-bridge
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// ─── Admin Settings Page ──────────────────────────────────────────────────────
add_action( 'admin_menu', 'tpb_bridge_admin_menu' );
function tpb_bridge_admin_menu() {
    add_options_page(
        'TPB MongoDB Bridge',
        'TPB MongoDB Bridge',
        'manage_options',
        'tpb-mongodb-bridge',
        'tpb_bridge_settings_page'
    );
}

add_action( 'admin_init', 'tpb_bridge_register_settings' );
function tpb_bridge_register_settings() {
    register_setting( 'tpb_bridge_settings', 'tpb_api_url' );
    register_setting( 'tpb_bridge_settings', 'tpb_cache_ttl' );
    register_setting( 'tpb_bridge_settings', 'tpb_api_jwt_token' );
}

function tpb_bridge_settings_page() {
    $sync_message = '';
    if ( isset( $_POST['tpb_sync_now'] ) && check_admin_referer( 'tpb_sync_action' ) ) {
        $result = tpb_sync_articles_to_wp();
        $sync_message = $result['message'];
    }
    if ( isset( $_POST['tpb_clear_cache'] ) && check_admin_referer( 'tpb_sync_action' ) ) {
        // clear all tpb transients
        global $wpdb;
        $wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_tpb_%' OR option_name LIKE '_transient_timeout_tpb_%'" );
        $sync_message = 'Cache cleared successfully.';
    }

    $api_url   = get_option( 'tpb_api_url', 'http://localhost:3001' );
    $cache_ttl = get_option( 'tpb_cache_ttl', 300 );
    $jwt_token = get_option( 'tpb_api_jwt_token', '' );

    $test_response = wp_remote_get( $api_url . '/api/articles', array( 'timeout' => 5 ) );
    $connected     = ( ! is_wp_error( $test_response ) ) && ( wp_remote_retrieve_response_code( $test_response ) === 200 );
    $article_count = 0;
    if ( $connected ) {
        $body          = json_decode( wp_remote_retrieve_body( $test_response ), true );
        $article_count = is_array( $body ) ? count( $body ) : 0;
    }
    echo '<div class="wrap"><h1>TPB MongoDB Bridge</h1>';
    if ( $sync_message ) echo '<div class="notice notice-success is-dismissible"><p>' . esc_html( $sync_message ) . '</p></div>';
    $bg    = $connected ? '#d4edda' : '#f8d7da';
    $bdr   = $connected ? '#28a745' : '#dc3545';
    $lbl   = $connected ? 'Connected — ' . $article_count . ' articles in MongoDB' : 'Not Connected — check your API URL and ensure Node.js server is running';
    echo '<div style="background:' . $bg . ';padding:15px;border-radius:8px;margin:20px 0;border-left:5px solid ' . $bdr . '">' . esc_html( $lbl ) . '</div>';
    echo '<form method="post" action="options.php" style="background:#fff;padding:25px;border-radius:8px;border:1px solid #ddd;margin-bottom:20px;">';
    settings_fields( 'tpb_bridge_settings' );
    echo '<h2>Settings</h2><table class="form-table">';
    echo '<tr><th><label for="tpb_api_url">Node.js API URL</label></th><td><input type="url" id="tpb_api_url" name="tpb_api_url" value="' . esc_attr( $api_url ) . '" class="regular-text" /><p class="description">URL where your backend runs, e.g. <code>https://api.yourdomain.com</code></p></td></tr>';
    echo '<tr><th><label for="tpb_cache_ttl">Cache Duration (sec)</label></th><td><input type="number" id="tpb_cache_ttl" name="tpb_cache_ttl" value="' . esc_attr( $cache_ttl ) . '" class="small-text" min="30" max="86400" /><p class="description">Default 300 (5 min)</p></td></tr>';
    echo '<tr><th><label for="tpb_api_jwt_token">Admin JWT Token</label></th><td><input type="text" id="tpb_api_jwt_token" name="tpb_api_jwt_token" value="' . esc_attr( $jwt_token ) . '" class="large-text" placeholder="Optional — for syncing pending articles" /></td></tr>';
    echo '</table>';
    submit_button( 'Save Settings' );
    echo '</form>';
    echo '<form method="post" style="background:#fff;padding:25px;border-radius:8px;border:1px solid #ddd;margin-bottom:20px;">';
    wp_nonce_field( 'tpb_sync_action' );
    echo '<h2>Article Sync</h2><p>Import articles from MongoDB into WordPress posts. Uses <code>_tpb_mongo_id</code> meta to avoid duplicates.</p><p>';
    $disabled = $connected ? '' : 'disabled';
    echo '<button type="submit" name="tpb_sync_now" class="button button-primary" ' . $disabled . '>Sync MongoDB Articles into WordPress</button> ';
    echo '<button type="submit" name="tpb_clear_cache" class="button">Clear API Cache</button>';
    echo '</p></form>';
    echo '<div style="background:#fff;padding:25px;border-radius:8px;border:1px solid #ddd;">';
    echo '<h2>Available Shortcodes</h2><table class="widefat striped"><thead><tr><th>Shortcode</th><th>Description</th></tr></thead><tbody>';
    echo '<tr><td><code>[tpb_articles limit="6" category="World"]</code></td><td>Article grid from MongoDB</td></tr>';
    echo '<tr><td><code>[tpb_latest_article]</code></td><td>Hero block with most recent article</td></tr>';
    echo '<tr><td><code>[tpb_article_count]</code></td><td>Total article count</td></tr>';
    echo '<tr><td><code>[tpb_articles_by_category category="Opinion" limit="4"]</code></td><td>Articles by category</td></tr>';
    echo '</tbody></table></div>';
    echo '<p style="margin-top:20px"><strong>REST Endpoints:</strong><br><code>/wp-json/tpb/v1/articles</code> — Public<br><code>/wp-json/tpb/v1/sync</code> — POST (admin only)</p>';
    echo '</div>';
}

// ─── Core: Fetch Articles from MongoDB API ────────────────────────────────────
function tpb_fetch_articles( $category = '', $limit = 0, $author = '' ) {
    $cache_key = 'tpb_articles_' . md5( $category . $limit . $author );
    $cached    = get_transient( $cache_key );
    if ( $cached !== false ) return $cached;

    $api_url = get_option( 'tpb_api_url', 'http://localhost:3001' );
    $params  = array();
    if ( $category ) $params['category'] = $category;
    if ( $author )   $params['author']   = $author;
    $url = $api_url . '/api/articles' . ( $params ? '?' . http_build_query( $params ) : '' );

    $response = wp_remote_get( $url, array( 'timeout' => 10 ) );
    if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) !== 200 ) return array();

    $articles = json_decode( wp_remote_retrieve_body( $response ), true );
    if ( ! is_array( $articles ) ) return array();
    if ( $limit > 0 ) $articles = array_slice( $articles, 0, $limit );

    $ttl = (int) get_option( 'tpb_cache_ttl', 300 );
    set_transient( $cache_key, $articles, $ttl );
    return $articles;
}

// ─── Sync: MongoDB Articles → WordPress Posts ─────────────────────────────────
function tpb_sync_articles_to_wp() {
    $articles = tpb_fetch_articles();
    if ( empty( $articles ) ) return array( 'message' => 'No articles fetched. Check API URL.' );

    $created = 0; $updated = 0;
    foreach ( $articles as $article ) {
        $mongo_id = sanitize_text_field( isset( $article['_id'] ) ? $article['_id'] : '' );
        if ( ! $mongo_id ) continue;
        $title    = sanitize_text_field( isset( $article['title'] )    ? $article['title']    : 'Untitled' );
        $body     = wp_kses_post(        isset( $article['body'] )     ? $article['body']     : '' );
        $category = sanitize_text_field( isset( $article['category'] ) ? $article['category'] : '' );
        $byline   = sanitize_text_field( isset( $article['byline'] )   ? $article['byline']   : '' );
        $image    = esc_url_raw(         isset( $article['imageUrl'] ) ? $article['imageUrl'] : '' );
        $status   = ( isset( $article['status'] ) && $article['status'] === 'published' ) ? 'publish' : 'draft';

        $existing = get_posts( array(
            'post_type'   => 'post',
            'meta_key'    => '_tpb_mongo_id',
            'meta_value'  => $mongo_id,
            'numberposts' => 1,
            'fields'      => 'ids',
        ) );

        $post_data = array(
            'post_title'   => $title,
            'post_content' => $body,
            'post_status'  => $status,
            'post_type'    => 'post',
            'post_excerpt' => wp_trim_words( strip_tags( $body ), 30 ),
        );

        if ( $existing ) {
            $post_data['ID'] = $existing[0];
            wp_update_post( $post_data );
            $post_id = $existing[0];
            $updated++;
        } else {
            $post_id = wp_insert_post( $post_data );
            if ( is_wp_error( $post_id ) ) continue;
            $created++;
        }

        update_post_meta( $post_id, '_tpb_mongo_id',  $mongo_id );
        update_post_meta( $post_id, '_tpb_byline',    $byline );
        update_post_meta( $post_id, '_tpb_image_url', $image );

        if ( $category ) {
            $cat = get_category_by_slug( sanitize_title( $category ) );
            $cat_id = $cat ? $cat->term_id : wp_create_category( $category );
            wp_set_post_categories( $post_id, array( $cat_id ) );
        }
    }
    return array( 'message' => "Sync complete! Created: {$created}, Updated: {$updated} posts.", 'created' => $created, 'updated' => $updated );
}

// ─── Shortcode: [tpb_articles] ───────────────────────────────────────────────
add_shortcode( 'tpb_articles', 'tpb_articles_shortcode' );
add_shortcode( 'tpb_articles_by_category', 'tpb_articles_shortcode' );
function tpb_articles_shortcode( $atts ) {
    $atts     = shortcode_atts( array( 'limit' => 6, 'category' => '', 'author' => '' ), $atts );
    $articles = tpb_fetch_articles( $atts['category'], (int) $atts['limit'], $atts['author'] );
    if ( empty( $articles ) ) return '<p class="tpb-no-articles">No articles found.</p>';
    ob_start();
    echo '<div class="tpb-articles-grid">';
    foreach ( $articles as $a ) {
        $title    = esc_html( isset( $a['title'] ) ? $a['title'] : '' );
        $cat      = esc_html( isset( $a['category'] ) ? $a['category'] : '' );
        $byline   = esc_html( isset( $a['byline'] ) ? $a['byline'] : '' );
        $image    = esc_url(  isset( $a['imageUrl'] ) ? $a['imageUrl'] : '' );
        $excerpt  = esc_html( wp_trim_words( strip_tags( isset( $a['body'] ) ? $a['body'] : '' ), 20, '...' ) );
        $mongo_id = isset( $a['_id'] ) ? $a['_id'] : '';
        $wp_posts  = get_posts( array( 'meta_key' => '_tpb_mongo_id', 'meta_value' => $mongo_id, 'numberposts' => 1, 'fields' => 'ids' ) );
        $link      = $wp_posts ? get_permalink( $wp_posts[0] ) : '#';
        echo '<article class="tpb-article-card">';
        if ( $image ) echo '<a href="' . esc_url( $link ) . '" class="tpb-card-image-link"><div class="tpb-card-image" style="background-image:url(\'' . $image . '\')"><span class="tpb-category-badge">' . $cat . '</span></div></a>';
        echo '<div class="tpb-card-content"><span class="tpb-card-category">' . $cat . '</span><h3 class="tpb-card-title"><a href="' . esc_url( $link ) . '">' . $title . '</a></h3><p class="tpb-card-excerpt">' . $excerpt . '</p><span class="tpb-card-byline">' . $byline . '</span></div>';
        echo '</article>';
    }
    echo '</div>';
    return ob_get_clean();
}

// ─── Shortcode: [tpb_latest_article] ─────────────────────────────────────────
add_shortcode( 'tpb_latest_article', 'tpb_latest_article_shortcode' );
function tpb_latest_article_shortcode() {
    $articles = tpb_fetch_articles( '', 1 );
    if ( empty( $articles ) ) return '';
    $a        = $articles[0];
    $title    = esc_html( isset( $a['title'] ) ? $a['title'] : '' );
    $cat      = esc_html( isset( $a['category'] ) ? $a['category'] : '' );
    $byline   = esc_html( isset( $a['byline'] ) ? $a['byline'] : '' );
    $image    = esc_url(  isset( $a['imageUrl'] ) ? $a['imageUrl'] : '' );
    $excerpt  = esc_html( wp_trim_words( strip_tags( isset( $a['body'] ) ? $a['body'] : '' ), 40, '...' ) );
    $mongo_id = isset( $a['_id'] ) ? $a['_id'] : '';
    $wp_posts  = get_posts( array( 'meta_key' => '_tpb_mongo_id', 'meta_value' => $mongo_id, 'numberposts' => 1, 'fields' => 'ids' ) );
    $link      = $wp_posts ? get_permalink( $wp_posts[0] ) : '#';
    ob_start();
    echo '<div class="tpb-hero-article" style="background-image:url(\'' . $image . '\')">';
    echo '<div class="tpb-hero-overlay">';
    echo '<span class="tpb-hero-category">' . $cat . '</span>';
    echo '<h2 class="tpb-hero-title"><a href="' . esc_url( $link ) . '">' . $title . '</a></h2>';
    echo '<p class="tpb-hero-excerpt">' . $excerpt . '</p>';
    echo '<span class="tpb-hero-byline">' . $byline . '</span><br><br>';
    echo '<a href="' . esc_url( $link ) . '" class="tpb-hero-btn">Read Story</a>';
    echo '</div></div>';
    return ob_get_clean();
}

// ─── Shortcode: [tpb_article_count] ──────────────────────────────────────────
add_shortcode( 'tpb_article_count', function() {
    return '<span class="tpb-article-count">' . count( tpb_fetch_articles() ) . '</span>';
} );

// ─── REST API ─────────────────────────────────────────────────────────────────
add_action( 'rest_api_init', function () {
    register_rest_route( 'tpb/v1', '/articles', array(
        'methods'             => 'GET',
        'callback'            => function ( $req ) {
            return rest_ensure_response( tpb_fetch_articles( $req->get_param('category') ?? '', (int)( $req->get_param('limit') ?? 0 ) ) );
        },
        'permission_callback' => '__return_true',
    ) );
    register_rest_route( 'tpb/v1', '/sync', array(
        'methods'             => 'POST',
        'callback'            => function () { return rest_ensure_response( tpb_sync_articles_to_wp() ); },
        'permission_callback' => function () { return current_user_can( 'manage_options' ); },
    ) );
} );

// ─── Styles ───────────────────────────────────────────────────────────────────
add_action( 'wp_head', function () {
    echo '<style>
.tpb-articles-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:28px;padding:20px 0}
.tpb-article-card{background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,.08);transition:transform .2s,box-shadow .2s}
.tpb-article-card:hover{transform:translateY(-4px);box-shadow:0 8px 28px rgba(0,0,0,.14)}
.tpb-card-image{height:200px;background-size:cover;background-position:center;position:relative}
.tpb-category-badge{position:absolute;top:12px;left:12px;background:#1a1a2e;color:#e8c84a;font-size:11px;font-weight:700;letter-spacing:1px;text-transform:uppercase;padding:4px 10px;border-radius:20px}
.tpb-card-content{padding:20px}
.tpb-card-category{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#e8c84a}
.tpb-card-title{margin:8px 0;font-size:17px;line-height:1.4}
.tpb-card-title a{color:#1a1a2e;text-decoration:none}
.tpb-card-title a:hover{color:#e8c84a}
.tpb-card-excerpt{color:#666;font-size:14px;line-height:1.6}
.tpb-card-byline{font-size:12px;color:#999;font-style:italic}
.tpb-hero-article{position:relative;min-height:480px;background-size:cover;background-position:center;border-radius:16px;overflow:hidden;margin-bottom:32px}
.tpb-hero-overlay{position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,.85) 0%,transparent 60%);display:flex;flex-direction:column;justify-content:flex-end;padding:40px}
.tpb-hero-category{font-size:12px;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:#e8c84a;margin-bottom:10px}
.tpb-hero-title{font-size:32px;color:#fff;margin:0 0 12px;line-height:1.2}
.tpb-hero-title a{color:inherit;text-decoration:none}
.tpb-hero-excerpt{color:rgba(255,255,255,.8);font-size:15px;margin-bottom:16px}
.tpb-hero-byline{color:rgba(255,255,255,.6);font-size:13px;font-style:italic}
.tpb-hero-btn{display:inline-block;background:#e8c84a;color:#1a1a2e;padding:10px 24px;border-radius:6px;font-weight:700;text-decoration:none;font-size:14px;transition:background .2s}
.tpb-hero-btn:hover{background:#f0d96a}
</style>';
} );

// ─── Auto Daily Sync ──────────────────────────────────────────────────────────
add_action( 'tpb_daily_sync', 'tpb_sync_articles_to_wp' );
register_activation_hook( __FILE__, function () {
    if ( ! wp_next_scheduled( 'tpb_daily_sync' ) ) wp_schedule_event( time(), 'daily', 'tpb_daily_sync' );
} );
register_deactivation_hook( __FILE__, function () {
    wp_clear_scheduled_hook( 'tpb_daily_sync' );
} );

<?php
/**
 * Subscribe Page Template — The Public Brief
 *
 * Template Name: Subscribe
 */
get_header();

// Handle subscription form
$success = false;
$error   = '';
if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tpb_subscribe_nonce']) ) {
    if ( wp_verify_nonce( sanitize_text_field($_POST['tpb_subscribe_nonce']), 'tpb_subscribe' ) ) {
        $email = sanitize_email( $_POST['subscribe_email'] ?? '' );
        $name  = sanitize_text_field( $_POST['subscribe_name'] ?? '' );
        if ( ! is_email( $email ) ) {
            $error = 'Please enter a valid email address.';
        } else {
            // Store subscription as a WP option list (or use a newsletter plugin)
            $subscribers = get_option( 'tpb_newsletter_subscribers', [] );
            if ( ! in_array( $email, array_column($subscribers, 'email') ) ) {
                $subscribers[] = [
                    'email' => $email,
                    'name'  => $name,
                    'date'  => current_time('mysql'),
                ];
                update_option( 'tpb_newsletter_subscribers', $subscribers );
            }
            // Send welcome email
            $subject = 'Welcome to The Public Brief Newsletter!';
            $body    = "Hi {$name},\n\nThank you for subscribing to The Public Brief.\n\nYou'll receive our best journalism — curated daily, no noise.\n\nThe Public Brief Team\nhttps://thepublicbrief.in";
            wp_mail( $email, $subject, $body );
            $success = true;
        }
    }
}
?>

<!-- Hero Banner -->
<div class="bg-primary text-white py-24 px-4 md:px-12 text-center relative overflow-hidden">
  <div class="absolute inset-0 opacity-5" style="background-image:repeating-linear-gradient(45deg,transparent,transparent 40px,rgba(255,255,255,.2) 40px,rgba(255,255,255,.2) 41px);"></div>
  <div class="relative z-10 max-w-2xl mx-auto">
    <p class="text-breaking-red font-label-sm text-label-sm uppercase tracking-widest mb-4">Stay Informed</p>
    <h1 class="text-display-lg-mobile md:text-display-lg font-display-lg text-white mb-6">The Public Brief<br/>Newsletter</h1>
    <p class="text-on-primary-container text-body-lg font-body-lg">
      Independent journalism delivered to your inbox. No fluff, no noise — just the stories that matter.
    </p>
  </div>
</div>

<main class="flex-grow w-full max-w-[1280px] mx-auto px-4 md:px-12 py-20">

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">

    <!-- Form Column -->
    <div>
      <?php if ($success) : ?>
        <div class="border-l-4 border-breaking-red bg-red-50 px-8 py-10 mb-8">
          <span class="material-symbols-outlined text-breaking-red text-[3rem] block mb-4">check_circle</span>
          <h2 class="text-headline-lg font-headline-lg text-primary mb-3">You're Subscribed!</h2>
          <p class="text-body-lg font-body-lg text-on-surface-variant">
            Check your inbox — a welcome email is on its way. You'll hear from us soon with our best reporting.
          </p>
        </div>
        <a href="<?php echo esc_url( home_url('/') ); ?>"
           class="inline-block bg-primary text-white px-10 py-4 font-label-lg text-label-lg uppercase tracking-wider hover:bg-breaking-red transition-colors">
          Read Latest Stories →
        </a>
      <?php else : ?>
        <span class="text-breaking-red font-label-sm text-label-sm uppercase tracking-widest font-bold block mb-4">Free Newsletter</span>
        <h2 class="text-headline-xl font-headline-xl text-primary mb-4">Subscribe Today</h2>
        <p class="text-body-lg font-body-lg text-on-surface-variant mb-8">
          Join over 120,000 readers who start their day with The Public Brief. Unsubscribe at any time.
        </p>

        <?php if ($error) : ?>
          <div class="bg-red-50 border border-red-300 text-red-700 px-6 py-3 mb-6 font-label-lg">
            <?php echo esc_html($error); ?>
          </div>
        <?php endif; ?>

        <form method="post" class="flex flex-col gap-6" id="tpb-subscribe-form">
          <?php wp_nonce_field('tpb_subscribe', 'tpb_subscribe_nonce'); ?>

          <div class="flex flex-col gap-2">
            <label class="font-label-lg text-label-lg text-primary uppercase tracking-wider" for="subscribe_name">First Name</label>
            <input type="text" id="subscribe_name" name="subscribe_name"
                   class="border border-separator px-4 py-4 text-primary bg-white outline-none focus:border-primary transition-colors text-lg"
                   placeholder="Your first name" />
          </div>

          <div class="flex flex-col gap-2">
            <label class="font-label-lg text-label-lg text-primary uppercase tracking-wider" for="subscribe_email">Email Address *</label>
            <input type="email" id="subscribe_email" name="subscribe_email" required
                   class="border border-separator px-4 py-4 text-primary bg-white outline-none focus:border-primary transition-colors text-lg"
                   placeholder="you@example.com" />
          </div>

          <p class="text-meta-text text-sm">
            By subscribing you agree to our <a href="<?php echo esc_url(home_url('/privacy-policy')); ?>" class="underline hover:text-breaking-red">Privacy Policy</a>.
            We will never sell your data.
          </p>

          <button type="submit"
                  class="bg-breaking-red text-white px-10 py-4 font-label-lg text-label-lg uppercase tracking-wider hover:bg-secondary-container transition-colors">
            Subscribe — It's Free →
          </button>
        </form>
      <?php endif; ?>
    </div>

    <!-- Benefits Column -->
    <div class="flex flex-col gap-8">
      <div class="border border-separator p-8">
        <h3 class="font-headline-md text-headline-md text-primary mb-6 border-b border-separator pb-4">What You'll Get</h3>
        <ul class="flex flex-col gap-5">
          <?php
          $perks = [
              ['icon' => 'newspaper',        'title' => 'Daily Briefing',         'desc' => 'Top stories curated every morning — concise, essential, no filler.'],
              ['icon' => 'analytics',        'title' => 'In-Depth Analysis',      'desc' => 'Long-form reporting on the stories that need context and depth.'],
              ['icon' => 'breaking_news',    'title' => 'Breaking News Alerts',   'desc' => 'Instant notifications when major stories break.'],
              ['icon' => 'person_outline',   'title' => 'Exclusive Columns',      'desc' => "Opinion and insight from our editors and top contributors."],
          ];
          foreach ($perks as $p) : ?>
            <li class="flex items-start gap-4">
              <span class="material-symbols-outlined text-breaking-red shrink-0" style="font-size:28px;"><?php echo esc_html($p['icon']); ?></span>
              <div>
                <div class="font-label-lg text-label-lg text-primary mb-1"><?php echo esc_html($p['title']); ?></div>
                <div class="text-body-md font-body-md text-on-surface-variant"><?php echo esc_html($p['desc']); ?></div>
              </div>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>

      <div class="bg-surface-gray border border-separator p-8">
        <div style="font-family:'Playfair Display',serif;font-size:3rem;color:#B00020;line-height:1;">"</div>
        <p class="text-body-lg font-body-lg text-primary italic mb-4">The Public Brief is the only newsletter I read every single morning without fail.</p>
        <div class="font-label-lg text-label-lg text-on-surface-variant uppercase tracking-wider">— Subscriber since 2021</div>
      </div>
    </div>

  </div>

</main>

<?php get_footer(); ?>

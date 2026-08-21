<?php
/**
 * Support / Contact Page Template — The Public Brief
 *
 * Template Name: Support
 */
get_header();
?>

<!-- Hero Banner -->
<div class="bg-primary text-white py-20 px-4 md:px-12 text-center">
  <p class="text-breaking-red font-label-sm text-label-sm uppercase tracking-widest mb-4">Get In Touch</p>
  <h1 class="text-display-lg-mobile md:text-display-lg font-display-lg text-white mb-6">Support & Contact</h1>
  <p class="text-on-primary-container text-body-lg font-body-lg max-w-2xl mx-auto">
    Questions, tips, corrections, or partnership enquiries — we want to hear from you.
  </p>
</div>

<main class="flex-grow w-full max-w-[1280px] mx-auto px-4 md:px-12 py-20">

  <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">

    <!-- Contact Form -->
    <div class="lg:col-span-7">
      <span class="text-breaking-red font-label-sm text-label-sm uppercase tracking-widest font-bold block mb-4">Send Us a Message</span>
      <h2 class="text-headline-xl font-headline-xl text-primary mb-8">Contact the Newsroom</h2>

      <?php if ( isset($_GET['sent']) && $_GET['sent'] === '1' ) : ?>
        <div class="bg-green-50 border border-green-300 text-green-800 px-6 py-4 mb-8 font-label-lg">
          ✓ Thank you! Your message has been received. We'll get back to you within 2 business days.
        </div>
      <?php endif; ?>

      <?php
      // Process form submission using wp_mail()
      if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tpb_contact_nonce']) ) {
          if ( wp_verify_nonce( sanitize_text_field($_POST['tpb_contact_nonce']), 'tpb_contact_form' ) ) {
              $name    = sanitize_text_field( $_POST['contact_name'] ?? '' );
              $email   = sanitize_email( $_POST['contact_email'] ?? '' );
              $subject = sanitize_text_field( $_POST['contact_subject'] ?? 'Website Contact Form' );
              $message = sanitize_textarea_field( $_POST['contact_message'] ?? '' );
              $to      = get_option( 'admin_email' );
              $headers = [ 'Content-Type: text/plain; charset=UTF-8', 'Reply-To: ' . $email ];
              $body    = "From: {$name} <{$email}>\n\n{$message}";
              wp_mail( $to, '[The Public Brief] ' . $subject, $body, $headers );
              wp_redirect( add_query_arg( 'sent', '1', get_permalink() ) );
              exit;
          }
      }
      ?>

      <form method="post" class="flex flex-col gap-6" id="tpb-contact-form">
        <?php wp_nonce_field( 'tpb_contact_form', 'tpb_contact_nonce' ); ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="flex flex-col gap-2">
            <label class="font-label-lg text-label-lg text-primary uppercase tracking-wider" for="contact_name">Full Name *</label>
            <input type="text" id="contact_name" name="contact_name" required
                   class="border border-separator px-4 py-3 text-primary bg-white outline-none focus:border-primary transition-colors font-body-md"
                   placeholder="Your full name" />
          </div>
          <div class="flex flex-col gap-2">
            <label class="font-label-lg text-label-lg text-primary uppercase tracking-wider" for="contact_email">Email Address *</label>
            <input type="email" id="contact_email" name="contact_email" required
                   class="border border-separator px-4 py-3 text-primary bg-white outline-none focus:border-primary transition-colors font-body-md"
                   placeholder="you@example.com" />
          </div>
        </div>

        <div class="flex flex-col gap-2">
          <label class="font-label-lg text-label-lg text-primary uppercase tracking-wider" for="contact_subject">Subject *</label>
          <select id="contact_subject" name="contact_subject" required
                  class="border border-separator px-4 py-3 text-primary bg-white outline-none focus:border-primary transition-colors font-body-md">
            <option value="">Select a topic…</option>
            <option value="News Tip">News Tip or Story Pitch</option>
            <option value="Correction">Request a Correction</option>
            <option value="Subscription">Subscription Help</option>
            <option value="Advertising">Advertising &amp; Partnerships</option>
            <option value="Press">Press Enquiry</option>
            <option value="Other">Other</option>
          </select>
        </div>

        <div class="flex flex-col gap-2">
          <label class="font-label-lg text-label-lg text-primary uppercase tracking-wider" for="contact_message">Message *</label>
          <textarea id="contact_message" name="contact_message" rows="7" required
                    class="border border-separator px-4 py-3 text-primary bg-white outline-none focus:border-primary transition-colors font-body-md resize-none"
                    placeholder="Write your message here…"></textarea>
        </div>

        <button type="submit"
                class="bg-primary text-white px-10 py-4 font-label-lg text-label-lg uppercase tracking-wider hover:bg-breaking-red transition-colors self-start">
          Send Message →
        </button>
      </form>
    </div>

    <!-- Sidebar Info -->
    <div class="lg:col-span-5 flex flex-col gap-8">

      <div class="border border-separator p-8">
        <h3 class="font-headline-md text-headline-md text-primary mb-6 border-b border-separator pb-4">Contact Information</h3>
        <div class="flex flex-col gap-5">
          <?php
          $contacts = [
              ['icon' => 'mail',      'label' => 'General Enquiries',  'val' => 'contact@thepublicbrief.in'],
              ['icon' => 'campaign',  'label' => 'News Tips',          'val' => 'tips@thepublicbrief.in'],
              ['icon' => 'edit',      'label' => 'Editorial',          'val' => 'editorial@thepublicbrief.in'],
              ['icon' => 'sell',      'label' => 'Advertising',        'val' => 'ads@thepublicbrief.in'],
          ];
          foreach ($contacts as $c) : ?>
            <div class="flex items-start gap-4">
              <span class="material-symbols-outlined text-breaking-red mt-1" style="font-size:22px;"><?php echo esc_html($c['icon']); ?></span>
              <div>
                <div class="font-label-lg text-label-lg text-on-surface-variant uppercase tracking-wider mb-1"><?php echo esc_html($c['label']); ?></div>
                <a href="mailto:<?php echo esc_attr($c['val']); ?>" class="text-primary hover:text-breaking-red transition-colors font-body-md">
                  <?php echo esc_html($c['val']); ?>
                </a>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="border border-separator p-8">
        <h3 class="font-headline-md text-headline-md text-primary mb-4">Response Times</h3>
        <ul class="flex flex-col gap-3 text-body-md font-body-md text-on-surface-variant">
          <li class="flex items-center gap-3">
            <span class="w-2 h-2 bg-breaking-red rounded-full shrink-0"></span>
            <span>General enquiries: <strong class="text-primary">1–2 business days</strong></span>
          </li>
          <li class="flex items-center gap-3">
            <span class="w-2 h-2 bg-breaking-red rounded-full shrink-0"></span>
            <span>Corrections: <strong class="text-primary">Within 24 hours</strong></span>
          </li>
          <li class="flex items-center gap-3">
            <span class="w-2 h-2 bg-breaking-red rounded-full shrink-0"></span>
            <span>Breaking tips: <strong class="text-primary">Immediately reviewed</strong></span>
          </li>
        </ul>
      </div>

      <div class="bg-primary p-8 text-white">
        <h3 class="font-headline-md text-headline-md text-white mb-3">Got a Story Tip?</h3>
        <p class="text-on-primary-container text-body-md font-body-md mb-4">
          Our journalists protect sources. All tips are handled with strict confidentiality. Use our secure tip line for sensitive information.
        </p>
        <a href="mailto:tips@thepublicbrief.in"
           class="inline-block bg-breaking-red text-white px-6 py-3 font-label-lg text-label-lg uppercase tracking-wider hover:bg-secondary-container transition-colors">
          Submit a Tip
        </a>
      </div>

    </div>
  </div>

</main>

<?php get_footer(); ?>

<?php
/**
 * About Page Template — The Public Brief
 *
 * Template Name: About
 */
get_header();
?>

<!-- Hero Banner -->
<div class="bg-primary text-white py-20 px-4 md:px-12 text-center relative overflow-hidden">
  <div class="absolute inset-0 opacity-5" style="background-image:repeating-linear-gradient(0deg,transparent,transparent 39px,rgba(255,255,255,.3) 39px,rgba(255,255,255,.3) 40px),repeating-linear-gradient(90deg,transparent,transparent 39px,rgba(255,255,255,.3) 39px,rgba(255,255,255,.3) 40px);"></div>
  <div class="relative z-10 max-w-3xl mx-auto">
    <p class="text-breaking-red font-label-sm text-label-sm uppercase tracking-widest mb-4">Who We Are</p>
    <h1 class="text-display-lg-mobile md:text-display-lg font-display-lg text-white mb-6">About The Public Brief</h1>
    <p class="text-on-primary-container text-body-lg font-body-lg leading-relaxed">
      Independent journalism that goes beyond the headlines. We report on the stories that matter — with rigour, clarity, and no agenda.
    </p>
  </div>
</div>

<main class="flex-grow w-full max-w-[1280px] mx-auto px-4 md:px-12 py-20">

  <!-- Mission Section -->
  <section class="grid grid-cols-1 lg:grid-cols-2 gap-16 mb-24 pb-16 border-b border-separator items-center">
    <div>
      <span class="text-breaking-red font-label-sm text-label-sm uppercase tracking-widest font-bold block mb-4">Our Mission</span>
      <h2 class="text-headline-xl font-headline-xl text-primary mb-6">Truth-First, Always</h2>
      <p class="text-body-lg font-body-lg text-on-surface-variant leading-relaxed mb-6">
        The Public Brief was founded on the belief that the public deserves journalism that is transparent about its methods, rigorous in its fact-checking, and uncompromising in its independence. We are not beholden to corporate interests, political affiliations, or advertiser pressure.
      </p>
      <p class="text-body-lg font-body-lg text-on-surface-variant leading-relaxed">
        Every story we publish goes through multiple layers of editorial review. Our journalists source primary documents, on-record interviews, and verified data — and we are always open about what we know and what we don't.
      </p>
    </div>
    <div class="grid grid-cols-2 gap-6">
      <?php
      $stats = [
          ['val' => '2019',   'label' => 'Founded'],
          ['val' => '12M+',   'label' => 'Monthly Readers'],
          ['val' => '48',     'label' => 'Staff Journalists'],
          ['val' => '120+',   'label' => 'Awards Won'],
      ];
      foreach ($stats as $s) : ?>
        <div class="bg-surface-gray border border-separator p-8 text-center">
          <div style="font-family:'Playfair Display',serif;font-size:2.5rem;font-weight:700;color:#B00020;margin-bottom:0.5rem;"><?php echo esc_html($s['val']); ?></div>
          <div class="font-label-lg text-label-lg text-on-surface-variant uppercase tracking-wider"><?php echo esc_html($s['label']); ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- Values Section -->
  <section class="mb-24 pb-16 border-b border-separator">
    <span class="text-breaking-red font-label-sm text-label-sm uppercase tracking-widest font-bold block mb-4 text-center">What Guides Us</span>
    <h2 class="text-headline-xl font-headline-xl text-primary mb-12 text-center">Our Editorial Values</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <?php
      $values = [
          ['icon' => 'verified', 'title' => 'Accuracy First',     'desc' => 'We verify before we publish. Every claim is sourced. Corrections are made prominently and without delay.'],
          ['icon' => 'lock_open','title' => 'Editorial Independence','desc' => 'No advertiser, investor, or political entity dictates our coverage. Our newsroom decisions are ours alone.'],
          ['icon' => 'groups',   'title' => 'Public Accountability','desc' => 'We hold power to account. From government to corporations, no institution is above scrutiny.'],
          ['icon' => 'diversity_3','title' => 'Diverse Voices',   'desc' => 'Our team reflects the society we report on. We actively commission perspectives that are underrepresented.'],
          ['icon' => 'open_in_new','title' => 'Transparency',     'desc' => 'We explain our methods, disclose our funding, and welcome feedback. Journalism works best in the open.'],
          ['icon' => 'balance',  'title' => 'Fairness',           'desc' => 'We give all parties a right of reply. We pursue truth without bias or predetermination.'],
      ];
      foreach ($values as $v) : ?>
        <div class="flex flex-col gap-4 p-8 border border-separator hover:shadow-md transition-shadow">
          <span class="material-symbols-outlined text-breaking-red" style="font-size:2.5rem;"><?php echo esc_html($v['icon']); ?></span>
          <h3 class="font-headline-md text-headline-md text-primary"><?php echo esc_html($v['title']); ?></h3>
          <p class="text-body-md font-body-md text-on-surface-variant"><?php echo esc_html($v['desc']); ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- Team Section -->
  <section class="mb-24 pb-16 border-b border-separator">
    <span class="text-breaking-red font-label-sm text-label-sm uppercase tracking-widest font-bold block mb-4 text-center">The Newsroom</span>
    <h2 class="text-headline-xl font-headline-xl text-primary mb-12 text-center">Leadership Team</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
      <?php
      $team = [
          ['name' => 'Priya Nair',      'role' => 'Editor-in-Chief',        'initials' => 'PN'],
          ['name' => 'Arjun Mehta',     'role' => 'Deputy Editor',          'initials' => 'AM'],
          ['name' => 'Sonia Kapoor',    'role' => 'Head of Investigations',  'initials' => 'SK'],
          ['name' => 'Rahul Deshpande','role' => 'Digital Director',        'initials' => 'RD'],
      ];
      foreach ($team as $member) : ?>
        <div class="flex flex-col items-center text-center gap-4">
          <div class="w-24 h-24 rounded-full bg-primary flex items-center justify-center"
               style="font-family:'Playfair Display',serif;font-size:2rem;font-weight:700;color:#fff;">
            <?php echo esc_html($member['initials']); ?>
          </div>
          <div>
            <div class="font-headline-md text-headline-md text-primary"><?php echo esc_html($member['name']); ?></div>
            <div class="text-breaking-red font-label-sm text-label-sm uppercase tracking-wider mt-1"><?php echo esc_html($member['role']); ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- WordPress Page Content (if any) -->
  <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    <?php if ( get_the_content() ) : ?>
    <section class="prose max-w-3xl mx-auto text-body-lg font-body-lg text-on-surface-variant leading-relaxed mb-16">
      <?php the_content(); ?>
    </section>
    <?php endif; ?>
  <?php endwhile; endif; ?>

  <!-- CTA: Contact -->
  <section class="bg-primary text-white p-16 text-center">
    <h2 class="text-headline-xl font-headline-xl text-white mb-4">Work With Us</h2>
    <p class="text-on-primary-container text-body-lg font-body-lg mb-8 max-w-lg mx-auto">
      We're always looking for talented journalists, photographers, and editors. Pitch us a story or enquire about joining the team.
    </p>
    <a href="<?php echo esc_url( home_url('/support') ); ?>"
       class="bg-breaking-red text-white px-10 py-4 font-label-lg text-label-lg uppercase tracking-wider hover:bg-secondary-container transition-colors inline-block">
      Get In Touch
    </a>
  </section>

</main>

<?php get_footer(); ?>

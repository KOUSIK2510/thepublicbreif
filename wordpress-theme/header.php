<!DOCTYPE html>
<html <?php language_attributes(); ?> class="light">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<?php wp_head(); ?>
</head>
<body <?php body_class( 'bg-background text-on-surface font-body-md min-h-screen flex flex-col' ); ?>>

<!-- Header -->
<header class="bg-surface flex flex-col w-full sticky top-0 z-50 border-b border-separator">
  <div class="flex items-center justify-between py-5 px-4 md:px-12 max-w-[1280px] mx-auto w-full">
    <!-- Date -->
    <div class="w-1/3 hidden md:flex items-center text-meta-text font-label-sm text-label-sm uppercase tracking-wider">
      <script>document.write(new Date().toLocaleDateString('en-US',{weekday:'long',year:'numeric',month:'long',day:'numeric'}));</script>
    </div>

    <!-- Logo -->
    <div class="w-1/3 flex justify-center">
      <a href="<?php echo esc_url( home_url('/') ); ?>" class="text-headline-xl font-headline-xl text-primary uppercase tracking-tighter">
        <?php bloginfo( 'name' ); ?>
      </a>
    </div>

    <!-- Auth / Subscribe -->
    <div class="w-1/3 flex justify-end items-center gap-4">
      <?php if ( is_user_logged_in() ) : ?>
        <a href="<?php echo wp_logout_url( home_url() ); ?>" class="hidden md:block text-primary hover:text-breaking-red transition-colors font-label-sm text-label-sm uppercase tracking-wider">Logout</a>
      <?php else : ?>
        <a href="<?php echo wp_login_url(); ?>" class="hidden md:block text-primary hover:text-breaking-red transition-colors font-label-sm text-label-sm uppercase tracking-wider">Login</a>
      <?php endif; ?>
      <a href="<?php echo esc_url( home_url('/subscribe') ); ?>" class="bg-breaking-red text-white px-6 py-2 font-label-lg text-label-lg uppercase tracking-wider hover:bg-secondary-container transition-colors">Subscribe</a>
    </div>
  </div>

  <!-- Navigation -->
  <nav class="flex justify-center w-full max-w-[1280px] mx-auto px-4 md:px-12 mb-0 relative">
    <!-- Hamburger -->
    <div class="absolute left-4 md:left-12 top-0 z-50">
      <button id="hamburger-btn" class="p-1 -ml-1 hover:bg-surface-gray transition-colors flex items-center justify-center" aria-label="Menu">
        <span class="material-symbols-outlined text-[28px] text-primary">menu</span>
      </button>
      <div id="hamburger-menu" class="hidden absolute left-0 top-full mt-3 bg-white border border-separator shadow-xl w-56 py-2">
        <a class="block px-6 py-3 text-label-lg font-label-lg uppercase tracking-wider text-primary hover:bg-surface-gray hover:text-breaking-red transition-colors" href="<?php echo esc_url( home_url('/category/opinion') ); ?>">Opinion</a>
        <a class="block px-6 py-3 text-label-lg font-label-lg uppercase tracking-wider text-primary hover:bg-surface-gray hover:text-breaking-red transition-colors" href="<?php echo esc_url( home_url('/about') ); ?>">About</a>
        <a class="block px-6 py-3 text-label-lg font-label-lg uppercase tracking-wider text-primary hover:bg-surface-gray hover:text-breaking-red transition-colors" href="<?php echo esc_url( home_url('/support') ); ?>">Support</a>
      </div>
    </div>

    <!-- Main nav links -->
    <ul class="flex space-x-8 md:space-x-12 overflow-x-auto whitespace-nowrap pb-0">
      <?php
      $sections = [
        'National'      => home_url('/category/national'),
        'World'         => home_url('/category/world'),
        'Sports'        => home_url('/category/sports'),
        'Entertainment' => home_url('/category/entertainment'),
      ];
      foreach ( $sections as $label => $url ) :
        $active = is_category( strtolower($label) ) ? 'text-secondary border-secondary' : 'border-transparent hover:text-secondary hover:border-secondary';
      ?>
      <li>
        <a class="text-on-surface font-label-lg text-label-lg uppercase tracking-wider block pb-4 border-b-2 <?php echo $active; ?> transition-colors duration-200"
           href="<?php echo esc_url($url); ?>">
          <?php echo esc_html($label); ?>
        </a>
      </li>
      <?php endforeach; ?>
    </ul>
  </nav>
</header>

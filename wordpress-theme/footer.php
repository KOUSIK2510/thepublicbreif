<!-- Footer -->
<footer class="bg-primary text-on-primary mt-auto w-full py-16 px-4 md:px-12">
  <div class="max-w-[1280px] mx-auto flex flex-col md:flex-row justify-between gap-8">
    <div>
      <h2 class="text-headline-lg font-headline-lg text-white uppercase tracking-tighter mb-2">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="hover:text-gray-300 transition-colors"><?php bloginfo('name'); ?></a>
      </h2>
      <p class="text-on-primary-container text-sm max-w-xs"><?php bloginfo('description'); ?></p>
    </div>
    <div class="flex gap-12">
      <div class="flex flex-col gap-3">
        <h4 class="text-white font-label-lg text-label-lg uppercase tracking-wider mb-2">Sections</h4>
        <a class="text-on-primary-container hover:text-white text-label-sm font-label-sm transition-colors" href="<?php echo esc_url(home_url('/')); ?>">Home</a>
        <a class="text-on-primary-container hover:text-white text-label-sm font-label-sm transition-colors" href="<?php echo esc_url(home_url('/category/national')); ?>">National</a>
        <a class="text-on-primary-container hover:text-white text-label-sm font-label-sm transition-colors" href="<?php echo esc_url(home_url('/category/world')); ?>">World</a>
        <a class="text-on-primary-container hover:text-white text-label-sm font-label-sm transition-colors" href="<?php echo esc_url(home_url('/category/sports')); ?>">Sports</a>
        <a class="text-on-primary-container hover:text-white text-label-sm font-label-sm transition-colors" href="<?php echo esc_url(home_url('/category/entertainment')); ?>">Entertainment</a>
        <a class="text-on-primary-container hover:text-white text-label-sm font-label-sm transition-colors" href="<?php echo esc_url(home_url('/category/opinion')); ?>">Opinion</a>
      </div>
      <div class="flex flex-col gap-3">
        <h4 class="text-white font-label-lg text-label-lg uppercase tracking-wider mb-2">Follow Us</h4>
        <a class="text-on-primary-container hover:text-white text-label-sm font-label-sm transition-colors" href="https://www.facebook.com/share/1UpHD9WnrN/" target="_blank" rel="noopener">Facebook</a>
        <a class="text-on-primary-container hover:text-white text-label-sm font-label-sm transition-colors" href="https://www.instagram.com/thepublicbrief.news?igsi=Nzg1dzMxOGdyY3kw" target="_blank" rel="noopener">Instagram</a>
        <a class="text-on-primary-container hover:text-white text-label-sm font-label-sm transition-colors" href="https://whatsapp.com/channel/0029Vb8gQJO5q08hRqjj4n2d" target="_blank" rel="noopener">WhatsApp</a>
      </div>
    </div>
  </div>
  <div class="max-w-[1280px] mx-auto mt-12 pt-6 border-t border-surface-tint text-on-primary-container text-label-sm font-label-sm">
    &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved.
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>

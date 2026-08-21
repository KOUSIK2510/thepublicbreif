/**
 * The Public Brief — WordPress Theme JS
 * Hamburger menu toggle + smooth scroll
 */
document.addEventListener('DOMContentLoaded', function () {

  // ── Hamburger Menu ─────────────────────────────────────────────────────────
  var btn  = document.getElementById('hamburger-btn');
  var menu = document.getElementById('hamburger-menu');

  if (btn && menu) {
    btn.addEventListener('click', function (e) {
      e.stopPropagation();
      menu.classList.toggle('hidden');
    });
    document.addEventListener('click', function () {
      menu.classList.add('hidden');
    });
  }

  // ── Smooth scroll for anchor links ─────────────────────────────────────────
  document.querySelectorAll('a[href^="#"]').forEach(function (link) {
    link.addEventListener('click', function (e) {
      var id = link.getAttribute('href').substring(1);
      var el = document.getElementById(id);
      if (el) {
        e.preventDefault();
        window.scrollTo({ top: el.offsetTop - 120, behavior: 'smooth' });
      }
    });
  });

  // ── Active nav highlight ────────────────────────────────────────────────────
  var currentPath = window.location.pathname;
  document.querySelectorAll('nav a').forEach(function (a) {
    if (a.href && a.href.includes(currentPath) && currentPath !== '/') {
      a.classList.add('nav-active');
    }
  });

});

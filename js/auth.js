/**
 * The Public Brief — Auth Module
 * Handles: token management, route guards, nav auth state, logout
 */
(function () {
  'use strict';

  const API_BASE = '';

  /* ── Token helpers ──────────────────────────────────────────── */
  function getToken() {
    return localStorage.getItem('pb_token') || null;
  }

  function getUser() {
    try {
      return JSON.parse(localStorage.getItem('pb_user') || 'null');
    } catch (e) {
      return null;
    }
  }

  function clearSession() {
    localStorage.removeItem('pb_token');
    localStorage.removeItem('pb_user');
  }

  /* ── Token expiry check ─────────────────────────────────────── */
  function isTokenExpired(token) {
    try {
      var payload = JSON.parse(atob(token.split('.')[1]));
      return payload.exp * 1000 < Date.now();
    } catch (e) {
      return true;
    }
  }

  /* ── Verify token against backend ──────────────────────────── */
  function verifyToken(token) {
    return fetch(API_BASE + '/api/auth/me', {
      headers: { 'Authorization': 'Bearer ' + token }
    }).then(function (res) {
      if (!res.ok) throw new Error('Invalid or expired session');
      return res.json();
    });
  }

  /* ── Logout ─────────────────────────────────────────────────── */
  window.pbLogout = function (redirectTo) {
    clearSession();
    window.location.href = redirectTo || 'login.html';
  };

  /* ── Route Guard — redirect if not logged in ────────────────── */
  window.requireAuth = function (redirectTo) {
    var token = getToken();
    if (!token || isTokenExpired(token)) {
      clearSession();
      window.location.href = redirectTo || 'login.html';
      return false;
    }
    return true;
  };

  /* ── Admin Route Guard ──────────────────────────────────────── */
  window.requireAdmin = function () {
    var token = getToken();
    var user = getUser();
    if (!token || isTokenExpired(token) || !user || user.role !== 'admin') {
      clearSession();
      var returnUrl = encodeURIComponent(window.location.pathname + window.location.search);
      window.location.href = 'login.html?redirect=' + returnUrl + '&reason=admin';
      return false;
    }
    return true;
  };

  /* ── Update Navigation Bar Based on Auth State ──────────────── */
  function updateNav() {
    var token = getToken();
    var user = getUser();
    var isLoggedIn = token && !isTokenExpired(token) && user;

    // Find all login links and replace/augment them
    document.querySelectorAll('a[href="login.html"]').forEach(function (loginLink) {
      if (isLoggedIn) {
        // Replace login link with user menu
        var parent = loginLink.parentNode;
        if (!parent.querySelector('.pb-user-menu')) {
          var wrapper = document.createElement('div');
          wrapper.className = 'pb-user-menu';
          wrapper.style.cssText = 'display:flex;align-items:center;gap:10px;position:relative;';

          var greeting = document.createElement('span');
          greeting.className = 'pb-user-greeting';
          greeting.style.cssText = 'font-size:12px;font-weight:600;letter-spacing:0.05em;text-transform:uppercase;color:#444748;cursor:default;';
          greeting.textContent = user.name || user.username || 'Reader';
          greeting.title = (user.role === 'admin' ? '🔐 Admin' : '📰 Subscriber') + ' · ' + (user.email || '');

          var logoutBtn = document.createElement('button');
          logoutBtn.className = 'pb-logout-btn';
          logoutBtn.style.cssText = 'font-size:12px;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;color:#B00020;background:none;border:1px solid #B00020;padding:4px 10px;cursor:pointer;border-radius:3px;transition:all 0.2s;';
          logoutBtn.textContent = 'Logout';
          logoutBtn.addEventListener('mouseenter', function () { this.style.background = '#B00020'; this.style.color = '#fff'; });
          logoutBtn.addEventListener('mouseleave', function () { this.style.background = 'none'; this.style.color = '#B00020'; });
          logoutBtn.addEventListener('click', function () { window.pbLogout('index.html'); });

          wrapper.appendChild(greeting);

          // If admin, add admin panel link
          if (user.role === 'admin') {
            var adminLink = document.createElement('a');
            adminLink.href = 'admin.html';
            adminLink.className = 'pb-admin-link';
            adminLink.style.cssText = 'font-size:12px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#fff;background:#B00020;text-decoration:none;padding:4px 10px;border-radius:3px;';
            adminLink.textContent = 'Admin';
            wrapper.appendChild(adminLink);
          }

          wrapper.appendChild(logoutBtn);
          parent.insertBefore(wrapper, loginLink);
          loginLink.style.display = 'none';
        }
      }
    });
  }

  /* ── Show toast with reason for redirect ────────────────────── */
  function checkRedirectReason() {
    var params = new URLSearchParams(window.location.search);
    var reason = params.get('reason');
    if (reason === 'admin') {
      setTimeout(function () {
        if (window.showPBToast) {
          window.showPBToast('⚠ Admin access required. Please log in.');
        }
      }, 400);
    }
  }

  /* ── Wire logout buttons ────────────────────────────────────── */
  function wireLogoutButtons() {
    document.querySelectorAll('[data-pb-logout], #logout-btn, #logout-btn-top').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var redirect = btn.dataset.pbLogout || 'index.html';
        window.pbLogout(redirect);
      });
    });
  }

  /* ── Init ────────────────────────────────────────────────────── */
  document.addEventListener('DOMContentLoaded', function () {
    updateNav();
    wireLogoutButtons();
    checkRedirectReason();

    // Refresh user data from server if logged in (optional, non-blocking)
    var token = getToken();
    var user = getUser();
    if (token && !isTokenExpired(token)) {
      verifyToken(token).then(function (data) {
        // Update stored user data from server
        var updatedUser = {
          name: data.user.fullname,
          username: data.user.username,
          email: data.user.email,
          role: data.user.role
        };
        localStorage.setItem('pb_user', JSON.stringify(updatedUser));
      }).catch(function () {
        // Token is invalid — clear session silently unless on protected page
        var protectedPages = ['admin.html'];
        var page = window.location.pathname.split('/').pop();
        if (protectedPages.includes(page)) {
          window.pbLogout('login.html');
        } else {
          clearSession();
        }
      });
    }
  });

})();

/* APARK IT Solutions — progressive enhancements (site works without JS). */
(function () {
  'use strict';
  var doc = document.documentElement;
  doc.classList.add('js');

  // Header shadow on scroll
  var header = document.querySelector('.site-header');
  if (header) {
    var onScroll = function () { header.classList.toggle('is-scrolled', window.scrollY > 8); };
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
  }

  // Mobile navigation
  var toggle = document.querySelector('.nav-toggle');
  var nav = document.getElementById('site-nav');
  if (toggle && nav) {
    var setOpen = function (open) {
      toggle.setAttribute('aria-expanded', String(open));
      toggle.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
      nav.classList.toggle('is-open', open);
      document.body.classList.toggle('nav-open', open);
      if (open) { var first = nav.querySelector('a'); if (first) first.focus(); }
    };
    toggle.addEventListener('click', function () { setOpen(toggle.getAttribute('aria-expanded') !== 'true'); });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && nav.classList.contains('is-open')) { setOpen(false); toggle.focus(); }
    });
    nav.addEventListener('click', function (e) { if (e.target.closest('a')) setOpen(false); });
    window.matchMedia('(min-width: 992px)').addEventListener('change', function (m) { if (m.matches) setOpen(false); });
  }

  // Portfolio filters: instant client-side filtering, server-side fallback via ?category=
  var filterBar = document.querySelector('[data-filter-bar]');
  if (filterBar) {
    var items = document.querySelectorAll('[data-category]');
    filterBar.addEventListener('click', function (e) {
      var link = e.target.closest('a[data-filter]');
      if (!link) return;
      e.preventDefault();
      var value = link.getAttribute('data-filter');
      filterBar.querySelectorAll('a[data-filter]').forEach(function (a) {
        var active = a === link;
        a.classList.toggle('is-active', active);
        if (active) a.setAttribute('aria-current', 'true'); else a.removeAttribute('aria-current');
      });
      items.forEach(function (el) { el.classList.toggle('is-hidden', value !== '' && el.getAttribute('data-category') !== value); });
      history.replaceState(null, '', link.href);
    });
  }

  // Forms: loading state and double-submit prevention
  document.querySelectorAll('form[data-loading]').forEach(function (form) {
    form.addEventListener('submit', function () {
      var btn = form.querySelector('[type="submit"]');
      if (!btn) return;
      if (btn.classList.contains('is-loading')) return;
      btn.classList.add('is-loading');
      btn.setAttribute('aria-disabled', 'true');
      setTimeout(function () { btn.disabled = true; }, 0);
      var label = btn.querySelector('[data-label]');
      if (label) label.textContent = btn.getAttribute('data-loading-text') || 'Sending…';
    });
  });

  // Focus first invalid field after server-side validation
  var invalid = document.querySelector('[aria-invalid="true"]');
  if (invalid) invalid.focus();

  // Subtle reveal of below-the-fold sections
  if ('IntersectionObserver' in window && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) { if (en.isIntersecting) { en.target.classList.add('is-visible'); io.unobserve(en.target); } });
    }, { rootMargin: '0px 0px -8% 0px' });
    document.querySelectorAll('.reveal').forEach(function (el) {
      if (el.getBoundingClientRect().top < window.innerHeight) el.classList.add('is-visible'); else io.observe(el);
    });
  } else {
    document.querySelectorAll('.reveal').forEach(function (el) { el.classList.add('is-visible'); });
  }
})();

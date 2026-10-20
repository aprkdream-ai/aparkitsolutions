/* Google Analytics 4 loader. Kept out of inline HTML so the CSP can stay strict;
   gtag.js is fetched on first interaction (or 5 s after load) so it never delays rendering. */
(function () {
  'use strict';
  var me = document.currentScript, id = me && me.getAttribute('data-ga4');
  if (!id) return;
  window.dataLayer = window.dataLayer || [];
  window.gtag = function () { window.dataLayer.push(arguments); };
  window.gtag('js', new Date());
  window.gtag('config', id);
  var load = function () {
    var s = document.createElement('script');
    s.async = true;
    s.src = 'https://www.googletagmanager.com/gtag/js?id=' + encodeURIComponent(id);
    document.head.appendChild(s);
  };
  // Load on the first interaction, or 5 s after page load, so gtag.js never competes with rendering.
  var done = false, events = ['scroll', 'pointerdown', 'keydown', 'touchstart', 'mousemove'];
  var go = function () {
    if (done) return; done = true;
    events.forEach(function (e) { window.removeEventListener(e, go, { passive: true }); });
    load();
  };
  events.forEach(function (e) { window.addEventListener(e, go, { passive: true, once: true }); });
  var timer = function () { setTimeout(go, 5000); };
  if (document.readyState === 'complete') timer(); else window.addEventListener('load', timer);
})();

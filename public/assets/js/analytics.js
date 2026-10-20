/* Google Analytics 4 loader. Kept out of inline HTML so the CSP can stay strict;
   gtag.js is fetched after the page has loaded so it never delays rendering. */
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
  var idle = function () { ('requestIdleCallback' in window) ? requestIdleCallback(load, { timeout: 3000 }) : setTimeout(load, 1500); };
  if (document.readyState === 'complete') idle(); else window.addEventListener('load', idle);
})();

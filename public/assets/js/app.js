/* APARK IT Solutions — progressive enhancements (site works without JS). */
(function () {
  'use strict';
  var doc = document.documentElement;
  doc.classList.add('js');

  // Header shadow on scroll
  var header = document.querySelector('.site-header');
  if (header) {
    var ticking = false, scrolled = null;
    var update = function () {
      ticking = false;
      var now = window.scrollY > 8;
      if (now !== scrolled) { scrolled = now; header.classList.toggle('is-scrolled', now); }
    };
    update();
    // While scrolling, suspend hover effects so cards passing under the pointer don't trigger repaints.
    var idle;
    window.addEventListener('scroll', function () {
      if (!ticking) { ticking = true; requestAnimationFrame(update); }
      if (!idle) doc.classList.add('is-scrolling');
      clearTimeout(idle);
      idle = setTimeout(function () { idle = null; doc.classList.remove('is-scrolling'); }, 140);
    }, { passive: true });
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
    window.matchMedia('(min-width: 1200px)').addEventListener('change', function (m) { if (m.matches) setOpen(false); });
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

  // ---- Contact form: captcha helpers ----
  var setCaptcha = function (c) {
    if (!c) return;
    document.querySelectorAll('[data-captcha-question]').forEach(function (el) { el.textContent = c.question + (el.classList.contains('captcha-q') ? ' =' : ''); });
    document.querySelectorAll('[data-captcha-token]').forEach(function (el) { el.value = c.token; });
    var input = document.getElementById('f-captcha'); if (input) input.value = '';
  };
  var refreshBtn = document.querySelector('[data-captcha-refresh]');
  var refreshCaptcha = function (focus) {
    if (!refreshBtn) return Promise.resolve();
    refreshBtn.classList.add('is-spinning');
    return fetch(refreshBtn.getAttribute('data-captcha-refresh'), { headers: { 'Accept': 'application/json' }, credentials: 'same-origin', cache: 'no-store' })
      .then(function (r) { return r.json(); })
      .then(function (d) { setCaptcha(d); if (focus) document.getElementById('f-captcha').focus(); })
      .catch(function () {})
      .then(function () { setTimeout(function () { refreshBtn.classList.remove('is-spinning'); }, 600); });
  };
  if (refreshBtn) refreshBtn.addEventListener('click', function () { refreshCaptcha(true); });

  // ---- Contact form: AJAX submission (falls back to a normal POST without JS) ----
  var ajaxForm = document.querySelector('form[data-ajax]');
  if (ajaxForm && window.fetch && window.FormData) {
    var statusBox = document.querySelector('[data-form-status]');
    var submitBtn = ajaxForm.querySelector('[type="submit"]');
    var label = submitBtn.querySelector('[data-label]');
    var labelText = label ? label.textContent : '';
    var sprite = (document.querySelector('svg.icon use') || { getAttribute: function () { return ''; } }).getAttribute('href').split('#')[0];

    var icon = function (name) {
      var ns = 'http://www.w3.org/2000/svg', svg = document.createElementNS(ns, 'svg'), use = document.createElementNS(ns, 'use');
      svg.setAttribute('class', 'icon'); svg.setAttribute('aria-hidden', 'true'); use.setAttribute('href', sprite + '#' + name); svg.appendChild(use);
      return svg;
    };
    var showAlert = function (type, title, text) {
      var box = document.createElement('div'), body = document.createElement('div'), strong = document.createElement('strong');
      box.className = 'alert alert--' + type; box.setAttribute('role', type === 'success' ? 'status' : 'alert');
      strong.textContent = title; body.appendChild(strong); body.appendChild(document.createTextNode(text || ''));
      box.appendChild(icon(type === 'success' ? 'check-circle' : 'alert')); box.appendChild(body);
      statusBox.innerHTML = ''; statusBox.appendChild(box);
      var top = statusBox.getBoundingClientRect().top + window.scrollY - 110;
      if (statusBox.getBoundingClientRect().top < 80) window.scrollTo({ top: top, behavior: 'smooth' });
    };
    var clearErrors = function () {
      ajaxForm.querySelectorAll('.field-error').forEach(function (el) { el.remove(); });
      ajaxForm.querySelectorAll('[aria-invalid="true"]').forEach(function (el) {
        el.removeAttribute('aria-invalid');
        var ids = (el.getAttribute('aria-describedby') || '').split(' ').filter(function (x) { return x && !/-error$/.test(x); });
        if (ids.length) el.setAttribute('aria-describedby', ids.join(' ')); else el.removeAttribute('aria-describedby');
      });
    };
    var showErrors = function (errors) {
      var first = null;
      Object.keys(errors || {}).forEach(function (name) {
        var el = ajaxForm.querySelector('[name="' + name + '"]');
        if (!el || el.type === 'hidden') return;
        var field = el.closest('.field') || el.parentNode, msg = document.createElement('span');
        msg.className = 'field-error'; msg.id = 'f-' + name + '-error'; msg.textContent = errors[name][0];
        field.appendChild(msg);
        el.setAttribute('aria-invalid', 'true');
        el.setAttribute('aria-describedby', ((el.getAttribute('aria-describedby') || '') + ' ' + msg.id).trim());
        if (!first) first = el;
      });
      return first;
    };
    var setBusy = function (busy) {
      submitBtn.classList.toggle('is-loading', busy);
      submitBtn.disabled = busy;
      if (busy) submitBtn.setAttribute('aria-disabled', 'true'); else submitBtn.removeAttribute('aria-disabled');
      if (label) label.textContent = busy ? (submitBtn.getAttribute('data-loading-text') || 'Sending…') : labelText;
    };

    // Project brief: one step at a time. Without JS every step is visible and the form posts normally.
    var stepper = null, stepEls = ajaxForm.querySelectorAll('[data-step]');
    if (ajaxForm.hasAttribute('data-steps') && stepEls.length) {
      var labels = ajaxForm.querySelectorAll('[data-step-label]'), current = 0;
      var stepOf = function (el) {
        for (var i = 0; i < stepEls.length; i++) if (stepEls[i].contains(el)) return i;
        return 0;
      };
      var show = function (i, focus) {
        current = i;
        stepEls.forEach(function (s, n) { s.hidden = n !== i; });
        labels.forEach(function (l, n) {
          l.classList.toggle('is-done', n < i);
          if (n === i) l.setAttribute('aria-current', 'step'); else l.removeAttribute('aria-current');
        });
        if (focus) {
          var legend = stepEls[i].querySelector('legend');
          if (legend) legend.focus({ preventScroll: true });
          ajaxForm.scrollIntoView({ block: 'start' });
        }
      };
      // Checks one step in the browser for fast feedback; the server validates everything again.
      var validStep = function (i) {
        var errors = {}, seen = {};
        stepEls[i].querySelectorAll('[required]').forEach(function (el) {
          if (seen[el.name] || el.checkValidity()) return;
          seen[el.name] = true;
          errors[el.name] = [el.getAttribute('data-msg') || el.validationMessage];
        });
        clearErrors();
        var first = showErrors(errors);
        if (first) first.focus();
        return !first;
      };
      ajaxForm.addEventListener('click', function (e) {
        if (e.target.closest('[data-step-next]')) { if (validStep(current)) { clearErrors(); show(current + 1, true); } }
        else if (e.target.closest('[data-step-back]')) { clearErrors(); show(current - 1, true); }
      });
      // Capture phase: runs before the loading-state and AJAX handlers, and stops them when the step is not ready.
      ajaxForm.addEventListener('submit', function (e) {
        if (current < stepEls.length - 1) {
          e.preventDefault(); e.stopImmediatePropagation();
          if (validStep(current)) show(current + 1, true);
        } else if (!validStep(current)) {
          e.preventDefault(); e.stopImmediatePropagation();
        }
      }, true);
      stepper = { reveal: function (el) { show(stepOf(el), false); } };
      var firstInvalid = ajaxForm.querySelector('[aria-invalid="true"]');
      show(firstInvalid ? stepOf(firstInvalid) : 0, false);
    }

    ajaxForm.addEventListener('submit', function (e) {
      e.preventDefault();
      if (submitBtn.disabled) return;
      setBusy(true); clearErrors();
      fetch(ajaxForm.action, {
        method: 'POST', body: new FormData(ajaxForm), credentials: 'same-origin',
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
      })
        .then(function (r) { return r.json().catch(function () { return {}; }).then(function (d) { return { code: r.status, d: d }; }); })
        .then(function (res) {
          var d = res.d;
          if (res.code === 200 && d.status === 'sent') {
            ajaxForm.reset();
            if (stepper) ajaxForm.hidden = true;
            showAlert('success', d.title, ' ' + d.message);
            if (window.gtag) {
              window.gtag('event', 'generate_lead', { form: ajaxForm.getAttribute('data-form-name') || 'contact' });
              if (stepper) window.gtag('event', 'project_brief_complete');
            }
          } else if (res.code === 422) {
            var first = showErrors(d.errors), n = Object.keys(d.errors || {}).length;
            showAlert('error', 'Please check the highlighted fields.', ' ' + (n === 1 ? 'One field needs' : n + ' fields need') + ' your attention.');
            if (first) { if (stepper) stepper.reveal(first); first.focus({ preventScroll: false }); }
          } else if (res.code === 429) {
            showAlert('error', 'Too many attempts.', ' Please wait a few minutes and try again.');
          } else if (res.code === 419) {
            showAlert('error', 'Your session has expired.', ' Please refresh the page and submit the form again.');
          } else {
            showAlert('error', d.title || 'Sorry, we could not send your message.', ' ' + (d.message || 'Please try again in a few minutes.'));
          }
          // Every attempt consumes the captcha: use the fresh one from the response, or fetch one.
          if (d.captcha) setCaptcha(d.captcha); else refreshCaptcha(false);
        })
        .catch(function () {
          showAlert('error', 'Network error.', ' Please check your connection and try again.');
          refreshCaptcha(false);
        })
        .then(function () { setBusy(false); });
    });
  }

  // Focus first invalid field after server-side validation
  var invalid = document.querySelector('[aria-invalid="true"]');
  if (invalid) invalid.focus();

  // Conversion clicks. Only the type of link and page area are sent, never contact details.
  document.addEventListener('click', function (e) {
    var a = e.target.closest ? e.target.closest('a[href]') : null;
    if (!a || !window.gtag) return;
    var href = a.getAttribute('href'), area = a.closest('header') ? 'header' : a.closest('footer') ? 'footer' : 'page';
    if (href.indexOf('tel:') === 0) window.gtag('event', 'click_to_call', { link_area: area });
    else if (href.indexOf('https://wa.me/') === 0) window.gtag('event', 'whatsapp_click', { link_area: area });
  });
})();

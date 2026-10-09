/* RMDHost – WordPress glue: AJAX contact form + page-builder re-mounting. */
(function () {
  'use strict';

  /* Contact form (RMDHost Core): submit over admin-ajax, show inline status. */
  document.addEventListener('submit', function (e) {
    var form = e.target.closest && e.target.closest('form[data-contact][data-ajax]');
    if (!form || !window.fetch || !window.FormData) return;
    e.preventDefault();
    var btn = form.querySelector('[type=submit]'), status = form.querySelector('.form-status');
    var i18n = (window.RMD && window.RMD.i18n) || {};
    btn.disabled = true;
    fetch(form.getAttribute('data-ajax'), { method: 'POST', body: new FormData(form), credentials: 'same-origin' })
      .then(function (r) { return r.json(); })
      .then(function (res) {
        var ok = res && res.success;
        status.hidden = false;
        status.className = 'form-status ' + (ok ? 'is-ok' : 'is-error');
        status.textContent = (res && res.data && res.data.message) || (ok ? i18n.sent : i18n.error) || '';
        if (ok) form.reset();
      })
      .catch(function () {
        status.hidden = false;
        status.className = 'form-status is-error';
        status.textContent = i18n.error || 'Something went wrong. Please email us instead.';
      })
      .then(function () { btn.disabled = false; });
  });

  /* Elementor: bind tabs, carousels, counters… on widgets rendered in the editor or loaded later. */
  window.addEventListener('elementor/frontend/init', function () {
    if (!window.elementorFrontend || !window.elementorFrontend.hooks) return;
    window.elementorFrontend.hooks.addAction('frontend/element_ready/global', function ($scope) {
      if (window.RMDMount && $scope && $scope[0]) window.RMDMount($scope[0]);
    });
  });
})();

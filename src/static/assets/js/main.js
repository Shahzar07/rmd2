/* RMDHost – site interactions (no dependencies) */
(function () {
  'use strict';
  var doc = document.documentElement;
  var $ = function (s, r) { return (r || document).querySelector(s); };
  var $$ = function (s, r) { return Array.prototype.slice.call((r || document).querySelectorAll(s)); };
  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var store = {
    get: function (k) { try { return localStorage.getItem(k); } catch (e) { return null; } },
    set: function (k, v) { try { localStorage.setItem(k, v); } catch (e) {} },
  };

  /* ───────── theme */
  function currentTheme() {
    var t = doc.getAttribute('data-theme');
    return t || 'light'; // white by default; dark only when chosen
  }
  $$('[data-theme-toggle]').forEach(function (b) {
    b.addEventListener('click', function () {
      var next = currentTheme() === 'dark' ? 'light' : 'dark';
      doc.setAttribute('data-theme', next);
      store.set('rmd-theme', next);
    });
  });

  /* ───────── header */
  var header = $('[data-header]');
  var onScroll = function () { header.classList.toggle('scrolled', window.scrollY > 20); };
  onScroll();
  window.addEventListener('scroll', onScroll, { passive: true });

  /* ───────── mega menu */
  var items = $$('.has-mega');
  function closeAll(except) {
    items.forEach(function (it) {
      if (it === except) return;
      it.classList.remove('open');
      $('.nav-link', it).setAttribute('aria-expanded', 'false');
    });
  }
  items.forEach(function (it) {
    var btn = $('.nav-link', it), t;
    var open = function () { clearTimeout(t); closeAll(it); it.classList.add('open'); btn.setAttribute('aria-expanded', 'true'); };
    var close = function () { t = setTimeout(function () { it.classList.remove('open'); btn.setAttribute('aria-expanded', 'false'); }, 160); };
    it.addEventListener('mouseenter', open);
    it.addEventListener('mouseleave', close);
    btn.addEventListener('click', function () { it.classList.contains('open') ? (it.classList.remove('open'), btn.setAttribute('aria-expanded', 'false')) : open(); });
    it.addEventListener('focusout', function (e) { if (!it.contains(e.relatedTarget)) close(); });
  });
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape') { closeAll(); closeDrawer(); } });
  document.addEventListener('click', function (e) { if (!e.target.closest('.has-mega')) closeAll(); });

  /* ───────── mobile drawer */
  var drawer = $('[data-drawer]'), menuBtn = $('[data-menu-open]');
  function closeDrawer() { if (!drawer || drawer.hidden) return; drawer.hidden = true; document.body.style.overflow = ''; menuBtn.setAttribute('aria-expanded', 'false'); menuBtn.focus(); }
  if (menuBtn) menuBtn.addEventListener('click', function () { drawer.hidden = false; document.body.style.overflow = 'hidden'; menuBtn.setAttribute('aria-expanded', 'true'); $('[data-menu-close]').focus(); });
  $$('[data-menu-close]').forEach(function (b) { b.addEventListener('click', closeDrawer); });
  $$('.drawer a').forEach(function (a) { a.addEventListener('click', closeDrawer); });

  /* ───────── currency */
  var cfg = (window.RMD && window.RMD.currency) || { default: 'GBP', list: [{ code: 'GBP', symbol: '£', rate: 1 }] };
  function applyCurrency(code) {
    var c = cfg.list.filter(function (x) { return x.code === code; })[0] || cfg.list[0];
    $$('[data-price]').forEach(function (el) {
      var gbp = parseFloat(el.getAttribute('data-gbp'));
      var v = c.code === 'USD' && el.getAttribute('data-usd') ? parseFloat(el.getAttribute('data-usd')) : gbp * c.rate;
      var txt = c.code === 'GBP' && Number.isInteger(gbp) ? String(gbp) : v.toFixed(2);
      $('[data-sym]', el).textContent = c.symbol;
      var amt = $('[data-amt]', el);
      if (amt.textContent !== txt) {
        amt.textContent = txt;
        if (!reduce && el.animate) el.animate([{ opacity: 0, transform: 'translateY(6px)' }, { opacity: 1, transform: 'none' }], { duration: 350, easing: 'ease-out' });
      }
    });
    $$('[data-currency-select]').forEach(function (s) { s.value = c.code; });
  }
  var cur = store.get('rmd-currency') || cfg.default;
  applyCurrency(cur);
  $$('[data-currency-select]').forEach(function (s) {
    s.addEventListener('change', function () { store.set('rmd-currency', s.value); applyCurrency(s.value); });
  });

  /* ───────── reveal on scroll (staggered) */
  var reveals = $$('[data-reveal]');
  if ('IntersectionObserver' in window && !reduce) {
    var io = new IntersectionObserver(function (entries) {
      var batch = 0;
      entries.forEach(function (en) {
        if (!en.isIntersecting) return;
        en.target.style.setProperty('--rd', (batch++ * 0.07) + 's');
        en.target.classList.add('in');
        io.unobserve(en.target);
      });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });
    reveals.forEach(function (el) { io.observe(el); });
  } else {
    reveals.forEach(function (el) { el.classList.add('in'); });
  }

  /* ───────── counters */
  function runCounter(el) {
    var end = parseFloat(el.getAttribute('data-count')), dec = +el.getAttribute('data-decimals') || 0, t0;
    if (reduce) { el.textContent = end.toFixed(dec); return; }
    var step = function (t) {
      if (!t0) t0 = t;
      var p = Math.min((t - t0) / 1600, 1), e = 1 - Math.pow(1 - p, 3);
      el.textContent = (end * e).toFixed(dec);
      if (p < 1) requestAnimationFrame(step);
    };
    requestAnimationFrame(step);
  }
  var counters = $$('[data-count]');
  if ('IntersectionObserver' in window) {
    var co = new IntersectionObserver(function (es) { es.forEach(function (e) { if (e.isIntersecting) { runCounter(e.target); co.unobserve(e.target); } }); }, { threshold: 0.5 });
    counters.forEach(function (c) { co.observe(c); });
  } else counters.forEach(runCounter);

  /* ───────── tabs with sliding indicator */
  $$('[data-tabs]').forEach(function (wrap) {
    var list = $('[role=tablist]', wrap), tabs = $$('[role=tab]', list), ind = $('.tab-ind', list);
    function move(tab) {
      if (!ind) return;
      ind.style.width = tab.offsetWidth + 'px';
      ind.style.transform = 'translateX(' + (tab.offsetLeft - 4) + 'px)';
    }
    function select(tab, focus) {
      tabs.forEach(function (t) {
        var on = t === tab;
        t.setAttribute('aria-selected', on);
        t.tabIndex = on ? 0 : -1;
        var p = document.getElementById(t.getAttribute('aria-controls'));
        if (p) p.hidden = !on;
      });
      move(tab);
      if (focus) tab.focus();
      if (tab.scrollIntoView && list.scrollWidth > list.clientWidth) tab.scrollIntoView({ block: 'nearest', inline: 'center', behavior: 'smooth' });
      wrap.dispatchEvent(new CustomEvent('tabchange'));
    }
    tabs.forEach(function (t, i) {
      t.addEventListener('click', function () { select(t); });
      t.addEventListener('keydown', function (e) {
        var n = e.key === 'ArrowRight' ? 1 : e.key === 'ArrowLeft' ? -1 : 0;
        if (n) { e.preventDefault(); select(tabs[(i + n + tabs.length) % tabs.length], true); }
      });
    });
    var start = tabs.filter(function (t) { return t.getAttribute('aria-selected') === 'true'; })[0] || tabs[0];
    requestAnimationFrame(function () { move(start); });
    window.addEventListener('resize', function () { move(tabs.filter(function (t) { return t.getAttribute('aria-selected') === 'true'; })[0]); });
    if (document.fonts && document.fonts.ready) document.fonts.ready.then(function () { move(tabs.filter(function (t) { return t.getAttribute('aria-selected') === 'true'; })[0]); });
  });

  /* ───────── accordions: FAQ + plan details */
  function bindToggle(btn) {
    btn.addEventListener('click', function () {
      var panel = document.getElementById(btn.getAttribute('aria-controls'));
      var open = btn.getAttribute('aria-expanded') !== 'true';
      btn.setAttribute('aria-expanded', open);
      panel.classList.toggle('open', open);
    });
  }
  $$('.faq-q, [data-more]').forEach(bindToggle);

  /* ───────── expanding support cards (auto-rotate) */
  $$('[data-xcards]').forEach(function (wrap) {
    var cards = $$('.xcard', wrap), idx = cards.findIndex(function (c) { return c.classList.contains('active'); }), timer;
    function act(i) {
      idx = i;
      cards.forEach(function (c, j) { c.classList.toggle('active', j === i); c.setAttribute('aria-expanded', j === i); });
    }
    cards.forEach(function (c, i) {
      c.addEventListener('mouseenter', function () { if (window.innerWidth > 900) { stop(); act(i); } });
      c.addEventListener('click', function () { stop(); act(i); });
      c.addEventListener('focus', function () { stop(); act(i); });
    });
    function stop() { clearInterval(timer); }
    function play() { if (!reduce) timer = setInterval(function () { act((idx + 1) % cards.length); }, 4200); }
    wrap.addEventListener('mouseleave', play);
    play();
  });

  /* ───────── carousels */
  $$('[data-carousel]').forEach(function (car) {
    var head = car.previousElementSibling;
    var prev = head && $('[data-car-prev]', head), next = head && $('[data-car-next]', head);
    var by = function (d) { var c = car.firstElementChild; car.scrollBy({ left: d * (c ? c.offsetWidth + 16 : 300), behavior: 'smooth' }); };
    if (prev) prev.addEventListener('click', function () { by(-1); });
    if (next) next.addEventListener('click', function () { by(1); });
  });

  /* ───────── spotlight cards */
  document.addEventListener('pointermove', function (e) {
    var el = e.target.closest && e.target.closest('[data-spot]');
    if (!el) return;
    var r = el.getBoundingClientRect();
    el.style.setProperty('--mx', (e.clientX - r.left) + 'px');
    el.style.setProperty('--my', (e.clientY - r.top) + 'px');
  }, { passive: true });

  /* ───────── typewriter placeholder */
  $$('[data-typewriter]').forEach(function (input) {
    var phrases = JSON.parse(input.getAttribute('data-typewriter')), p = 0, c = 0, del = false;
    if (reduce) { input.placeholder = phrases[0]; return; }
    (function tick() {
      if (document.activeElement === input || input.value) { input.placeholder = 'Describe what you want to host…'; return setTimeout(tick, 600); }
      var word = phrases[p];
      input.placeholder = word.slice(0, c) + '▍';
      if (!del && c < word.length) c++;
      else if (!del) { del = true; return setTimeout(tick, 1600); }
      else if (c > 0) c--;
      else { del = false; p = (p + 1) % phrases.length; }
      setTimeout(tick, del ? 28 : 55);
    })();
  });

  /* ───────── "tell us your project" recommender */
  var rules = [
    [/minecraft|cs2|counter|arma|gta|fivem|rust|game|teamspeak|server for \d+ players/i, 'Game Servers', '/game-servers/', 'Anti-DDoS Game protection, high clock speeds and up to 256 GB ECC RAM keep your players online.'],
    [/mac|ios|xcode|swift|iphone|ipad/i, 'macOS VPS', '/macos-vps/', 'A virtual Mac on Apple hardware for Xcode builds, CI runners and app testing.'],
    [/\bai\b|llm|gpt|chatgpt|ollama|llama|agent|model|rag|n8n|automation/i, 'AI VPS', '/ai-vps/', 'High-memory NVMe servers with one-click Ollama, Open WebUI and n8n templates – private by default.'],
    [/windows|rdp|remote desktop|metatrader|mt4|mt5|forex|trading|\.net|asp|mssql|iis/i, 'Windows VPS', '/windows-vps/', 'Ryzen + NVMe with Remote Desktop, free backups and a dedicated IP – ideal for trading platforms and Windows apps.'],
    [/storage|files|dropbox|owncloud|nextcloud|photos|backup/i, 'OwnCloud Storage', '/owncloud-storage/', 'Your own private cloud with end-to-end encryption and up to 1.6 TB of storage.'],
    [/database|postgres|mysql|saas|high traffic|enterprise|virtuali|proxmox|bare metal|dedicated|heavy/i, 'Dedicated Servers', '/dedicated-servers/', 'A whole Intel Xeon server with ECC RAM and RAID storage for demanding, high-traffic workloads.'],
    [/usa|america|instant|us server/i, 'Instant Dedicated USA', '/instant-dedicated-servers-usa/', 'Bare metal in the USA, online in minutes with free setup on in-stock servers.'],
    [/docker|kubernetes|api|node|python|app|staging|ci/i, 'SSD VPS (OpenStack)', '/ssd-vps/', 'NVMe VPS with up to 12 vCores, daily backups and a free control panel – great for apps and APIs.'],
  ];
  $$('[data-idea]').forEach(function (form) {
    var out = $('[data-idea-result]');
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      var input = $('input', form), q = input.value.trim() || input.placeholder.replace('▍', '');
      var m = rules.filter(function (r) { return r[0].test(q); })[0] || [null, 'Linux VPS', '/vps/', 'Root access, SSD-boosted storage and unlimited bandwidth from £8.99/mo – the best all-rounder for websites and apps.'];
      out.hidden = false;
      out.innerHTML = '';
      var wrap = document.createElement('div');
      var label = document.createElement('p'); label.className = 'pill pill-xs'; label.textContent = 'Recommended for “' + q.slice(0, 60) + '”';
      var h = document.createElement('h3'); h.textContent = m[1];
      var p = document.createElement('p'); p.textContent = m[3];
      var a = document.createElement('a'); a.className = 'btn btn-primary'; a.href = m[2]; a.textContent = 'See ' + m[1] + ' plans';
      wrap.append(label, h, p, a);
      out.appendChild(wrap);
      out.style.animation = 'none'; void out.offsetWidth; out.style.animation = '';
    });
  });

  /* ───────── terminal typing scenes */
  function typeTerminal(pre) {
    var lines = JSON.parse(pre.getAttribute('data-term'));
    if (reduce) { pre.textContent = lines.join('\n'); return; }
    var li = 0, ci = 0;
    function render(partial) {
      pre.innerHTML = '';
      lines.slice(0, li).forEach(function (l) { pre.appendChild(lineEl(l)); pre.appendChild(document.createTextNode('\n')); });
      if (partial !== undefined) { pre.appendChild(lineEl(partial)); }
      var cur = document.createElement('span'); cur.className = 'cursor'; pre.appendChild(cur);
    }
    function lineEl(l) {
      var s = document.createElement('span');
      s.className = l.charAt(0) === '✓' ? 'ok' : l.charAt(0) === '›' ? 'dim' : '';
      s.textContent = l; return s;
    }
    (function tick() {
      if (li >= lines.length) { return setTimeout(function () { li = 0; ci = 0; tick(); }, 3200); }
      var line = lines[li];
      var isCmd = line.charAt(0) === '$';
      if (isCmd && ci < line.length) { ci++; render(line.slice(0, ci)); return setTimeout(tick, 38); }
      li++; ci = 0; render();
      setTimeout(tick, isCmd ? 420 : 380);
    })();
  }

  /* ───────── live sparklines + counters + streaming text */
  var sparks = {};
  function sparkPath(vals) {
    var w = 120, h = 36, step = w / (vals.length - 1);
    return vals.map(function (v, i) { return (i ? 'L' : 'M') + (i * step).toFixed(1) + ' ' + (h - v * h * 0.9 - 2).toFixed(1); }).join(' ');
  }
  function initSparks(root) {
    $$('[data-spark]', root).forEach(function (path) {
      var id = path.getAttribute('data-spark');
      var vals = Array.from({ length: 14 }, function () { return 0.25 + Math.random() * 0.45; });
      var fill = path.parentNode.querySelector('[data-spark-fill]');
      var val = path.closest('.gauge').querySelector('[data-spark-val]');
      sparks[id + Math.random()] = { path: path, fill: fill, val: val, vals: vals };
    });
  }
  function tickSparks() {
    Object.keys(sparks).forEach(function (k) {
      var s = sparks[k];
      if (!isVisible(s.path)) return;
      var last = s.vals[s.vals.length - 1];
      s.vals.shift();
      s.vals.push(Math.max(0.08, Math.min(0.95, last + (Math.random() - 0.5) * 0.25)));
      var d = sparkPath(s.vals);
      s.path.setAttribute('d', d);
      s.fill.setAttribute('d', d + ' L120 36 L0 36 Z');
      if (s.val) s.val.textContent = Math.round(s.vals[s.vals.length - 1] * 100) + '%';
    });
    $$('[data-counter-live]').forEach(function (el) {
      if (!isVisible(el)) return;
      var v = parseInt(el.textContent.replace(/,/g, ''), 10) || 0, max = +el.getAttribute('data-max') || Infinity;
      v = Math.min(max, Math.max(1, v + Math.round((Math.random() - 0.3) * (max === Infinity ? 9 : 3))));
      el.textContent = v.toLocaleString('en-GB');
    });
  }
  function isVisible(el) { var r = el.getBoundingClientRect(); return r.bottom > 0 && r.top < innerHeight && r.width > 0; }

  function streamText(el) {
    var full = el.getAttribute('data-stream'), words = full.split(' '), i = 0;
    if (reduce) { el.textContent = full; return; }
    (function tick() {
      if (!isVisible(el)) return setTimeout(tick, 800);
      if (i > words.length) { return setTimeout(function () { i = 0; tick(); }, 3500); }
      el.textContent = words.slice(0, i).join(' ') + (i < words.length ? ' ▍' : '');
      i++;
      setTimeout(tick, 70 + Math.random() * 90);
    })();
  }

  // Start scenes when first visible
  if ('IntersectionObserver' in window) {
    var so = new IntersectionObserver(function (es) {
      es.forEach(function (e) {
        if (!e.isIntersecting) return;
        var el = e.target;
        if (el.hasAttribute('data-term')) typeTerminal(el);
        if (el.hasAttribute('data-stream')) streamText(el);
        so.unobserve(el);
      });
    }, { threshold: 0.2 });
    $$('[data-term], [data-stream]').forEach(function (el) { so.observe(el); });
  }
  initSparks(document);
  tickSparks();
  if (!reduce) setInterval(tickSparks, 1200);

  $$('[data-clock]').forEach(function (el) {
    var f = function () { var d = new Date(); el.textContent = ('0' + d.getHours()).slice(-2) + ':' + ('0' + d.getMinutes()).slice(-2); };
    f(); setInterval(f, 30000);
  });

  /* ───────── data-centre map ↔ list highlighting */
  $$('[data-dc]').forEach(function (dc) {
    function hl(i, on) {
      $$('[data-loc="' + i + '"]', dc).forEach(function (el) { el.classList.toggle('hl', on); });
    }
    $$('.dc-list li', dc).forEach(function (li) {
      var i = li.getAttribute('data-loc');
      ['mouseenter', 'focus'].forEach(function (ev) { li.addEventListener(ev, function () { hl(i, true); }); });
      ['mouseleave', 'blur'].forEach(function (ev) { li.addEventListener(ev, function () { hl(i, false); }); });
    });
  });

  /* ───────── matrix rain (essentials visuals) */
  var glyphs = '01アイウエオカキクケコｱｲｳｴｵ#$%{}[]<>/\\=+*';
  $$('canvas[data-matrix]').forEach(function (cv) {
    var ctx = cv.getContext('2d'), cols = [], fs = 12, running = false, last = 0;
    function size() {
      var r = cv.getBoundingClientRect(), dpr = Math.min(2, window.devicePixelRatio || 1);
      cv.width = Math.max(1, r.width * dpr); cv.height = Math.max(1, r.height * dpr);
      ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
      var n = Math.ceil(r.width / fs);
      cols = Array.from({ length: n }, function () { return Math.random() * -40; });
      ctx.fillStyle = '#050605'; ctx.fillRect(0, 0, r.width, r.height);
    }
    function frame(t) {
      if (!running) return;
      requestAnimationFrame(frame);
      if (t - last < 60) return; // ~16 fps is plenty and cheap
      last = t;
      draw();
    }
    function draw() {
      var w = cv.width, h = cv.height / Math.min(2, window.devicePixelRatio || 1);
      ctx.fillStyle = 'rgba(5,6,5,0.16)'; ctx.fillRect(0, 0, w, h);
      ctx.font = fs + 'px "JetBrains Mono", monospace';
      for (var i = 0; i < cols.length; i++) {
        var y = cols[i] * fs;
        ctx.fillStyle = Math.random() > 0.96 ? '#e6fff0' : '#3ddc84';
        ctx.fillText(glyphs.charAt(Math.floor(Math.random() * glyphs.length)), i * fs, y);
        cols[i] = y > h && Math.random() > 0.975 ? 0 : cols[i] + 1;
      }
    }
    size();
    window.addEventListener('resize', size);
    if (reduce) { for (var k = 0; k < 40; k++) draw(); return; }
    if ('IntersectionObserver' in window) {
      new IntersectionObserver(function (es) {
        es.forEach(function (e) {
          if (e.isIntersecting && !running) { running = true; requestAnimationFrame(frame); }
          else if (!e.isIntersecting) running = false;
        });
      }).observe(cv);
    }
  });

  /* ───────── product subnav highlight */
  var sub = $('[data-subnav]');
  if (sub && 'IntersectionObserver' in window) {
    var links = $$('a', sub);
    var secs = links.map(function (a) { return document.querySelector(a.getAttribute('href')); }).filter(Boolean);
    var sio = new IntersectionObserver(function (es) {
      es.forEach(function (e) {
        if (!e.isIntersecting) return;
        links.forEach(function (a) { a.classList.toggle('on', a.getAttribute('href') === '#' + e.target.id); });
      });
    }, { rootMargin: '-45% 0px -50% 0px' });
    secs.forEach(function (s) { sio.observe(s); });
  }

  /* ───────── filters (instant servers, tutorials) */
  $$('[data-filter-group]').forEach(function (g) {
    var target = g.getAttribute('data-target') || '.srow:not(.shead)';
    var scope = g.parentNode;
    var chips = $$('[data-filter]', g), stockBox = $('[data-instock]', g), active = '*';
    function apply() {
      $$(target, scope).forEach(function (row) {
        var fam = row.getAttribute('data-family');
        var show = (active === '*' || fam === active) && (!stockBox || !stockBox.checked || row.getAttribute('data-stock') === 'true');
        row.classList.toggle('is-hidden', !show);
      });
    }
    chips.forEach(function (c) {
      c.addEventListener('click', function () {
        active = c.getAttribute('data-filter');
        chips.forEach(function (x) { x.classList.toggle('on', x === c); x.setAttribute('aria-pressed', x === c); });
        apply();
      });
    });
    if (stockBox) stockBox.addEventListener('change', apply);
  });

  /* ───────── knowledge base search */
  var kbForm = $('[data-kb-search]');
  if (kbForm) {
    var kbIn = $('input', kbForm);
    kbForm.addEventListener('submit', function (e) { e.preventDefault(); });
    kbIn.addEventListener('input', function () {
      var q = kbIn.value.trim().toLowerCase(), any = false;
      $$('.kb-cat').forEach(function (cat) {
        var vis = 0;
        $$('[data-kb-item]', cat).forEach(function (li) { var m = !q || li.textContent.toLowerCase().indexOf(q) > -1; li.classList.toggle('is-hidden', !m); if (m) vis++; });
        cat.classList.toggle('is-hidden', !vis);
        if (vis) any = true;
      });
      $('[data-kb-empty]').hidden = any;
    });
  }

  /* ───────── contact topic from ?topic= */
  var topicSel = $('[data-topic]');
  if (topicSel) {
    var tq = new URLSearchParams(location.search).get('topic');
    if (tq) {
      var opt = document.createElement('option'); opt.textContent = tq; opt.selected = true; topicSel.appendChild(opt);
    }
  }
  $$('[data-now]').forEach(function (el) { el.textContent = new Date().toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit' }); });

  /* ───────── background videos: play only while visible */
  var vids = $$('video[data-autoplay]');
  if (!reduce && 'IntersectionObserver' in window) {
    var vo = new IntersectionObserver(function (es) {
      es.forEach(function (e) {
        var v = e.target;
        if (e.isIntersecting) { if (v.preload === 'none') { v.preload = 'auto'; v.load(); } var pr = v.play(); if (pr && pr.catch) pr.catch(function () {}); }
        else v.pause();
      });
    }, { threshold: 0.15 });
    vids.forEach(function (v) { vo.observe(v); });
  }

  /* ───────── cookie consent */
  var CK = 'rmd-consent';
  var banner = $('[data-cookie]'), modal = $('[data-cookie-modal]');
  function readConsent() { try { return JSON.parse(store.get(CK)); } catch (e) { return null; } }
  function saveConsent(c) {
    c.necessary = true; c.ts = new Date().toISOString();
    store.set(CK, JSON.stringify(c));
    banner.hidden = true;
    if (modal.open) modal.close();
    document.dispatchEvent(new CustomEvent('rmd:consent', { detail: c }));
  }
  function syncModal() {
    var c = readConsent() || {};
    $$('[data-ck]', modal).forEach(function (i) { i.checked = !!c[i.getAttribute('data-ck')]; });
  }
  if (!readConsent()) setTimeout(function () { banner.hidden = false; }, 700);
  $$('[data-cookie-accept]').forEach(function (b) { b.addEventListener('click', function () { saveConsent({ analytics: true, marketing: true }); }); });
  $$('[data-cookie-reject]').forEach(function (b) { b.addEventListener('click', function () { saveConsent({ analytics: false, marketing: false }); }); });
  $$('[data-cookie-save]').forEach(function (b) {
    b.addEventListener('click', function () {
      var c = {};
      $$('[data-ck]', modal).forEach(function (i) { c[i.getAttribute('data-ck')] = i.checked; });
      saveConsent(c);
    });
  });
  $$('[data-cookie-manage]').forEach(function (b) {
    b.addEventListener('click', function () {
      syncModal();
      if (modal.showModal) modal.showModal(); else modal.setAttribute('open', '');
    });
  });
  // Load analytics only after consent, e.g.:
  // document.addEventListener('rmd:consent', function (e) { if (e.detail.analytics) { /* inject analytics script */ } });

  $$('[data-year]').forEach(function (el) { el.textContent = new Date().getFullYear(); });
})();

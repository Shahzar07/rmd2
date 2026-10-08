import { site, nav, footer } from '../data/site.mjs';
import { icon } from './icons.mjs';

export const esc = (s = '') =>
  String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');

export const logo = (cls = '') => `
<a href="/" class="logo ${cls}" aria-label="${site.name} home">
  <svg class="logo-mark" viewBox="0 0 32 32" aria-hidden="true"><rect x="2" y="2" width="28" height="28" rx="8" fill="currentColor"/><path class="logo-r" d="M11 24V8.5h6.2a4.6 4.6 0 0 1 0 9.2H11m6.4 0L22 24" fill="none" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
  <span class="logo-word">RMD<b>HOST</b></span>
</a>`;

const currencySelect = (id) => `
<label class="currency" for="${id}">
  <span class="sr-only">Currency</span>
  <select id="${id}" data-currency-select>
    ${site.currency.list.map((c) => `<option value="${c.code}">${c.symbol} ${c.code}</option>`).join('')}
  </select>
  ${icon('chevron', 'currency-caret')}
</label>`;

const themeToggle = `
<button class="icon-btn theme-toggle" type="button" data-theme-toggle aria-label="Toggle dark mode">
  ${icon('moon', 'only-light')}${icon('sun', 'only-dark')}
</button>`;

function megaPanel(item, i) {
  const cols = item.mega.map((col) => `
    <div class="mega-col">
      <p class="mega-title">${col.title}</p>
      <ul>
        ${col.items.map((l) => `
        <li><a href="${l.href}" class="mega-link">
          <span class="mega-ico">${icon(l.icon)}</span>
          <span><span class="mega-label">${l.label}${l.tag ? ` <em class="pill pill-xs">${l.tag}</em>` : ''}</span><span class="mega-desc">${l.desc}</span></span>
        </a></li>`).join('')}
      </ul>
    </div>`).join('');
  const promo = item.promo ? `
    <a class="mega-promo" href="${item.promo.href}">
      <span class="pill pill-invert">${item.promo.eyebrow}</span>
      <strong>${item.promo.title}</strong>
      <span>${item.promo.text}</span>
      <span class="link-arrow">${item.promo.cta} ${icon('arrow')}</span>
    </a>` : '';
  return `<div class="mega" id="mega-${i}" role="region" aria-label="${item.label}"><div class="mega-inner">${cols}${promo}</div></div>`;
}

export function header() {
  const desktop = nav.map((item, i) => item.mega
    ? `<li class="nav-item has-mega"><button class="nav-link" type="button" aria-expanded="false" aria-controls="mega-${i}">${item.label}${icon('chevron', 'nav-caret')}</button>${megaPanel(item, i)}</li>`
    : `<li class="nav-item"><a class="nav-link" href="${item.href}">${item.label}</a></li>`).join('');

  const mobile = nav.map((item) => item.mega
    ? `<details class="m-group"><summary>${item.label}${icon('chevron')}</summary>${item.mega.map((c) => `<p class="m-title">${c.title}</p>${c.items.map((l) => `<a href="${l.href}">${icon(l.icon)}${l.label}</a>`).join('')}`).join('')}</details>`
    : `<a class="m-link" href="${item.href}">${item.label}</a>`).join('');

  return `
<a class="skip" href="#main">Skip to content</a>
<header class="site-header" data-header>
  <div class="container header-row">
    ${logo()}
    <nav class="nav" aria-label="Main"><ul>${desktop}</ul></nav>
    <div class="header-actions">
      <a class="chip-btn hide-sm" href="/network-status/">${icon('pulse')}<span>Status</span></a>
      ${currencySelect('currency-desktop')}
      ${themeToggle}
      <a class="icon-btn" href="${site.loginUrl}" aria-label="Client area login">${icon('user')}</a>
      <button class="icon-btn menu-btn" type="button" data-menu-open aria-label="Open menu" aria-controls="drawer" aria-expanded="false">${icon('menu')}</button>
    </div>
  </div>
  <div class="container domainbar-row">
    <form class="domainbar" action="${site.domainSearchUrl.split('?')[0]}" method="get" role="search">
      <input type="hidden" name="a" value="add"><input type="hidden" name="domain" value="register">
      ${icon('search')}
      <label class="sr-only" for="dq">Search for a domain</label>
      <input id="dq" name="query" type="text" placeholder="Type the domain you want" autocomplete="off">
      <button class="btn btn-sm btn-white" type="submit">Search</button>
    </form>
    <p class="domainbar-note"><strong>No setup fees</strong><br>on VPS &amp; in-stock servers</p>
  </div>
</header>
<div class="drawer" id="drawer" data-drawer hidden>
  <div class="drawer-head">${logo()}<button class="icon-btn" type="button" data-menu-close aria-label="Close menu">${icon('close')}</button></div>
  <nav class="drawer-nav" aria-label="Mobile">${mobile}
    <a class="m-link" href="/network-status/">Network status</a>
  </nav>
  <div class="drawer-foot">
    ${currencySelect('currency-mobile')}
    ${themeToggle}
    <a class="btn btn-primary btn-block" href="${site.loginUrl}">Client area</a>
  </div>
</div>`;
}

export function siteFooter() {
  const cols = footer.map((c) => `
    <div class="f-col">
      <p class="f-title">${c.title}</p>
      <ul>${c.links.map(([l, h]) => `<li><a href="${h === 'CLIENT_AREA' ? site.loginUrl : h}">${l}</a></li>`).join('')}</ul>
    </div>`).join('');
  const socials = Object.entries(site.social).map(([k, v]) => `<a href="${v}" aria-label="${k}" rel="noopener" target="_blank">${icon(k)}</a>`).join('');
  const pays = ['VISA', 'Mastercard', 'AMEX', 'PayPal', 'Bank transfer', 'Crypto'].map((p) => `<span class="pay">${p}</span>`).join('');
  return `
<footer class="site-footer">
  <div class="container">
    <div class="f-grid">${cols}</div>
    <div class="f-mid">
      ${logo()}
      <div class="f-social">${socials}</div>
    </div>
    <div class="f-bottom">
      <div class="f-pays">${pays}</div>
      <div class="f-legal">
        <a href="/privacy-policy/">Privacy policy</a>
        <a href="/terms-of-service/">Terms of service</a>
        <a href="/cookie-policy/">Cookie policy</a>
        <button type="button" class="linklike" data-cookie-manage>Cookie settings</button>
      </div>
    </div>
    <div class="f-copy">
      <p>© <span data-year>${new Date().getFullYear()}</span> ${site.name} – ${site.tagline}.</p>
      <p>Prices are listed without VAT.</p>
    </div>
  </div>
</footer>`;
}

export function cookieUI() {
  return `
<div class="cookie" data-cookie hidden role="dialog" aria-live="polite" aria-label="Cookie consent">
  <div class="cookie-inner">
    <div class="cookie-text">
      <p class="cookie-title">${icon('cookie')} We care about your privacy</p>
      <p>We use cookies that are needed for the site to work, plus optional cookies for analytics and marketing. You can accept all, reject optional cookies or choose your preferences. Read our <a href="/cookie-policy/">Cookie policy</a> and <a href="/privacy-policy/">Privacy policy</a>.</p>
    </div>
    <div class="cookie-actions">
      <button type="button" class="btn btn-primary btn-sm" data-cookie-accept>Accept all</button>
      <button type="button" class="btn btn-outline btn-sm" data-cookie-reject>Reject all</button>
      <button type="button" class="btn btn-ghost btn-sm" data-cookie-manage>Manage preferences</button>
    </div>
  </div>
</div>
<dialog class="cookie-modal" data-cookie-modal aria-labelledby="ck-title">
  <form method="dialog" class="cookie-modal-inner">
    <div class="ck-head"><h2 id="ck-title">Cookie preferences</h2><button class="icon-btn" value="cancel" aria-label="Close">${icon('close')}</button></div>
    <p class="muted">Choose which optional cookies we may use. Necessary cookies are always on because the site cannot work without them.</p>
    <div class="ck-row"><div><strong>Necessary</strong><p>Security, load balancing, currency and theme preferences.</p></div><span class="switch is-locked"><input type="checkbox" checked disabled aria-label="Necessary cookies (always on)"><i></i></span></div>
    <div class="ck-row"><div><strong>Analytics</strong><p>Anonymous statistics that help us improve the website.</p></div><label class="switch"><input type="checkbox" name="analytics" data-ck="analytics" aria-label="Analytics cookies"><i></i></label></div>
    <div class="ck-row"><div><strong>Marketing</strong><p>Used to show relevant offers on other websites.</p></div><label class="switch"><input type="checkbox" name="marketing" data-ck="marketing" aria-label="Marketing cookies"><i></i></label></div>
    <div class="ck-actions">
      <button type="button" class="btn btn-outline btn-sm" data-cookie-reject>Reject all</button>
      <button type="button" class="btn btn-outline btn-sm" data-cookie-save>Save preferences</button>
      <button type="button" class="btn btn-primary btn-sm" data-cookie-accept>Accept all</button>
    </div>
  </form>
</dialog>`;
}

export function document({ path, title, description, body, schema = [], ogType = 'website', noindex = false }) {
  const canonical = site.url + path;
  const ld = [
    {
      '@context': 'https://schema.org',
      '@type': 'Organization',
      name: site.name,
      url: site.url,
      logo: `${site.url}/assets/img/logo.svg`,
      email: site.email,
      sameAs: Object.values(site.social),
    },
    ...schema,
  ];
  return `<!doctype html>
<html lang="en-GB">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>${esc(title)}</title>
<meta name="description" content="${esc(description)}">
${noindex ? '<meta name="robots" content="noindex">' : `<link rel="canonical" href="${canonical}">`}
<meta name="theme-color" content="#000000">
<meta property="og:type" content="${ogType}">
<meta property="og:site_name" content="${site.name}">
<meta property="og:title" content="${esc(title)}">
<meta property="og:description" content="${esc(description)}">
<meta property="og:url" content="${canonical}">
<meta property="og:image" content="${site.url}/assets/img/og.svg">
<meta name="twitter:card" content="summary_large_image">
<link rel="icon" href="/assets/img/favicon.svg" type="image/svg+xml">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/main.css">
<script>(function(){try{var t=localStorage.getItem('rmd-theme');if(t)document.documentElement.setAttribute('data-theme',t);}catch(e){}})();</script>
<script type="application/ld+json">${JSON.stringify(ld.length === 1 ? ld[0] : ld)}</script>
</head>
<body>
${header()}
<main id="main">
${body}
</main>
${siteFooter()}
${cookieUI()}
<script>window.RMD=${JSON.stringify({ currency: site.currency })};</script>
<script src="/assets/js/main.js" defer></script>
</body>
</html>
`;
}

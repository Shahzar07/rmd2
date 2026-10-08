import { site } from '../data/site.mjs';
import { locations } from '../data/content.mjs';
import { MAP, WORLD_PATH } from '../data/world-dots.mjs';
import { MEDIA } from '../data/media.mjs';
import { icon } from './icons.mjs';
import { esc } from './layout.mjs';

// ───────────────────────────── photos & videos (src/static/assets/media)
// Generated with Nano Banana 2.1 (images) and Seedance 2.5 (video).
export function img(name, alt = '', { cls = '', eager = false, sizes = '(max-width: 900px) 100vw, 50vw' } = {}) {
  const m = MEDIA[name] || {};
  return `<img class="${cls}" src="/assets/media/${name}.webp"${m.small ? ` srcset="/assets/media/${name}-sm.webp ${m.small}w, /assets/media/${name}.webp ${m.w}w" sizes="${sizes}"` : ''} alt="${esc(alt)}"${m.w ? ` width="${m.w}" height="${m.h}"` : ''} ${eager ? 'fetchpriority="high"' : 'loading="lazy"'} decoding="async">`;
}

export function vid(name, { cls = '', label = '' } = {}) {
  const m = MEDIA[name] || {};
  return `<video class="${cls}" muted loop playsinline preload="none" data-autoplay poster="/assets/media/${name}-poster.webp"${m.w ? ` width="${m.w}" height="${m.h}"` : ''}${label ? ` aria-label="${esc(label)}"` : ' aria-hidden="true"'}><source src="/assets/media/${name}.mp4" type="video/mp4"><source src="/assets/media/${name}.webm" type="video/webm"></video>`;
}

// ───────────────────────────── prices
export function money(gbp, { usd, per = '/mo', cls = '' } = {}) {
  if (gbp == null) return `<span class="price-soon ${cls}">Price coming soon</span>`;
  const fmt = Number.isInteger(gbp) ? String(gbp) : gbp.toFixed(2);
  return `<span class="price ${cls}" data-price data-gbp="${gbp}"${usd ? ` data-usd="${usd}"` : ''}><span data-sym>£</span><span data-amt>${fmt}</span></span>${per ? `<span class="per">${per}</span>` : ''}`;
}

export const orderUrl = (plan) => plan.order || `${site.orderFallback}?plan=${encodeURIComponent(plan.id || plan.name)}`;

// ───────────────────────────── headings
export const sectionHead = ({ eyebrow, title, text, center = true, cls = '' }) => `
<div class="s-head ${center ? 'center' : ''} ${cls}" data-reveal>
  ${eyebrow ? `<p class="eyebrow">${eyebrow}</p>` : ''}
  <h2 class="h2">${title}</h2>
  ${text ? `<p class="lead">${text}</p>` : ''}
</div>`;

export const checklist = (items, cls = '') => `<ul class="checks ${cls}">${items.map((i) => `<li>${icon('check')}${i}</li>`).join('')}</ul>`;

// ───────────────────────────── plan box
// Shows CPU | RAM | Storage | Bandwidth | Location | DDoS | Price | More details | Order now
export function planCard(plan, { featured = false, product } = {}) {
  const rows = [
    ['cpu', 'CPU', plan.cpu],
    ['memory', 'RAM', plan.ram],
    ['drive', 'Storage', plan.storage],
    ['globe', 'Bandwidth', plan.bandwidth],
    ['location', 'Location', plan.location],
    ['shield', 'DDoS', plan.ddos],
  ];
  const soon = plan.price == null;
  const id = `pd-${plan.id}`;
  return `
<article class="plan ${featured ? 'featured' : ''}" data-reveal data-spot>
  ${plan.badge ? `<p class="plan-flag">${plan.badge}</p>` : ''}
  <header class="plan-head">
    <h3>${plan.name}</h3>
    ${product ? `<p class="plan-sub">${product}</p>` : ''}
  </header>
  <div class="plan-price">${money(plan.price, { usd: plan.usd })}</div>
  <p class="plan-note">${soon ? 'Final pricing announced soon' : 'Billed monthly · No setup fee'}</p>
  ${soon
    ? `<a class="btn ${featured ? 'btn-white' : 'btn-outline'} btn-block" href="/support/?topic=${encodeURIComponent(plan.name)}">Contact sales</a>`
    : `<a class="btn ${featured ? 'btn-white' : 'btn-primary'} btn-block" href="${orderUrl(plan)}">Order now</a>`}
  <ul class="specs">
    ${rows.map(([ic, k, v]) => `<li>${icon(ic)}<span class="k">${k}</span><span class="v">${esc(v)}</span></li>`).join('')}
  </ul>
  ${plan.extras?.length ? `
  <button class="more" type="button" aria-expanded="false" aria-controls="${id}" data-more>More details ${icon('chevron')}</button>
  <div class="more-panel" id="${id}"><div>${checklist(plan.extras, 'checks-sm')}</div></div>` : ''}
</article>`;
}

export const planGrid = (plans, product) => `
<div class="plans plans-${plans.length}">
  ${plans.map((p) => planCard(p, { featured: !!p.badge, product })).join('')}
</div>`;

export const everyPlan = (items) => `
<div class="every" data-reveal>
  <p class="every-title">Every plan has <u>everything you need</u> and more</p>
  ${checklist(items, 'checks-3')}
</div>`;

// ───────────────────────────── FAQ
export const faqList = (faqs) => `
<div class="faq">
  ${faqs.map(([q, a], i) => `
  <div class="faq-item" data-reveal>
    <button class="faq-q" type="button" aria-expanded="false" aria-controls="fa-${i}-${q.length}">${q}<span class="faq-ico">${icon('plus')}</span></button>
    <div class="faq-a" id="fa-${i}-${q.length}"><div><p>${a}</p></div></div>
  </div>`).join('')}
</div>`;

export const faqSchema = (faqs) => ({
  '@context': 'https://schema.org',
  '@type': 'FAQPage',
  mainEntity: faqs.map(([q, a]) => ({ '@type': 'Question', name: q, acceptedAnswer: { '@type': 'Answer', text: a } })),
});

export const breadcrumbSchema = (trail) => ({
  '@context': 'https://schema.org',
  '@type': 'BreadcrumbList',
  itemListElement: trail.map(([name, path], i) => ({ '@type': 'ListItem', position: i + 1, name, item: site.url + path })),
});

export const productSchema = (p) => ({
  '@context': 'https://schema.org',
  '@type': 'Product',
  name: `${site.name} ${p.name}`,
  description: p.description,
  brand: { '@type': 'Brand', name: site.name },
  ...(p.from != null ? {
    offers: {
      '@type': 'AggregateOffer',
      priceCurrency: 'GBP',
      lowPrice: Math.min(...(p.plans.length ? p.plans : p.servers).map((x) => x.price)),
      highPrice: Math.max(...(p.plans.length ? p.plans : p.servers).map((x) => x.price)),
      offerCount: (p.plans.length || p.servers.length),
      url: `${site.url}/${p.slug}/`,
    },
  } : {}),
});

// ───────────────────────────── animated scenes ("videos")
const winChrome = (title, extra = '') => `<div class="win-bar"><i></i><i></i><i></i><span>${title}</span>${extra}</div>`;

const spark = (id, label, val) => `
<div class="gauge">
  <div class="gauge-top"><span>${label}</span><b data-spark-val="${id}">${val}%</b></div>
  <svg viewBox="0 0 120 36" preserveAspectRatio="none" class="spark"><path data-spark="${id}" d="M0 30 L120 30"/><path class="spark-fill" data-spark-fill="${id}" d="M0 36 L0 30 L120 30 L120 36 Z"/></svg>
</div>`;

export const scenes = {
  dashboard: () => `
<div class="scene scene-dash" aria-hidden="true">
  <div class="dash-card float-a">
    <div class="dash-srv"><span class="srv-ico">${icon('server')}</span><div><b>srv-482.rmdhost.net</b><small>Ubuntu 24.04 · Plus Berg</small></div><span class="live">Running</span></div>
  </div>
  <div class="dash-apps float-b">${['Docker', 'Nginx', 'WordPress', 'n8n', 'MySQL', 'Node'].map((a) => `<span>${a}</span>`).join('')}<span class="more-dots">•••</span></div>
  <div class="dash-gauges float-c">${spark('cpu', 'CPU usage', 42)}${spark('ram', 'Memory usage', 31)}</div>
  <div class="dash-chip float-d">${icon('shield')} DDoS filtered <b data-counter-live="1284">1,284</b></div>
</div>`,

  terminal: () => `
<div class="scene scene-term" aria-hidden="true">
  <div class="win">
    ${winChrome('root@rmdhost: ~')}
    <pre class="term" data-term='${JSON.stringify([
      '$ rmd deploy --plan ssd-medium --region fra',
      '› Allocating 6 vCores · 12 GB RAM · 100 GB NVMe',
      '› Installing Ubuntu 24.04 LTS ......... done',
      '› Applying DDoS protection profile ..... done',
      '› Configuring IPv4 + IPv6 /64 .......... done',
      '✓ Server online at 185.214.10.42 (38s)',
      '$ ssh root@185.214.10.42',
    ])}'></pre>
  </div>
  <div class="toast float-b">${icon('check')}<div><b>Deployment complete</b><small>Frankfurt · 38 seconds</small></div></div>
</div>`,

  windows: () => `
<div class="scene scene-win" aria-hidden="true">
  <div class="win rdp">
    ${winChrome('Remote Desktop – winberg-x8')}
    <div class="rdp-body">
      <div class="rdp-icons">${['This PC', 'MetaTrader', 'SQL Server', 'IIS'].map((n) => `<span><i></i>${n}</span>`).join('')}</div>
      <div class="rdp-app float-a"><div class="rdp-app-bar">MetaTrader – EURUSD</div><svg viewBox="0 0 200 70" class="candles">${Array.from({ length: 18 }, (_, i) => { const h = 10 + ((i * 37) % 30); const y = 15 + ((i * 53) % 28); return `<rect x="${6 + i * 11}" y="${y}" width="6" height="${h}" class="${i % 3 ? 'up' : 'down'}"/>`; }).join('')}</svg></div>
    </div>
    <div class="rdp-taskbar"><span class="start">${icon('windows')}</span><i></i><i></i><i></i><span class="clock" data-clock>12:00</span></div>
  </div>
</div>`,

  cloud: () => `
<div class="scene scene-cloud" aria-hidden="true">
  <svg viewBox="0 0 400 260" class="nodes">
    <g class="links">
      <path d="M200 60 L90 150"/><path d="M200 60 L310 150"/><path d="M90 150 L200 220"/><path d="M310 150 L200 220"/><path d="M200 60 L200 220"/>
    </g>
    ${[[200, 60, 'Controller'], [90, 150, 'vCloud 1'], [310, 150, 'vCloud 2'], [200, 220, 'Storage']].map(([x, y, l], i) => `
      <g class="node n${i}" transform="translate(${x} ${y})"><rect x="-46" y="-18" width="92" height="36" rx="10"/><text y="5">${l}</text></g>`).join('')}
  </svg>
  <div class="toast float-b">${icon('backup')}<div><b>Snapshot created</b><small>vCloud 2 · 2 min ago</small></div></div>
</div>`,

  storage: () => `
<div class="scene scene-store" aria-hidden="true">
  <div class="win">
    ${winChrome('cloud.yourcompany.com')}
    <div class="files">
      ${[['Projects', '12.4 GB'], ['Invoices 2026', '840 MB'], ['Photos', '96.1 GB'], ['Client portal', '3.2 GB']].map(([n, s], i) => `
      <div class="file"><span class="f-ico">${icon('folder')}</span><b>${n}</b><small>${s}</small><span class="bar"><i style="--d:${i * 0.6}s"></i></span></div>`).join('')}
    </div>
  </div>
  <div class="toast float-b">${icon('lock')}<div><b>End-to-end encrypted</b><small>4 folders synced</small></div></div>
</div>`,

  ai: () => `
<div class="scene scene-ai" aria-hidden="true">
  <div class="win">
    ${winChrome('ai.your-vps.net – Open WebUI')}
    <div class="chat">
      <p class="msg me">Summarise last week's support tickets</p>
      <p class="msg bot"><span data-stream="Found 128 tickets. 41% were billing questions, 33% DNS changes and 26% performance. Average first response: 6 minutes. Suggested action: publish a DNS guide.">…</span></p>
    </div>
    <div class="chat-input"><span>Ask your private model…</span><b>${icon('send')}</b></div>
  </div>
  <div class="toast float-b">${icon('spark')}<div><b>llama3.1:8b</b><small>Running locally · 0 data shared</small></div></div>
</div>`,

  mac: () => `
<div class="scene scene-mac" aria-hidden="true">
  <div class="win">
    ${winChrome('Xcode — Build MyApp (iOS)')}
    <div class="build">
      ${['Compile Swift sources', 'Link MyApp', 'Copy resources', 'Code sign', 'Archive'].map((s, i) => `<div class="step" style="--d:${i * 0.9}s"><span class="tick">${icon('check')}</span>${s}<span class="bar"><i></i></span></div>`).join('')}
    </div>
  </div>
  <div class="toast float-b">${icon('apple')}<div><b>Build succeeded</b><small>Apple silicon · 2m 14s</small></div></div>
</div>`,

  rack: () => `
<div class="scene scene-rack" aria-hidden="true">
  <div class="rack">
    ${Array.from({ length: 7 }, (_, i) => `<div class="unit${i === 2 ? ' sel' : ''}"><span class="leds"><i style="--d:${(i * 0.37) % 1.4}s"></i><i style="--d:${(i * 0.71) % 1.2}s"></i></span><span class="vents"></span><span class="lbl">${['Mercury', 'Venus', 'Uranus', 'Mars', 'Neptune', 'Saturn', 'Jupiter'][i]}</span></div>`).join('')}
  </div>
  <div class="dash-card float-a rack-card"><div class="dash-srv"><span class="srv-ico">${icon('rack')}</span><div><b>Uranus · E-2146G</b><small>6C/12T · 32 GB ECC · RAID 1</small></div><span class="live">Online</span></div></div>
  <div class="dash-gauges float-c rack-g">${spark('net', 'Network 1 Gbit/s', 64)}</div>
</div>`,

  game: () => `
<div class="scene scene-game" aria-hidden="true">
  <div class="win">
    ${winChrome('Game panel – survival-01')}
    <div class="game-body">
      <div class="g-stat"><small>Players</small><b data-counter-live="87" data-max="120">87</b><span>/ 120</span></div>
      <div class="g-stat"><small>Tick rate</small><b>20.0</b><span>TPS</span></div>
      <div class="g-stat"><small>Ping</small><b>18</b><span>ms</span></div>
      ${spark('ping', 'Server load', 38)}
    </div>
  </div>
  <div class="toast float-b">${icon('shield')}<div><b>Attack mitigated</b><small>UDP flood · 41 Gbps blocked</small></div></div>
</div>`,

  shield: () => `
<div class="scene scene-shield" aria-hidden="true">
  <div class="rings"><i></i><i></i><i></i></div>
  <div class="shield-core">${icon('shield')}</div>
  ${Array.from({ length: 8 }, (_, i) => `<span class="packet" style="--a:${i * 45}deg;--d:${i * 0.35}s"></span>`).join('')}
  <div class="toast float-b">${icon('check')}<div><b>Clean traffic only</b><small>Edge filtering active</small></div></div>
</div>`,
};

// ───────────────────────────── world map
export const lonlat = (lon, lat) => [
  ((lon + 180) / 360) * MAP.w,
  ((MAP.latTop - lat) / (MAP.latTop - MAP.latBottom)) * MAP.h,
];

// Label text and offsets – European pins sit close together, so the two UK
// sites share one label.
const LABELS = {
  Leeds: ['Derby & Leeds', -12, -10, 'end'],
  Amsterdam: ['Amsterdam', -6, 26, 'end'],
  Frankfurt: ['Frankfurt', 10, 22, 'start'],
  Riga: ['Riga', 10, -8, 'start'],
  'New York': ['New York', -12, -10, 'end'],
};

export function worldMap({ labels = true, cls = '' } = {}) {
  const pins = locations.map((l, i) => {
    const [x, y] = lonlat(l.lon, l.lat);
    const lb = LABELS[l.city];
    return `<g class="pin" transform="translate(${x.toFixed(1)} ${y.toFixed(1)})" style="--d:${i * 0.4}s"><circle class="pulse" r="6"/><circle class="dot" r="4.5"/>${labels && lb ? `<text x="${lb[1]}" y="${lb[2]}" text-anchor="${lb[3]}">${lb[0]}</text>` : ''}</g>`;
  }).join('');
  return `<svg class="worldmap ${cls}" viewBox="0 0 ${MAP.w} ${MAP.h}" role="img" aria-label="Map of RMDHost data centre locations"><path class="land" d="${WORLD_PATH}"/>${pins}</svg>`;
}

export function locationsBlock() {
  return `
<div class="locs" data-reveal>
  <div class="locs-copy">
    <p class="loc-reco"><span>Recommended server location:</span><b data-reco-loc>London, United Kingdom</b><small>· Best latency <span data-reco-ms>8</span> ms</small></p>
    <h3 class="h3">Local deployment. Global reach.</h3>
    <p class="muted">Choose a server location close to your audience for faster load times. We operate data centres in the UK, mainland Europe and North America, connected with Cisco 10 Gb/s fibre and direct LINX peering.</p>
    <a class="btn btn-white" href="/data-centres/">View all locations</a>
  </div>
  <div class="locs-map">${worldMap()}</div>
</div>`;
}

// ───────────────────────────── generic page hero (dark)
export function pageHero({ eyebrow, h1, lede, actions = '', visual = '', crumbs = [], small = false }) {
  return `
<section class="hero hero-page ${small ? 'hero-sm' : ''} dark">
  <div class="glow" aria-hidden="true"><i></i><i></i><i></i></div>
  <div class="grid-bg" aria-hidden="true"></div>
  <div class="container ${visual ? 'hero-split' : 'hero-center'}">
    <div class="hero-copy">
      ${crumbs.length ? `<nav class="crumbs" aria-label="Breadcrumb">${crumbs.map(([n, h], i) => i < crumbs.length - 1 ? `<a href="${h}">${n}</a><span>/</span>` : `<span aria-current="page">${n}</span>`).join('')}</nav>` : ''}
      ${eyebrow ? `<p class="eyebrow eyebrow-hero" data-reveal>${eyebrow}</p>` : ''}
      <h1 class="h1" data-reveal>${h1}</h1>
      ${lede ? `<p class="lead" data-reveal>${lede}</p>` : ''}
      ${actions}
    </div>
    ${visual ? `<div class="hero-visual" data-reveal>${visual}</div>` : ''}
  </div>
</section>`;
}

export function featureGrid(features, photo) {
  return `<div class="bento">${features.map((f, i) => `
  <div class="bento-card ${i === 0 || (i === 5 && features.length === 6) ? 'wide' : ''}${i === 0 && photo ? ' has-photo' : ''}" data-reveal data-spot>
    ${i === 0 && photo ? `<span class="bento-photo">${img(photo, '')}</span>` : ''}
    <span class="b-ico">${icon(f.icon)}</span>
    <h3>${f.title}</h3>
    <p>${f.text}</p>
  </div>`).join('')}</div>`;
}

export function ctaBand({ title = 'Imagined it.<br>Now deploy it.', text = 'No setup fees on VPS plans. Free migration help from our engineers.', href = '/pricing/', cta = 'Get started' } = {}) {
  return `
<section class="cta-band dark">
  <div class="glow" aria-hidden="true"><i></i><i></i></div>
  <div class="container cta-grid">
    <div data-reveal>
      <h2 class="h-display">${title}</h2>
      <p class="lead">${text}</p>
      <a class="btn btn-white btn-lg" href="${href}">${cta}</a>
    </div>
    <div class="cta-visual" data-reveal>
      <div class="photo-frame">${img('business-owner', 'Small business owner smiling at a laptop after launching a website')}</div>
      <div class="cta-domain float-a">${icon('globe')} yourproject<b>.com</b></div>
      <div class="toast cta-toast float-b">${icon('check')}<div><b>Server online</b><small>Deployed in 38 seconds</small></div></div>
      <div class="cta-prompt float-c"><span>Deploy a VPS in London</span><b>${icon('arrow')}</b></div>
    </div>
  </div>
</section>`;
}

export const stars = (n) => `<span class="stars" aria-label="${n} out of 5 stars">${Array.from({ length: 5 }, (_, i) => `<i class="${i < n ? 'on' : ''}">★</i>`).join('')}</span>`;

import { site } from '../data/site.mjs';
import { bySlug, products } from '../data/products.mjs';
import { homeFaqs, reviews } from '../data/content.mjs';
import { icon } from '../lib/icons.mjs';
import {
  scenes, techVisual, planCard, sectionHead, faqList, faqSchema, locationsBlock, ctaBand, money, stars, checklist, img, vid,
} from '../lib/components.mjs';
import { esc } from '../lib/layout.mjs';

const tabs = [
  {
    id: 'deploy', label: 'Deploy', scene: 'terminal', icon: 'rocket',
    title: 'Provision in seconds, not tickets.',
    text: 'Pick a plan, OS image and data centre. Your KVM instance boots with root SSH access, IPv4 + IPv6 /64 and DDoS filtering already applied.',
    link: ['Deploy a VPS', '/vps/'],
  },
  {
    id: 'scale', label: 'Scale', scene: 'dashboard', icon: 'scale',
    title: 'Scale from 1 vCore to bare metal.',
    text: 'Resize vCPU, RAM and disk from the client area, attach extra IPs, or move to a dedicated Xeon box when load demands it – our engineers handle the migration.',
    link: ['Compare plans', '/pricing/'],
  },
  {
    id: 'protect', label: 'Protect', scene: 'shield', icon: 'shield',
    title: 'Filtered at the edge.',
    text: 'Volumetric and protocol attacks are scrubbed upstream before they reach your port. Game servers add UDP-aware mitigation for Minecraft, CS and ARMA.',
    link: ['How DDoS protection works', '/ddos-protection/'],
  },
  {
    id: 'manage', label: 'Manage', scene: 'windows', icon: 'panel',
    title: 'Root, RDP and out-of-band console.',
    text: 'SSH or Remote Desktop in, use the VNC console when the network is down, snapshot before risky changes and reinstall any of 25+ OS images.',
    link: ['Explore Windows VPS', '/windows-vps/'],
  },
];

const essentials = [
  { slug: 'vps', title: 'Linux VPS', text: 'KVM · full root · SSD + HDD · unmetered 100 Mbit/s.', visual: 'linux', chip: 'srv-482 · running' },
  { slug: 'windows-vps', title: 'Windows VPS', text: 'Ryzen vCPU · NVMe · RDP · free backups.', visual: 'windows', chip: 'rdp :3389 · connected' },
  { slug: 'dedicated-servers', title: 'Dedicated servers', text: 'Bare-metal Xeon & Core · RAID 1 · 1 Gbit/s.', visual: 'rack', chip: 'uranus · e-2146g · online' },
  { slug: 'owncloud-storage', title: 'Private cloud storage', text: 'OwnCloud · E2E encryption · up to 1.6 TB.', visual: 'storage', chip: 'sync · 2,481 objects' },
];

const xcards = [
  { title: 'Migrate', text: 'Tell us where your site lives today and our engineers move it to RMDHost for free.', q: 'Can you move my WordPress site from my old host?', a: 'Of course – send us read-only access and we’ll migrate it with zero downtime.' },
  { title: 'Secure', text: 'Get help hardening SSH, firewalls and updates on your new server.', q: 'How do I lock down SSH on my VPS?', a: 'Use key-only login and disable root. I’ve added a step-by-step guide to your ticket.' },
  { title: 'Fix', text: 'Share your error and get clear steps to sort it out – day or night.', q: 'My site is showing a 502 Bad Gateway. Can you help?', a: 'Let me check your Nginx and PHP-FPM logs – I’ll find what’s causing it.' },
  { title: 'Speed up', text: 'Describe your speed issue and get tuning tips for your stack.', q: 'Why is my WooCommerce store slow?', a: 'Your MySQL buffer is too small. Increasing it to 2 GB should halve load time.' },
  { title: 'Scale', text: 'Ask for an upgrade path that matches your growth and budget.', q: 'We’re expecting 10× traffic next month.', a: 'A Premium Berg VPS or the Uranus dedicated server will handle it – here’s a quote.' },
];

const carousel = products.map((p) => ({ href: `/${p.slug}/`, icon: p.icon, title: p.name, text: p.lede.split('. ')[0] + '.', tag: p.tag }));

const pricingTabs = [
  // Packages, specs, prices and order links mirror the DigitalBerg product pages.
  { id: 'p-vps', label: 'Linux VPS', product: bySlug.vps, pick: [0, 1, 2, 3] },
  { id: 'p-win', label: 'Windows VPS', product: bySlug['windows-vps'], pick: [0, 1, 2, 3] },
  { id: 'p-ssd', label: 'SSD VPS', product: bySlug['ssd-vps'], pick: [0, 1, 2, 3] },
  { id: 'p-ded', label: 'Dedicated', product: bySlug['dedicated-servers'], pick: [0, 1, 2, 3] },
];

const review = (r) => `
      <figure class="review" data-reveal>
        ${stars(r.rating)}
        <blockquote>“${r.text}”</blockquote>
        <figcaption><span class="av">${r.name[0]}</span><span><b>${r.name}</b><small>${r.role} · ${r.product}</small></span>${r.sample ? '<em class="pill pill-xs" title="Replace with a real customer review before launch">Sample</em>' : ''}</figcaption>
      </figure>`;

// Hostinger-style wall: four columns mixing videos, photos and reviews.
const tile = (t) => {
  if (t.review != null) return review(reviews[t.review]);
  const media = t.video ? vid(t.video, { cls: 'wall-media' }) : img(t.photo, t.alt, { cls: 'wall-media', sizes: '(max-width: 640px) 100vw, 25vw' });
  return `<a class="wall-tile ${t.tall ? 'tall' : ''}" href="${t.href}" data-reveal>${media}<span class="m-tag">${t.tag}</span>${t.video ? `<span class="play">${icon('play')}</span>` : ''}<span class="wall-cap">${t.cap}</span></a>`;
};
const wall = [
  [{ video: 'tile-gamer', tag: 'Game Servers', cap: 'Game<br>networks', href: '/game-servers/', tall: true }, { review: 0 }, { photo: 'typing-hands', alt: 'Developer typing on a laptop at night', tag: 'SSD VPS', cap: 'Developers', href: '/ssd-vps/' }],
  [{ review: 1 }, { photo: 'agency-team', alt: 'Creative agency team working together', tag: 'Linux VPS', cap: 'Web<br>agencies', href: '/vps/', tall: true }, { review: 2 }],
  [{ photo: 'founder', alt: 'Software startup founder in a bright office', tag: 'Dedicated', cap: 'SaaS<br>startups', href: '/dedicated-servers/' }, { review: 3 }, { video: 'tile-typing', tag: 'Cloud VPS', cap: 'Builders', href: '/cloud-vps/', tall: true }],
  [{ review: 4 }, { video: 'tile-founder', tag: 'AI VPS', cap: 'AI-first<br>teams', href: '/ai-vps/', tall: true }, { review: 5 }],
];

// DreamHost-style hero: eyebrow, headline, benefit text, offer, CTAs, stats,
// plus a translucent offer/review banner. All copy comes from homepage.json
// (or WordPress) – nothing here is hard-coded.
function hero(h) {
  const { hero: x, banner: b, stats } = h;
  const r = b?.rating || {};
  const hasScore = r.score != null && r.score !== '';
  const ratingStars = hasScore
    ? `<span class="hb-stars" style="--pct:${Math.max(0, Math.min(100, (r.score / (r.scale || 5)) * 100))}%" aria-hidden="true"><i>★★★★★</i><i>★★★★★</i></span>`
    : `<span class="hb-badge" aria-hidden="true">${icon('star')}</span>`; // no score entered: neutral icon, never implied stars
  return `
<section class="hero hero-home hero-promo dark">
  <div class="hero-arcs" aria-hidden="true"><i></i><i></i><i></i><i></i></div>
  <div class="grid-bg" aria-hidden="true"></div>
  <div class="container hero-center">
    ${x.eyebrow ? `<p class="hero-eyebrow" data-reveal>${esc(x.eyebrow)}</p>` : ''}
    <h1 class="h-display hero-title" data-reveal>${esc(x.headline)}</h1>
    ${x.text ? `<p class="lead" data-reveal>${esc(x.text)}</p>` : ''}
    ${x.offer?.price != null ? `
    <p class="hero-offer" data-reveal><span class="ho-label">${esc(x.offer.label)}</span> ${money(Number(x.offer.price), { per: esc(x.offer.suffix || '') })}${x.offer.note ? `<span class="ho-note">${esc(x.offer.note)}</span>` : ''}</p>` : ''}
    <div class="hero-cta" data-reveal>
      ${x.primaryCta?.label ? `<a class="btn btn-white btn-lg" href="${esc(x.primaryCta.href)}">${esc(x.primaryCta.label)}</a>` : ''}
      ${x.secondaryCta?.label ? `<a class="btn btn-ghost-light btn-lg" href="${esc(x.secondaryCta.href)}">${esc(x.secondaryCta.label)}</a>` : ''}
    </div>
    ${stats?.length ? `<ul class="hero-stats" data-reveal>${stats.map((st) => `<li><b>${esc(st.value)}</b><span>${esc(st.label)}</span></li>`).join('')}</ul>` : ''}
  </div>
  ${b?.enabled ? `
  <div class="hero-banner" data-reveal>
    <div class="container hb-inner">
      <div class="hb-offer">
        ${b.offerTag ? `<span class="hb-tag">${esc(b.offerTag)}</span>` : ''}
        <p>${esc(b.offerText)}</p>
        ${b.offerCta?.label ? `<a class="hb-link" href="${esc(b.offerCta.href)}">${esc(b.offerCta.label)} ${icon('arrow')}</a>` : ''}
      </div>
      ${r.url ? `
      <a class="hb-rating" href="${esc(r.url)}" target="_blank" rel="noopener">
        ${ratingStars}
        <span class="hb-text">
          ${hasScore ? `<b>${esc(String(r.score))}</b><span class="hb-scale">/ ${esc(String(r.scale || 5))}</span>` : ''}
          <span class="hb-label">${esc(r.label)}${r.count ? ` · ${esc(String(r.count))} reviews` : ''}</span>
          ${r.note ? `<small>${esc(r.note)}</small>` : ''}
        </span>
        <span class="hb-link">${esc(r.linkText || 'Read reviews')} ${icon('arrowUpRight')}</span>
      </a>` : ''}
    </div>
  </div>` : ''}
</section>`;
}

export default {
  path: '/',
  title: 'RMDHost – Fast VPS, Windows VPS & Dedicated Server Hosting',
  description: 'High-performance Linux VPS from £8.99/mo, Windows VPS, dedicated and game servers in six UK, EU and US data centres. DDoS protection, no setup fees and 24/7 expert support.',
  schema: [
    faqSchema(homeFaqs),
    { '@context': 'https://schema.org', '@type': 'WebSite', name: site.name, url: site.url },
  ],
  body: ({ home }) => `
<!-- HERO -->
${hero(home)}

<!-- PROMO GRID -->
<section class="section promo-sec">
  <div class="container promo-grid">
    <a class="promo-big" href="/pricing/" data-reveal data-spot>
      ${img('datacentre-building', '', { cls: 'promo-bg' })}
      <span class="stripes" aria-hidden="true"><i></i><i></i><i></i></span>
      <span class="pill pill-glass">Pricing</span>
      <span class="promo-big-body">
        <strong>Plans and prices</strong>
        <span>KVM Linux VPS from ${money(8.99)}, Ryzen Windows VPS, OpenStack NVMe VPS and bare-metal Xeon servers – monthly billing, no setup fee on Linux &amp; SSD VPS.</span>
        <span class="btn btn-white">Explore all offers</span>
      </span>
    </a>
    <a class="promo-small" href="/aws-lightsail-vps/" data-reveal data-spot>
      <span class="pill">New</span>${icon('arrowUpRight', 'corner')}
      <strong>AWS Lightsail VPS</strong>
      <span>Fixed-price cloud bundles: 2 vCPUs, 1–8 GB RAM and 2–5 TB transfer from ${money(11.99)}.</span>
    </a>
    <a class="promo-small" href="/instant-dedicated-servers-usa/" data-reveal data-spot>
      <span class="pill">Trending</span>${icon('arrowUpRight', 'corner')}
      <strong>Instant dedicated servers</strong>
      <span>Bare metal in the USA, online in minutes from ${money(12.99)}.</span>
      <span class="promo-ico float-a">${icon('rocket')}</span>
    </a>
  </div>
</section>

<!-- IDEA PROMPT -->
<section class="idea">
  <div class="idea-bg" aria-hidden="true"></div>
  <div class="container idea-inner">
    <span class="idea-mark" data-reveal>${icon('spark')}</span>
    <h2 class="h-display" data-reveal>Tell us your project.<br><span class="grad-text">We’ll pick the perfect server.</span></h2>
    <form class="idea-form" data-idea data-reveal>
      <label class="sr-only" for="idea-in">Describe what you want to host</label>
      <input id="idea-in" type="text" autocomplete="off" data-typewriter='${JSON.stringify(['A Minecraft server for 100 players', 'WordPress sites for my agency', 'MetaTrader bots running 24/7', 'A private ChatGPT for my team', 'A Postgres database for my SaaS', 'iOS builds for my app'])}'>
      <button type="submit" class="idea-go" aria-label="Get a recommendation">${icon('arrow')}</button>
    </form>
    <p class="idea-note" data-reveal>Get an instant server recommendation. Free, no signup.</p>
    <div class="idea-result" data-idea-result hidden aria-live="polite"></div>
  </div>
</section>

<!-- TOOLS TABS -->
<section class="section">
  <div class="container">
    ${sectionHead({ title: 'Servers for every online project', text: 'Get more power and control over what you run online, without taking on more technical complexity.' })}
    <div class="tabs" data-tabs data-reveal>
      <div class="tablist" role="tablist" aria-label="What you can do">
        <span class="tab-ind" aria-hidden="true"></span>
        ${tabs.map((t, i) => `<button role="tab" id="tab-${t.id}" aria-controls="panel-${t.id}" aria-selected="${i === 0}" tabindex="${i === 0 ? 0 : -1}">${t.label}</button>`).join('')}
      </div>
      ${tabs.map((t, i) => `
      <div class="tabpanel tool-panel" role="tabpanel" id="panel-${t.id}" aria-labelledby="tab-${t.id}" ${i ? 'hidden' : ''}>
        <div class="tool-visual">${scenes[t.scene]()}</div>
        <div class="tool-copy">
          <span class="tool-ico">${icon(t.icon)}</span>
          <h3 class="h3">${t.title}</h3>
          <p class="muted">${t.text}</p>
          <a class="row-link" href="${t.link[1]}">${t.link[0]}${icon('arrow')}</a>
          <div class="avatars"><span>R</span><span>M</span><span>D</span><span>H</span><b>10K+</b><small>clients choose<br>${site.name}</small></div>
        </div>
      </div>`).join('')}
    </div>
    <h3 class="sub-h" data-reveal>Want more hands-on control?</h3>
    <div class="duo">
      <a class="soft-card" href="/dedicated-servers/" data-reveal data-spot>${icon('rack')}${icon('arrowUpRight', 'corner')}<strong>Dedicated servers</strong><span>Bare-metal Xeon, fully under your control.</span></a>
      <a class="soft-card" href="/game-servers/" data-reveal data-spot>${icon('game')}${icon('arrowUpRight', 'corner')}<strong>Game servers</strong><span>Anti-DDoS Game for Minecraft, CS2, ARMA and more.</span></a>
    </div>
  </div>
</section>

<!-- ESSENTIALS -->
<section class="section pt-0">
  <div class="container">
    ${sectionHead({ title: 'Set up the essentials to go online' })}
    <div class="ess-grid">
      ${essentials.map((e) => `
      <a class="ess" href="/${e.slug}/" data-reveal>
        <span class="ess-photo ess-tech">${techVisual(e.visual)}<span class="ess-chip"><i class="led"></i>${e.chip}</span></span>
        <strong>${e.title}</strong>
        <span>${e.text}</span>
        <span class="ess-price">From ${money(bySlug[e.slug].from)}</span>
      </a>`).join('')}
    </div>
  </div>
</section>

<!-- ALTERNATING -->
<section class="section alt-sec">
  <div class="container">
    <div class="alt" data-reveal>
      <div class="alt-copy">
        <h2 class="h3">Grow without limits and keep more of what you earn</h2>
        <p class="muted">Unlimited bandwidth on VPS and dedicated plans, no setup fees and transparent monthly pricing in GBP or USD.</p>
        <a class="row-link" href="/pricing/">See all pricing${icon('arrow')}</a>
      </div>
      <div class="alt-visual">${scenes.rack()}</div>
    </div>
    <div class="alt alt-rev" data-reveal>
      <div class="alt-visual">${scenes.ai()}</div>
      <div class="alt-copy">
        <h2 class="h3">Bring AI in-house with a private AI VPS</h2>
        <p class="muted">Run open-source models, AI agents and n8n automations on your own server – your prompts and data never leave it.</p>
        <a class="row-link" href="/ai-vps/">Explore AI VPS${icon('arrow')}</a>
      </div>
    </div>
  </div>
</section>

<!-- SUPPORT (dark expanding cards) -->
<section class="section dark support-sec">
  <div class="glow" aria-hidden="true"><i></i><i></i></div>
  <div class="container">
    <div class="sup-head" data-reveal>
      <div>
        <p class="award">${icon('star')} <span>24/7/365<br>Expert support</span></p>
        <h2 class="h2">Real engineers. Real answers.<br>Every hour of every day.</h2>
      </div>
      <div>
        <p class="muted">Migrate, fix, secure and scale your servers with help from our in-house engineers – no chatbots, no scripts.</p>
        <a class="chip-btn chip-outline" href="/support/">${icon('chat')} Open a ticket</a>
      </div>
    </div>
    <div class="xcards" data-xcards data-reveal>
      ${xcards.map((c, i) => `
      <button class="xcard ${i === 2 ? 'active' : ''}" type="button" aria-expanded="${i === 2}">
        <span class="x-title">${c.title}</span>
        <span class="x-body">
          <span class="x-text">${c.text}</span>
          <span class="x-chat"><span class="bubble me">${icon('user')}${c.q}</span><span class="bubble them">${icon('headset')}${c.a}</span></span>
        </span>
      </button>`).join('')}
    </div>
    <div class="sup-more" data-reveal>
      <div>
        <h3 class="h3">Same team. More ways to grow.</h3>
        <p class="muted">Every plan includes 24/7 support. Need more? Add fully managed services, custom hardware builds and dedicated account management.</p>
        <a class="row-link row-link-light" href="/support/">Learn more${icon('arrow')}</a>
      </div>
      <div class="stack-cards">
        <span class="stack-photo">${img('support-engineer', 'RMDHost support engineer wearing a headset')}</span>
        <span class="sc sc1">${icon('wrench')} Install cPanel on my server</span>
        <span class="sc sc2">${icon('backup')} Restore last night’s snapshot</span>
        <span class="sc sc3">${icon('globe')} Add 4 IPs in Frankfurt
          <span class="sc-bar">${['Tickets', 'Chat', 'Status', 'Docs', 'Billing'].map((x, i) => `<i class="${i === 1 ? 'on' : ''}">${x}</i>`).join('')}</span>
        </span>
      </div>
    </div>
  </div>
</section>

<!-- PRICING -->
<section class="section section-soft" id="pricing">
  <div class="container">
    ${sectionHead({ title: 'Choose the plan that<br>matches what you need', text: 'Compare vCPU, RAM, storage, bandwidth and network protection side by side – every package links straight to checkout.' })}
    <ul class="assure" data-reveal><li>${icon('shield')} Anti-DDoS on VPS</li><li>${icon('refresh')} Monthly billing</li><li>${icon('headset')} 24/7 support</li></ul>
    <div class="tabs" data-tabs>
      <div class="tablist tablist-center" role="tablist" aria-label="Plan type" data-reveal>
        <span class="tab-ind" aria-hidden="true"></span>
        ${pricingTabs.map((t, i) => `<button role="tab" id="tab-${t.id}" aria-controls="panel-${t.id}" aria-selected="${i === 0}" tabindex="${i === 0 ? 0 : -1}">${t.label}</button>`).join('')}
      </div>
      ${pricingTabs.map((t, i) => `
      <div class="tabpanel" role="tabpanel" id="panel-${t.id}" aria-labelledby="tab-${t.id}" ${i ? 'hidden' : ''}>
        <div class="plans plans-4">
          ${t.pick.map((n) => planCard(t.product.plans[n], { featured: !!t.product.plans[n].badge, product: t.product.name })).join('')}
        </div>
        <p class="center mt"><a class="row-link inline" href="/${t.product.slug}/">View all ${t.product.name} plans${icon('arrowUpRight')}</a></p>
      </div>`).join('')}
    </div>
    <p class="fine">Prices exclude VAT. All plans are billed monthly. Unlimited bandwidth is subject to our Acceptable Use Policy.</p>
  </div>
</section>

<!-- AUTOMATION (dark) -->
<section class="section dark auto-sec">
  <div class="glow glow-bottom" aria-hidden="true"><i></i><i></i></div>
  <div class="container">
    ${sectionHead({ title: 'Put your servers to work', text: 'Launch AI tools, dev stacks and popular apps in a few clicks – then let them run in the background, around the clock.' })}
    <div class="auto" data-reveal>
      <div class="flow" aria-hidden="true">
        <div class="flow-grid"></div>
        <span class="flow-node n-a">${icon('refresh')}</span>
        <span class="flow-node n-b">${icon('globe')}</span>
        <span class="flow-node n-c">${icon('server')}</span>
        <span class="flow-node n-ai">${icon('spark')} AI Agent</span>
        <span class="flow-node n-d">${icon('mail')}</span>
        <span class="flow-node n-e">${icon('chat')}</span>
        <svg class="flow-lines" viewBox="0 0 400 300" preserveAspectRatio="none"><path d="M120 80 H190 M250 80 H300 M200 110 V170 M200 210 V240 H140 M200 240 H270"/></svg>
        <span class="flow-cursor">${icon('arrow')}</span>
      </div>
      <div class="auto-copy">
        <h3 class="h3">One-click apps</h3>
        <p class="muted">Connect your tools, hand repetitive tasks to AI and keep things running – even when you’re not.</p>
        <a class="row-link row-link-light" href="/ai-vps/">Self-hosted n8n${icon('arrow')}</a>
        <a class="row-link row-link-light" href="/ai-vps/">Ollama + Open WebUI${icon('arrow')}</a>
        <a class="row-link row-link-light" href="/tutorials/">Docker &amp; Compose${icon('arrow')}</a>
      </div>
    </div>
    <div class="car-head" data-reveal>
      <h2 class="h3">More power when you need it</h2>
      <div class="car-arrows"><button class="icon-btn" type="button" data-car-prev aria-label="Previous">${icon('chevronLeft')}</button><button class="icon-btn" type="button" data-car-next aria-label="Next">${icon('chevronRight')}</button></div>
    </div>
    <div class="carousel" data-carousel>
      ${carousel.map((c) => `<a class="car-card" href="${c.href}" data-spot>${icon(c.icon)}${icon('arrowUpRight', 'corner')}<strong>${c.title}${c.tag ? ` <em class="pill pill-xs pill-invert">${c.tag}</em>` : ''}</strong><span>${c.text}</span></a>`).join('')}
    </div>
  </div>
</section>

<!-- LOCATIONS -->
<section class="section">
  <div class="container">
    <div class="dark-panel dark">${locationsBlock()}</div>
    <div class="stats" data-reveal>
      ${site.stats.map((s) => `<div class="stat"><b><span data-count="${s.value}" data-decimals="${s.decimals || 0}">0</span>${s.suffix}</b><span>${s.label}</span></div>`).join('')}
    </div>
  </div>
</section>

<!-- REVIEWS -->
<section class="section pt-0">
  <div class="container">
    ${sectionHead({ title: 'Trusted by teams that can’t<br>afford downtime', text: 'From agencies and traders to game networks and SaaS startups.' })}
    <p class="center" data-reveal><a class="btn btn-primary" href="/references/">See customer stories</a></p>
    <div class="wall">
      ${wall.map((col, ci) => `<div class="wall-col${ci % 2 ? ' off' : ''}">${col.map((t) => tile(t)).join('')}</div>`).join('')}
    </div>
    <div class="rating-bar" data-reveal>
      <span>${stars(5)}</span>
      <p>Read independent reviews of our infrastructure on <a href="https://hostadvice.com/hosting-company/digitalberg-reviews/" rel="noopener" target="_blank">HostAdvice</a>.</p>
    </div>
  </div>
</section>

<!-- FAQ -->
<section class="section section-soft">
  <div class="container narrow">
    ${sectionHead({ title: `${site.name} hosting FAQs` })}
    ${faqList(homeFaqs)}
  </div>
</section>

${ctaBand()}
`,
};

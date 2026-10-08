import { site } from '../data/site.mjs';
import { products } from '../data/products.mjs';
import { homeFaqs, locations, posts, kb, tutorials, statusServices, references, reviews } from '../data/content.mjs';
import { icon } from '../lib/icons.mjs';
import {
  pageHero, sectionHead, planCard, faqList, faqSchema, breadcrumbSchema, worldMap, scenes,
  featureGrid, ctaBand, money, checklist, stars,
} from '../lib/components.mjs';

const crumbs = (name, path) => [['Home', '/'], [name, path]];
const bc = (name, path) => breadcrumbSchema(crumbs(name, path));

const prose = (html) => `<section class="section"><div class="container narrow prose">${html}</div></section>`;

// ───────────────────────────── Pricing
const pricing = {
  path: '/pricing/',
  title: 'Pricing – VPS, Windows VPS & Dedicated Server Plans | RMDHost',
  description: 'Compare every RMDHost plan: Linux VPS from £8.99/mo, Windows VPS from £7.99/mo, SSD VPS, Cloud VPS, OwnCloud, dedicated and game servers.',
  schema: [bc('Pricing', '/pricing/')],
  body: () => `
${pageHero({ eyebrow: 'Plans and prices', h1: 'Simple, transparent pricing', lede: 'Monthly billing, no setup fees on VPS, DDoS protection on every server. Switch between GBP and USD at any time.', crumbs: crumbs('Pricing', '/pricing/') })}
<section class="section">
  <div class="container">
    <div class="tabs" data-tabs>
      <div class="tablist tablist-center tablist-wrap" role="tablist" aria-label="Product" data-reveal>
        <span class="tab-ind" aria-hidden="true"></span>
        ${products.filter((p) => p.plans.length).map((p, i) => `<button role="tab" id="pt-${p.slug}" aria-controls="pp-${p.slug}" aria-selected="${!i}" tabindex="${i ? -1 : 0}">${p.name}</button>`).join('')}
      </div>
      ${products.filter((p) => p.plans.length).map((p, i) => `
      <div class="tabpanel" role="tabpanel" id="pp-${p.slug}" aria-labelledby="pt-${p.slug}" ${i ? 'hidden' : ''}>
        <div class="plans plans-${p.plans.length}">${p.plans.map((pl) => planCard(pl, { featured: !!pl.badge, product: p.name })).join('')}</div>
        <p class="center mt"><a class="row-link inline" href="/${p.slug}/">${p.name} details${icon('arrowUpRight')}</a></p>
      </div>`).join('')}
    </div>
    <div class="callout" data-reveal>
      <div><h2 class="h3">Need instant bare metal in the USA?</h2><p class="muted">Pre-configured dedicated servers online in minutes from ${money(12.99)}.</p></div>
      <a class="btn btn-primary" href="/instant-dedicated-servers-usa/">View instant servers</a>
    </div>
    <p class="fine">Prices exclude VAT and are billed monthly.</p>
  </div>
</section>
${ctaBand()}`,
};

// ───────────────────────────── Data centres
const dataCentres = {
  path: '/data-centres/',
  title: 'Data Centres & Server Locations – UK, EU & USA | RMDHost',
  description: 'RMDHost data centres in Derby, Leeds, Amsterdam, Frankfurt, Riga and New York with Cisco 10 Gb/s fibre, LINX peering, N+1 power and 24/7 security.',
  schema: [bc('Data centres', '/data-centres/')],
  body: () => `
${pageHero({ eyebrow: 'Global network', h1: 'Data centres around the world', lede: 'Deploy closer to your users. Six locations across the UK, Europe and North America with Cisco 10 Gb/s fibre and direct LINX connectivity.', crumbs: crumbs('Data centres', '/data-centres/') })}
<section class="section">
  <div class="container">
    <div class="map-big" data-reveal>${worldMap()}</div>
    <div class="loc-grid">
      ${locations.map((l) => `
      <article class="loc-card" data-reveal data-spot>
        <p class="loc-top"><span class="flag">${l.code}</span><span class="status-dot">Operational</span></p>
        <h2 class="h4">${l.city}</h2>
        <p class="muted">${l.country} · ${l.region}</p>
        <ul class="tags">${l.services.map((s) => `<li>${s}</li>`).join('')}</ul>
      </article>`).join('')}
    </div>
  </div>
</section>
<section class="section section-soft">
  <div class="container">
    ${sectionHead({ title: 'Built for uptime' })}
    ${featureGrid([
      { icon: 'lock', title: '24/7 physical security', text: 'Biometric access control, CCTV monitoring and on-site security teams at every facility.' },
      { icon: 'bolt', title: 'Redundant power', text: 'Dual power feeds, N+1 UPS battery backup and diesel generators.' },
      { icon: 'leaf', title: 'Efficient cooling', text: 'N+1 climate control with under-floor air distribution.' },
      { icon: 'globe', title: 'Carrier-grade network', text: 'Cisco 10 Gb/s fibre backbone with multi-path connectivity and LINX peering.' },
      { icon: 'shield', title: 'DDoS mitigation', text: 'Edge filtering protects every server in every location.' },
      { icon: 'headset', title: 'Remote hands', text: 'Engineers on site to replace hardware and assist 24/7.' },
    ])}
  </div>
</section>
${ctaBand({ title: 'Pick a location.<br>Deploy in seconds.' })}`,
};

// ───────────────────────────── DDoS
const ddos = {
  path: '/ddos-protection/',
  title: 'DDoS Protection – Included on Every Server | RMDHost',
  description: 'Always-on DDoS protection with edge filtering for every RMDHost VPS, dedicated and game server. Game-aware mitigation for UDP-based games.',
  schema: [bc('DDoS protection', '/ddos-protection/'), faqSchema([
    ['Is DDoS protection free?', 'Yes. Network-level DDoS protection is included with every VPS, dedicated and game server at no extra cost.'],
    ['Does it add latency?', 'No. Filtering happens inline at the network edge and clean traffic is forwarded without measurable added latency.'],
  ])],
  body: () => `
${pageHero({ eyebrow: 'Security', h1: 'DDoS protection that never sleeps', lede: 'Attacks are detected and filtered at the network edge, so only clean traffic reaches your server. Included on every plan.', visual: scenes.shield(), crumbs: crumbs('DDoS protection', '/ddos-protection/') })}
<section class="section">
  <div class="container">
    ${sectionHead({ title: 'How it works' })}
    <ol class="steps">
      ${[['Detect', 'Traffic is analysed continuously for volumetric and protocol anomalies.'], ['Filter', 'Malicious packets are dropped at the edge before they reach your network port.'], ['Deliver', 'Clean traffic is forwarded to your server with no change to your setup.'], ['Report', 'Our team monitors every mitigation and contacts you if action is needed.']].map(([t, d], i) => `<li data-reveal><span class="step-n">0${i + 1}</span><h3>${t}</h3><p class="muted">${d}</p></li>`).join('')}
    </ol>
  </div>
</section>
<section class="section section-soft">
  <div class="container">
    ${sectionHead({ title: 'Protection for every workload' })}
    ${featureGrid([
      { icon: 'server', title: 'VPS & Cloud', text: 'Always-on filtering for websites, APIs and applications.' },
      { icon: 'rack', title: 'Dedicated servers', text: 'High-capacity mitigation for bare-metal workloads.' },
      { icon: 'game', title: 'Anti-DDoS Game', text: 'Game-aware filtering for Minecraft, CS, ARMA, GTA, Team Fortress and TeamSpeak.' },
      { icon: 'pulse', title: 'Layer 3/4 attacks', text: 'UDP, SYN and amplification floods filtered automatically.' },
      { icon: 'clock', title: 'Instant reaction', text: 'Mitigation starts within seconds – no manual switching.' },
      { icon: 'check', title: 'No extra cost', text: 'Included in every price you see on our site.' },
    ])}
  </div>
</section>
${ctaBand({ title: 'Stay online.<br>Whatever happens.' })}`,
};

// ───────────────────────────── Network status
const status = {
  path: '/network-status/',
  title: 'Network & Server Status | RMDHost',
  description: 'Live status of RMDHost services, data centres and network. Check for maintenance and incidents.',
  schema: [bc('Network status', '/network-status/')],
  body: () => {
    const groups = [...new Set(statusServices.map((s) => s.group))];
    return `
${pageHero({ eyebrow: 'Network status', h1: 'All systems operational', lede: 'Live health of our platform, data centres and services. Subscribe to updates from the client area.', crumbs: crumbs('Network status', '/network-status/'), small: true })}
<section class="section">
  <div class="container narrow">
    <div class="status-banner" data-reveal><span class="status-dot big"></span><div><b>All systems operational</b><small>Last checked <span data-now>just now</span></small></div></div>
    ${groups.map((g) => `
    <div class="status-group" data-reveal>
      <h2 class="h4">${g}</h2>
      ${statusServices.filter((s) => s.group === g).map((s) => `
      <div class="status-row"><span>${s.name}</span><span class="uptime" aria-hidden="true">${Array.from({ length: 45 }, () => '<i></i>').join('')}</span><span class="status-dot">Operational</span></div>`).join('')}
    </div>`).join('')}
    <div class="status-group" data-reveal>
      <h2 class="h4">Scheduled maintenance</h2>
      <p class="muted">No maintenance is currently scheduled. Planned work is announced at least 72 hours in advance by email.</p>
    </div>
    <p class="fine">Connect this page to your monitoring provider for real-time data – see README.</p>
  </div>
</section>`;
  },
};

// ───────────────────────────── About
const about = {
  path: '/about/',
  title: 'About RMDHost – Hosting Built on Trust & Performance',
  description: 'RMDHost is an international hosting provider delivering VPS, Windows VPS and dedicated servers from data centres across the UK, Europe and North America.',
  schema: [bc('About us', '/about/')],
  body: () => `
${pageHero({ eyebrow: 'About us', h1: 'Infrastructure built on trust and performance', lede: 'We deliver VPS, Windows VPS and dedicated server solutions from state-of-the-art data centres across the UK, Europe and North America.', crumbs: crumbs('About us', '/about/') })}
<section class="section">
  <div class="container">
    <div class="stats stats-plain" data-reveal>
      ${site.stats.map((s) => `<div class="stat"><b><span data-count="${s.value}" data-decimals="${s.decimals || 0}">0</span>${s.suffix}</b><span>${s.label}</span></div>`).join('')}
    </div>
    <div class="three">
      ${[['Our mission', 'To deliver enterprise-grade hosting infrastructure that is fast, reliable and accessible to businesses of every size – without the enterprise price tag.'], ['Our vision', 'A world where any business can deploy global infrastructure in minutes, with the confidence that their applications are protected by world-class security and support.'], ['Our values', 'Transparency in pricing, excellence in support and a relentless commitment to performance. We treat your infrastructure like our own.']].map(([t, d]) => `<div class="soft-card static" data-reveal><strong>${t}</strong><span>${d}</span></div>`).join('')}
    </div>
  </div>
</section>
<section class="section section-soft">
  <div class="container">
    ${sectionHead({ title: 'Why teams choose RMDHost' })}
    ${featureGrid([
      { icon: 'bolt', title: 'NVMe & SSD everywhere', text: 'Enterprise drives delivering up to 640,000 IOPS.' },
      { icon: 'shield', title: 'Anti-DDoS Pro', text: 'Multi-layer filtering included on every plan.' },
      { icon: 'globe', title: 'Six locations', text: 'UK, EU and US data centres with LINX connectivity.' },
      { icon: 'headset', title: '24/7 engineers', text: 'Real people, real answers – no chatbots.' },
      { icon: 'backup', title: 'Backups included', text: 'Free backups on Windows VPS, snapshots on Linux.' },
      { icon: 'check', title: '99.99% uptime SLA', text: 'Redundant power, cooling and network paths.' },
    ])}
  </div>
</section>
${ctaBand()}`,
};

// ───────────────────────────── References
const refs = {
  path: '/references/',
  title: 'Customer References & Case Studies | RMDHost',
  description: 'See how agencies, traders, game networks and SaaS teams run their infrastructure on RMDHost VPS and dedicated servers.',
  schema: [bc('References', '/references/')],
  body: () => `
${pageHero({ eyebrow: 'References', h1: 'Teams that run on RMDHost', lede: 'From agencies and traders to game networks and AI start-ups – here’s who trusts us with their infrastructure.', crumbs: crumbs('References', '/references/') })}
<section class="section">
  <div class="container">
    <div class="ref-grid">
      ${references.map((r) => `<div class="ref" data-reveal data-spot><span class="ref-logo">${r.name.split(' ').map((w) => w[0]).join('')}</span><b>${r.name}</b><small>${r.sector} · ${r.product}</small><em class="pill pill-xs" title="Replace with a real customer before launch">Sample</em></div>`).join('')}
    </div>
    ${sectionHead({ title: 'What customers say' })}
    <div class="masonry masonry-3">
      ${reviews.map((r) => `<figure class="review" data-reveal>${stars(r.rating)}<blockquote>“${r.text}”</blockquote><figcaption><span class="av">${r.name[0]}</span><span><b>${r.name}</b><small>${r.role} · ${r.product}</small></span>${r.sample ? '<em class="pill pill-xs">Sample</em>' : ''}</figcaption></figure>`).join('')}
    </div>
  </div>
</section>
${ctaBand({ title: 'Become our next<br>success story.' })}`,
};

// ───────────────────────────── Sustainability
const sustain = {
  path: '/sustainability/',
  title: 'Sustainability & Green Hosting | RMDHost',
  description: 'How RMDHost reduces the environmental impact of hosting: efficient hardware, high utilisation, efficient cooling and hardware reuse.',
  schema: [bc('Sustainability', '/sustainability/')],
  body: () => `
${pageHero({ eyebrow: 'Sustainability', h1: 'Hosting with a smaller footprint', lede: 'Every watt counts. We design our infrastructure to do more with less energy – and keep hardware in service for longer.', crumbs: crumbs('Sustainability', '/sustainability/') })}
<section class="section">
  <div class="container">
    ${featureGrid([
      { icon: 'leaf', title: 'Efficient facilities', text: 'Our data centres use N+1 cooling with under-floor air distribution to reduce energy waste.' },
      { icon: 'server', title: 'High utilisation', text: 'Virtualisation lets many customers share efficient hardware instead of idling separate machines.' },
      { icon: 'refresh', title: 'Hardware reuse', text: 'Proven server platforms are refurbished and kept in service, reducing e-waste.' },
      { icon: 'bolt', title: 'Modern components', text: 'NVMe and modern CPUs deliver more performance per watt.' },
      { icon: 'globe', title: 'Local hosting', text: 'Serve users from the nearest location to reduce network energy and latency.' },
      { icon: 'check', title: 'Continuous improvement', text: 'We review our energy sources and efficiency every year.' },
    ])}
  </div>
</section>
${ctaBand()}`,
};

// ───────────────────────────── FAQ
const faqPage = {
  path: '/faq/',
  title: 'Frequently Asked Questions | RMDHost',
  description: 'Answers about RMDHost VPS, Windows VPS, dedicated servers, billing, DDoS protection, data centres and support.',
  schema: [bc('FAQ', '/faq/'), faqSchema([...homeFaqs, ...products.flatMap((p) => p.faqs)])],
  body: () => `
${pageHero({ eyebrow: 'FAQ', h1: 'Questions? We’ve got answers', lede: 'Everything you need to know about our servers, billing and support.', crumbs: crumbs('FAQ', '/faq/'), small: true })}
<section class="section">
  <div class="container narrow">
    <h2 class="h3 faq-h">General</h2>
    ${faqList(homeFaqs)}
    ${products.map((p) => `<h2 class="h3 faq-h" id="faq-${p.slug}">${p.name}</h2>${faqList(p.faqs)}`).join('')}
  </div>
</section>
${ctaBand({ title: 'Still have questions?', text: 'Our engineers are available 24/7.', href: '/support/', cta: 'Contact support' })}`,
};

// ───────────────────────────── Blog
const blog = {
  path: '/blog/',
  title: 'Blog – Hosting Guides, News & Tutorials | RMDHost',
  description: 'Guides on VPS, dedicated servers, security, AI hosting and automation from the RMDHost team.',
  schema: [bc('Blog', '/blog/')],
  body: () => `
${pageHero({ eyebrow: 'Blog', h1: 'Guides, news and ideas', lede: 'Practical advice for running fast, secure servers.', crumbs: crumbs('Blog', '/blog/'), small: true })}
<section class="section">
  <div class="container">
    <div class="post-grid">
      ${posts.map((p, i) => `
      <a class="post ${i === 0 ? 'post-lead' : ''}" href="/blog/${p.slug}/" data-reveal data-spot>
        <span class="post-art" aria-hidden="true">${icon(['rack', 'lock', 'spark'][i % 3])}</span>
        <span class="post-meta"><em class="pill pill-xs">${p.tag}</em><time datetime="${p.date}">${new Date(p.date).toLocaleDateString('en-GB', { day: 'numeric', month: 'long', year: 'numeric' })}</time></span>
        <strong>${p.title}</strong>
        <span class="muted">${p.excerpt}</span>
        <span class="link-arrow">Read article ${icon('arrow')}</span>
      </a>`).join('')}
    </div>
  </div>
</section>`,
};

const renderBody = (blocks) => blocks.map(([t, c]) => (t === 'ol' ? `<ol>${c.map((x) => `<li>${x}</li>`).join('')}</ol>` : `<${t}>${c}</${t}>`)).join('\n');

const postPages = posts.map((p) => ({
  path: `/blog/${p.slug}/`,
  title: `${p.title} | RMDHost Blog`,
  description: p.excerpt,
  ogType: 'article',
  schema: [
    breadcrumbSchema([['Home', '/'], ['Blog', '/blog/'], [p.title, `/blog/${p.slug}/`]]),
    { '@context': 'https://schema.org', '@type': 'BlogPosting', headline: p.title, datePublished: p.date, author: { '@type': 'Organization', name: site.name }, publisher: { '@type': 'Organization', name: site.name }, description: p.excerpt, mainEntityOfPage: `${site.url}/blog/${p.slug}/` },
  ],
  body: () => `
${pageHero({ eyebrow: p.tag, h1: p.title, lede: p.excerpt, crumbs: [['Home', '/'], ['Blog', '/blog/'], [p.tag, `/blog/${p.slug}/`]], small: true })}
<article class="section"><div class="container narrow prose">
  <p class="muted"><time datetime="${p.date}">${new Date(p.date).toLocaleDateString('en-GB', { day: 'numeric', month: 'long', year: 'numeric' })}</time> · ${site.name} team</p>
  ${renderBody(p.body)}
  <p><a class="row-link inline" href="/blog/">${icon('chevronLeft')} Back to the blog</a></p>
</div></article>
${ctaBand()}`,
}));

// ───────────────────────────── Knowledge base
const kbPage = {
  path: '/knowledge-base/',
  title: 'Knowledge Base – Help Articles | RMDHost',
  description: 'Help articles for the RMDHost client area, billing, VPS management, dedicated servers, DNS and security.',
  schema: [bc('Knowledge base', '/knowledge-base/')],
  body: () => `
${pageHero({ eyebrow: 'Knowledge base', h1: 'How can we help?', lede: 'Search our help articles or browse by topic.', crumbs: crumbs('Knowledge base', '/knowledge-base/'), small: true, actions: `<form class="kb-search" data-kb-search role="search" data-reveal>${icon('search')}<label class="sr-only" for="kbq">Search the knowledge base</label><input id="kbq" type="search" placeholder="e.g. reset root password"></form>` })}
<section class="section">
  <div class="container">
    <div class="kb-grid">
      ${kb.map((c) => `
      <div class="kb-cat" data-reveal>
        <h2 class="h4">${c.cat}</h2>
        <ul>${c.items.map((a) => `<li data-kb-item><a href="${site.clientArea}/knowledgebase.php?search=${encodeURIComponent(a)}">${icon('book')}${a}</a></li>`).join('')}</ul>
      </div>`).join('')}
    </div>
    <p class="center muted kb-empty" data-kb-empty hidden>No articles match your search. <a href="/support/">Ask our engineers</a>.</p>
  </div>
</section>
${ctaBand({ title: 'Can’t find an answer?', text: 'Open a ticket and an engineer will reply – usually within minutes.', href: '/support/', cta: 'Contact support' })}`,
};

// ───────────────────────────── Tutorials
const tutPage = {
  path: '/tutorials/',
  title: 'Server Tutorials – Step-by-Step Guides | RMDHost',
  description: 'Step-by-step tutorials for WordPress, Docker, n8n, Ollama, Minecraft, WireGuard, Proxmox and more on your RMDHost server.',
  schema: [bc('Tutorials', '/tutorials/')],
  body: () => `
${pageHero({ eyebrow: 'Tutorials', h1: 'Learn by doing', lede: 'Step-by-step guides to get the most from your server.', crumbs: crumbs('Tutorials', '/tutorials/'), small: true })}
<section class="section">
  <div class="container">
    <div class="filters" data-filter-group data-target=".tut" role="group" aria-label="Filter tutorials" data-reveal>
      ${['All', ...new Set(tutorials.map((t) => t.tag))].map((f, i) => `<button type="button" class="chip ${i ? '' : 'on'}" data-filter="${i ? f : '*'}" aria-pressed="${!i}">${f}</button>`).join('')}
    </div>
    <div class="tut-grid">
      ${tutorials.map((t) => `
      <a class="tut" data-family="${t.tag}" href="${site.clientArea}/knowledgebase.php?search=${encodeURIComponent(t.title)}" data-reveal data-spot>
        <span class="tut-top"><em class="pill pill-xs">${t.tag}</em><small>${t.level} · ${t.time}</small></span>
        <strong>${t.title}</strong>
        <span class="link-arrow">Start tutorial ${icon('arrow')}</span>
      </a>`).join('')}
    </div>
  </div>
</section>`,
};

// ───────────────────────────── Support / contact
const support = {
  path: '/support/',
  title: 'Support & Contact – 24/7 Expert Help | RMDHost',
  description: 'Contact RMDHost 24/7. Open a ticket, email support or sales, and get help from real engineers with VPS, dedicated servers and billing.',
  schema: [bc('Support', '/support/')],
  body: () => `
${pageHero({ eyebrow: 'Support', h1: 'Talk to a real engineer, 24/7', lede: 'Our team answers every ticket – day or night, every day of the year.', crumbs: crumbs('Support', '/support/'), small: true })}
<section class="section">
  <div class="container">
    <div class="three">
      <a class="soft-card" href="${site.ticketUrl}" data-reveal data-spot>${icon('chat')}<strong>Open a ticket</strong><span>Fastest route for technical issues. Average first reply in minutes.</span></a>
      <a class="soft-card" href="mailto:${site.email}" data-reveal data-spot>${icon('mail')}<strong>${site.email}</strong><span>Technical support and account questions.</span></a>
      <a class="soft-card" href="mailto:${site.salesEmail}" data-reveal data-spot>${icon('card')}<strong>${site.salesEmail}</strong><span>Custom builds, AI and macOS servers, volume pricing.</span></a>
    </div>
    <div class="contact" data-reveal>
      <div>
        <h2 class="h3">Send us a message</h2>
        <p class="muted">Tell us what you need and we’ll get back to you quickly. Existing customers: please use the client area so we can verify your account.</p>
        ${checklist(['24/7/365 availability', 'Free migration assistance', 'Custom hardware quotes'])}
      </div>
      <form class="form" data-contact action="mailto:${site.salesEmail}" method="post" enctype="text/plain">
        <div class="field"><label for="c-name">Name</label><input id="c-name" name="name" required autocomplete="name"></div>
        <div class="field"><label for="c-email">Email</label><input id="c-email" name="email" type="email" required autocomplete="email"></div>
        <div class="field"><label for="c-topic">Topic</label><select id="c-topic" name="topic" data-topic>
          <option>Sales question</option><option>Technical support</option><option>Billing</option>${products.map((p) => `<option>${p.name}</option>`).join('')}
        </select></div>
        <div class="field"><label for="c-msg">Message</label><textarea id="c-msg" name="message" rows="5" required></textarea></div>
        <button class="btn btn-primary" type="submit">Send message ${icon('send')}</button>
        <p class="fine left">By sending this form you agree to our <a href="/privacy-policy/">Privacy policy</a>.</p>
      </form>
    </div>
  </div>
</section>
<section class="section section-soft"><div class="container narrow">${sectionHead({ title: 'Quick answers' })}${faqList(homeFaqs.slice(0, 5))}</div></section>`,
};

// ───────────────────────────── Legal
const legalHero = (h1, path) => pageHero({ eyebrow: 'Legal', h1, lede: `Last updated: 1 October 2026`, crumbs: crumbs(h1, path), small: true });
const legalNote = '<p class="note">This document is a starting template. Have it reviewed by a qualified legal professional before publishing.</p>';

const privacy = {
  path: '/privacy-policy/',
  title: 'Privacy Policy | RMDHost',
  description: 'How RMDHost collects, uses and protects your personal data, and the rights you have under UK GDPR and EU GDPR.',
  schema: [bc('Privacy policy', '/privacy-policy/')],
  body: () => `${legalHero('Privacy policy', '/privacy-policy/')}${prose(`${legalNote}
<h2>Who we are</h2><p>${site.name} (“we”, “us”) provides hosting services through ${site.domain}. We are the controller of personal data processed through this website and our client area. Contact: <a href="mailto:${site.email}">${site.email}</a>.</p>
<h2>Data we collect</h2><ul><li>Account details: name, email address, postal address, phone number.</li><li>Billing details processed by our payment providers.</li><li>Technical data: IP address, browser type, log files.</li><li>Support communications.</li><li>Cookie data, according to your preferences.</li></ul>
<h2>Why we use it</h2><p>To provide and bill for services (contract), to secure our network and prevent abuse (legitimate interests), to meet legal obligations such as tax records, and – only with your consent – for analytics and marketing.</p>
<h2>Sharing</h2><p>We share data only with service providers who help us run our business (payment processors, data-centre operators, email providers) under appropriate contracts, or where required by law.</p>
<h2>International transfers</h2><p>Where data is transferred outside the UK/EEA we use appropriate safeguards such as standard contractual clauses.</p>
<h2>Retention</h2><p>We keep account and billing records for as long as required by law (typically six years) and delete other data when no longer needed.</p>
<h2>Your rights</h2><p>You may request access, correction, deletion, restriction, portability or object to processing. You can withdraw consent at any time via <button type="button" class="linklike" data-cookie-manage>cookie settings</button>. You may complain to the ICO or your local supervisory authority.</p>`)}`,
};

const terms = {
  path: '/terms-of-service/',
  title: 'Terms of Service | RMDHost',
  description: 'The terms and conditions that apply to RMDHost hosting services, billing, acceptable use and cancellations.',
  schema: [bc('Terms of service', '/terms-of-service/')],
  body: () => `${legalHero('Terms of service', '/terms-of-service/')}${prose(`${legalNote}
<h2>1. Agreement</h2><p>By ordering a service from ${site.name} you agree to these terms and our Acceptable Use Policy.</p>
<h2>2. Services</h2><p>We provide virtual and dedicated servers, storage and related services as described on our website at the time of order.</p>
<h2>3. Billing</h2><p>Services are billed in advance on a monthly basis unless stated otherwise. Prices exclude VAT, which is added where applicable. Unpaid services may be suspended after the due date.</p>
<h2>4. Acceptable use</h2><p>You must not use our services for illegal content, spam, malware, network attacks, or activities that harm our network or other customers. “Unlimited” bandwidth is subject to fair use.</p>
<h2>5. Uptime & support</h2><p>We aim for 99.99% network availability. Planned maintenance is announced in advance. Support is available 24/7 via the client area.</p>
<h2>6. Backups</h2><p>Unless a plan explicitly includes backups, you are responsible for backing up your data.</p>
<h2>7. Cancellation & refunds</h2><p>You may cancel at any time from the client area; cancellation takes effect at the end of the billing period. Refund requests made within 7 days of a first order are reviewed individually.</p>
<h2>8. Liability</h2><p>Our liability is limited to the fees paid for the affected service in the previous month, except where the law does not allow such limitation.</p>
<h2>9. Governing law</h2><p>These terms are governed by the laws of England and Wales.</p>`)}`,
};

const cookies = {
  path: '/cookie-policy/',
  title: 'Cookie Policy | RMDHost',
  description: 'Which cookies RMDHost uses, why, and how you can manage your cookie preferences.',
  schema: [bc('Cookie policy', '/cookie-policy/')],
  body: () => `${legalHero('Cookie policy', '/cookie-policy/')}${prose(`${legalNote}
<p>Cookies are small files stored on your device. We use them to run the website and, with your permission, to understand how it is used.</p>
<h2>Categories</h2>
<table class="tbl"><thead><tr><th>Category</th><th>Purpose</th><th>Default</th></tr></thead><tbody>
<tr><td>Necessary</td><td>Security, consent choice, currency and theme preferences</td><td>Always on</td></tr>
<tr><td>Analytics</td><td>Anonymous usage statistics</td><td>Off until you consent</td></tr>
<tr><td>Marketing</td><td>Advertising and remarketing</td><td>Off until you consent</td></tr>
</tbody></table>
<h2>Managing cookies</h2><p>You can change your choice at any time: <button type="button" class="btn btn-outline btn-sm" data-cookie-manage>Manage preferences</button></p>
<p>You can also block cookies in your browser settings, but some parts of the site may not work.</p>`)}`,
};

const notFound = {
  path: '/404.html',
  file: '404.html',
  title: 'Page not found | RMDHost',
  description: 'The page you are looking for could not be found.',
  noindex: true,
  body: () => `
${pageHero({ eyebrow: 'Error 404', h1: 'This page went offline', lede: 'The page you’re looking for doesn’t exist or has moved.', actions: '<div class="hero-cta"><a class="btn btn-white btn-lg" href="/">Back to home</a></div>' })}`,
};

export default [pricing, dataCentres, ddos, status, about, refs, sustain, faqPage, blog, ...postPages, kbPage, tutPage, support, privacy, terms, cookies, notFound];

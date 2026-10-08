import { products } from '../data/products.mjs';
import { icon } from '../lib/icons.mjs';
import {
  scenes, planGrid, everyPlan, sectionHead, faqList, faqSchema, breadcrumbSchema, productSchema,
  featureGrid, locationsBlock, ctaBand, checklist, money, orderUrl,
} from '../lib/components.mjs';

function instantTable(p) {
  return `
<div class="filters" data-filter-group role="group" aria-label="Filter servers" data-reveal>
  ${p.filters.map((f, i) => `<button type="button" class="chip ${i ? '' : 'on'}" data-filter="${i ? f : '*'}" aria-pressed="${!i}">${f}</button>`).join('')}
  <label class="chip chip-check"><input type="checkbox" data-instock> In stock only</label>
</div>
<div class="stable" role="table" aria-label="Instant dedicated servers">
  <div class="srow shead" role="row"><span role="columnheader">Processor</span><span role="columnheader">RAM</span><span role="columnheader">Storage</span><span role="columnheader">Included</span><span role="columnheader">Price</span><span role="columnheader"><span class="sr-only">Order</span></span></div>
  ${p.servers.map((s) => `
  <div class="srow ${s.stock ? '' : 'out'}" role="row" data-family="${s.family}" data-stock="${s.stock}">
    <span role="cell" data-l="Processor"><b>${s.name}</b><small>${s.cpu}</small></span>
    <span role="cell" data-l="RAM">${s.ram}</span>
    <span role="cell" data-l="Storage">${s.storage}</span>
    <span role="cell" data-l="Included">${s.network}<small>/64 IPv6 · DDoS protection</small></span>
    <span role="cell" data-l="Price" class="sprice">${money(s.price)}</span>
    <span role="cell">${s.stock ? `<a class="btn btn-primary btn-sm" href="${orderUrl(s)}">Order now</a><small class="free">Free setup</small>` : '<span class="btn btn-sm btn-disabled" aria-disabled="true">Sold out</span>'}</span>
  </div>`).join('')}
</div>`;
}

function dedicatedTable(t) {
  return `
<h3 class="sub-h center" data-reveal>${t.title}</h3>
<div class="stable stable-6" role="table" aria-label="${t.title}">
  <div class="srow shead" role="row">${t.columns.map((c) => `<span role="columnheader">${c}</span>`).join('')}<span role="columnheader"><span class="sr-only">Order</span></span></div>
  ${t.rows.map((r) => `
  <div class="srow" role="row">
    <span role="cell" data-l="Server"><b>${r.name}</b></span>
    <span role="cell" data-l="CPU">${r.cpu}</span>
    <span role="cell" data-l="Storage">${r.storage}</span>
    <span role="cell" data-l="RAM">${r.ram}</span>
    <span role="cell" data-l="Network">${r.network}</span>
    <span role="cell" data-l="Price" class="sprice">${money(r.price)}</span>
    <span role="cell"><a class="btn btn-outline btn-sm" href="${orderUrl({ id: r.name.toLowerCase(), name: r.name })}">Order now</a></span>
  </div>`).join('')}
</div>`;
}

function useTabs(p) {
  if (!p.tabs) return '';
  return `
<section class="section">
  <div class="container">
    ${sectionHead({ title: `What you can run on ${p.name}` })}
    <div class="tabs" data-tabs data-reveal>
      <div class="tablist tablist-center" role="tablist">
        <span class="tab-ind" aria-hidden="true"></span>
        ${p.tabs.map((t, i) => `<button role="tab" id="ut-${i}" aria-controls="up-${i}" aria-selected="${!i}" tabindex="${i ? -1 : 0}">${t.label}</button>`).join('')}
      </div>
      ${p.tabs.map((t, i) => `<div class="tabpanel use-panel" role="tabpanel" id="up-${i}" aria-labelledby="ut-${i}" ${i ? 'hidden' : ''}><p class="lead">${t.text}</p></div>`).join('')}
    </div>
  </div>
</section>`;
}

const productPage = (p) => ({
  path: `/${p.slug}/`,
  title: p.title,
  description: p.description,
  schema: [productSchema(p), faqSchema(p.faqs), breadcrumbSchema([['Home', '/'], [p.name, `/${p.slug}/`]])],
  body: () => `
<section class="hero hero-page hero-product dark">
  <div class="glow" aria-hidden="true"><i></i><i></i><i></i></div>
  <div class="grid-bg" aria-hidden="true"></div>
  <div class="container hero-split">
    <div class="hero-copy">
      <nav class="crumbs" aria-label="Breadcrumb"><a href="/">Home</a><span>/</span><span aria-current="page">${p.name}</span></nav>
      <p class="eyebrow eyebrow-hero" data-reveal>${p.eyebrow}${p.from != null ? ` · from <b>${money(p.from)}</b>` : ''}${p.tag ? ` <em class="pill pill-xs pill-invert">${p.tag}</em>` : ''}</p>
      <h1 class="h1" data-reveal>${p.h1}</h1>
      <p class="lead" data-reveal>${p.lede}</p>
      <div data-reveal>${checklist(p.checklist, 'checks-hero')}</div>
      <div class="hero-cta left" data-reveal>
        <a class="btn btn-white btn-lg" href="#plans">${p.from != null ? 'Choose plan' : 'See specifications'}</a>
        ${p.from == null ? '<a class="btn btn-ghost-light btn-lg" href="/support/">Contact sales</a>' : ''}
      </div>
      <p class="hero-note left" data-reveal>${icon('shield')} DDoS protection included · 24/7 expert support</p>
    </div>
    <div class="hero-visual" data-reveal>${scenes[p.visual]()}</div>
  </div>
</section>

<div class="subnav" data-subnav>
  <div class="container"><nav class="subnav-pill" aria-label="On this page">
    <a href="#plans" class="on">Pricing</a><a href="#features">Features</a><a href="#os">OS &amp; apps</a><a href="#locations">Locations</a><a href="#faq">FAQ</a>
  </nav></div>
</div>

<section class="section" id="plans">
  <div class="container">
    ${sectionHead({ title: `Choose your ${p.short} plan`, text: p.comingSoon || null })}
    ${p.instant ? instantTable(p) : planGrid(p.plans, p.name)}
    ${p.table ? dedicatedTable(p.table) : ''}
    ${everyPlan(p.everyPlan)}
    <p class="fine">Prices exclude VAT and are billed monthly. Toggle GBP / USD in the header.</p>
  </div>
</section>

${useTabs(p)}

<section class="section section-soft" id="features">
  <div class="container">
    ${sectionHead({ title: `Secure, speedy, reliable ${p.name}` })}
    ${featureGrid(p.features)}
  </div>
</section>

<section class="section" id="locations">
  <div class="container"><div class="dark-panel dark">${locationsBlock()}</div></div>
</section>

<section class="section pt-0" id="os">
  <div class="container">
    ${sectionHead({ title: 'One-click deployment', text: 'Deploy popular operating systems, control panels and applications. Get up and running in minutes.' })}
    <div class="tabs" data-tabs data-reveal>
      <div class="tablist" role="tablist">
        <span class="tab-ind" aria-hidden="true"></span>
        <button role="tab" id="os-t1" aria-controls="os-p1" aria-selected="true">Applications</button>
        <button role="tab" id="os-t2" aria-controls="os-p2" aria-selected="false" tabindex="-1">Operating systems</button>
        <button role="tab" id="os-t3" aria-controls="os-p3" aria-selected="false" tabindex="-1">Use cases</button>
      </div>
      <div class="tabpanel" role="tabpanel" id="os-p1" aria-labelledby="os-t1"><div class="app-grid">${p.stack.map((s) => `<span class="app"><b>${s[0]}</b>${s}${icon('arrowUpRight', 'corner')}</span>`).join('')}</div></div>
      <div class="tabpanel" role="tabpanel" id="os-p2" aria-labelledby="os-t2" hidden><div class="app-grid">${p.os.map((s) => `<span class="app"><b>${s[0]}</b>${s}${icon('arrowUpRight', 'corner')}</span>`).join('')}</div></div>
      <div class="tabpanel" role="tabpanel" id="os-p3" aria-labelledby="os-t3" hidden><div class="app-grid">${p.useCases.map((s) => `<span class="app"><b>${icon('check')}</b>${s}</span>`).join('')}</div></div>
    </div>
    <div class="marquee" aria-hidden="true"><div class="marquee-track">${[...p.os, ...p.stack, ...p.os, ...p.stack].map((s) => `<span>${s}</span>`).join('')}</div></div>
  </div>
</section>

<section class="section section-soft" id="faq">
  <div class="container narrow">
    ${sectionHead({ title: `${p.name} FAQs` })}
    ${faqList(p.faqs)}
  </div>
</section>

<section class="section">
  <div class="container">
    ${sectionHead({ title: 'Explore more servers' })}
    <div class="carousel" data-carousel>
      ${products.filter((x) => x.slug !== p.slug).map((x) => `<a class="car-card car-light" href="/${x.slug}/" data-spot>${icon(x.icon)}${icon('arrowUpRight', 'corner')}<strong>${x.name}</strong><span>${x.from != null ? `From ${money(x.from)}` : 'Pricing coming soon'}</span></a>`).join('')}
    </div>
  </div>
</section>

${ctaBand({ title: `Ready to launch your<br>${p.name.toLowerCase().includes('server') ? p.name : p.name + ' server'}?`, href: '#plans', cta: p.from != null ? 'Choose plan' : 'Contact sales' })}
`,
});

export default products.map(productPage);

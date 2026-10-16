import { site, infra } from '../data/site.mjs';
import { icon } from '../lib/icons.mjs';
import { pageHero, sectionHead, featureGrid, faqSection, faqSchema, breadcrumbSchema, scenes, ctaBand, locationsBlock } from '../lib/components.mjs';

const cap = infra.ddosCapacity.value;
const enquiry = `/support/?topic=${encodeURIComponent('Edge servers')}`;

const faqs = [
  ['What is the difference between an edge server and DDoS protection?', `DDoS protection filters attacks aimed at your server’s IP address. An edge server adds a protected entry point in front of your server, so its real IP is never published and attackers cannot go around the ${cap} filtering by targeting it directly.`],
  ['Will players notice the edge server?', 'Players connect to the edge IP or hostname instead of your origin. Choosing an edge location close to them keeps the extra network hop short, and traffic between the edge and your origin travels over our network.'],
  ['Which protocols are supported?', 'TCP and UDP forwarding for game servers, voice servers, web applications and APIs. Tell us which ports your service uses and we will configure the edge to match.'],
  ['Can I use more than one edge location?', 'Yes. Communities with players in several regions can use an edge in each region, all forwarding to the same origin server.'],
  ['How do I order an edge server?', 'Edge servers are configured to order. Send us your game or application, your origin location, the ports you use and your expected traffic, and our engineers will recommend a setup and send a quote.'],
];

export const edgeContent = () => `
<section class="section" id="what"><div class="container">
  <div class="alt" data-reveal>
    <div class="alt-copy">
      <p class="eyebrow">What are edge servers?</p>
      <h2 class="h3">A protected front door for your server</h2>
      <p class="muted">An edge server is a protected entry point that sits in front of your main (origin) server. Players and users connect to the edge instead of your origin. The edge filters attacks with our ${cap} DDoS mitigation network, then forwards clean traffic to your origin through a private tunnel.</p>
      <p class="muted">Because your origin’s real IP address is never published, attackers cannot bypass the protection by hitting your server directly. Your origin can be resized, moved or rebuilt without changing the address your players use.</p>
      <a class="row-link" href="${enquiry}">Plan an edge setup${icon('arrow')}</a>
    </div>
    <div class="alt-visual">${scenes.shield()}</div>
  </div>
</div></section>
<section class="section section-soft"><div class="container">
${sectionHead({ eyebrow: 'How it works', title: 'Connect. Filter. Forward. Respond.' })}
<ol class="steps">
${[['Connect', 'Players connect to the edge server’s IP or hostname in the location closest to them.'], ['Filter', `Traffic is checked at the edge by ${cap} of always-on DDoS mitigation. Floods and malformed packets are dropped.`], ['Forward', 'Clean TCP and UDP traffic for your ports is forwarded to your origin server through a private tunnel.'], ['Respond', 'Your origin replies through the edge, so its real IP address is never exposed to the internet.']].map(([t, d], i) => `<li data-reveal><span class="step-n">0${i + 1}</span><h3>${t}</h3><p class="muted">${d}</p></li>`).join('')}
</ol></div></section>
<section class="section"><div class="container">
${sectionHead({ title: 'Why use an edge server?' })}
${featureGrid([
  { icon: 'lock', title: 'Hidden origin IP', text: 'Only the edge address is public. Your origin cannot be targeted directly, even if an attacker learns your domain.' },
  { icon: 'shield', title: `${cap} DDoS filtering`, text: 'Volumetric, protocol and game-specific floods are filtered at the edge before they reach your origin.' },
  { icon: 'globe', title: 'Closer to your players', text: 'Place edges in the regions where your community plays, and carry traffic to your origin over our network.' },
  { icon: 'game', title: 'Game and voice ready', text: 'UDP and TCP forwarding for Minecraft, Rust, ARK, Arma 3, FiveM, TeamSpeak and other services.' },
  { icon: 'scale', title: 'Change your origin freely', text: 'Upgrade, migrate or rebuild your origin server without changing the address your players use.' },
  { icon: 'headset', title: 'Set up by engineers', text: 'Our team configures the edge, tunnel and port forwarding with you, and helps 24/7 if anything changes.' },
])}</div></section>
<section class="section section-soft"><div class="container narrow prose">
<h2>Technical details</h2>
<table class="tbl"><thead><tr><th>Feature</th><th>Details</th></tr></thead><tbody>
<tr><td>DDoS protection</td><td>${cap} of always-on mitigation capacity at the edge, Layer 3/4 and game-aware rules. See <a href="/ddos-protection/">DDoS protection</a>.</td></tr>
<tr><td>Protocols</td><td>TCP and UDP forwarding for the ports your service uses.</td></tr>
<tr><td>Origin connection</td><td>A private tunnel between the edge and your origin. Your origin IP is never published.</td></tr>
<tr><td>Locations</td><td>Edge servers can be placed in our core locations. See <a href="/data-centres/">data centres</a>.</td></tr>
<tr><td>Uptime</td><td>Covered by our <a href="/sla/">${infra.networkSla.value} network uptime SLA</a>.</td></tr>
<tr><td>Setup</td><td>Configured to order by our engineers. Pricing depends on location, ports and traffic.</td></tr>
</tbody></table>
<h2>Who uses edge servers?</h2>
<ul>
<li><strong>Game communities</strong> that keep getting knocked offline and need their server IP hidden. See <a href="/game-servers/">game servers</a>.</li>
<li><strong>Voice servers</strong> such as TeamSpeak that must stay reachable during matches and events.</li>
<li><strong>Web applications and APIs</strong> that need a stable public address in front of a <a href="/vps/">VPS</a> or <a href="/dedicated-servers/">dedicated server</a>.</li>
</ul>
</div></section>
${faqSection({ title: 'Edge server FAQs', faqs })}`;

export const edgePage = {
  path: '/edge-servers/',
  title: `Edge Servers – Hide Your Origin IP with ${cap} DDoS Protection | RMDHost`,
  description: `Protected edge servers that hide your origin IP and filter attacks with ${cap} of DDoS mitigation. TCP and UDP forwarding for game servers, voice and web apps.`,
  schema: [
    breadcrumbSchema([['Home', '/'], ['Edge servers', '/edge-servers/']]),
    faqSchema(faqs),
    { '@context': 'https://schema.org', '@type': 'Service', name: `${site.name} Edge Servers`, serviceType: 'DDoS-protected edge server', url: `${site.url}/edge-servers/`, provider: { '@type': 'Organization', name: site.name, url: site.url } },
  ],
  body: () => `${pageHero({ eyebrow: 'Edge servers', h1: 'Edge servers that keep your origin hidden', lede: `Put a protected edge server between the internet and your game or application server. Users connect to the edge, ${cap} DDoS filtering removes attacks, and only clean traffic reaches your origin.`, actions: `<div class="hero-cta left" data-reveal><a class="btn btn-white btn-lg" href="${enquiry}">Talk to an engineer</a><a class="btn btn-ghost-light btn-lg" href="#what">What are edge servers?</a></div>`, visual: scenes.game(), crumbs: [['Home', '/'], ['Edge servers', '/edge-servers/']] })}
${edgeContent()}
<section class="section" id="locations"><div class="container"><div class="dark-panel dark">${locationsBlock()}</div></div></section>
${ctaBand({ title: 'Hide your origin.<br>Stay online.', text: 'Tell us about your game or application and we’ll design an edge setup for it.', href: enquiry, cta: 'Talk to an engineer' })}`,
};

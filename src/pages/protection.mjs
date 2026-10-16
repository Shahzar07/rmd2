import { infra } from '../data/site.mjs';
import { icon } from '../lib/icons.mjs';
import { pageHero, sectionHead, featureGrid, faqSection, faqSchema, breadcrumbSchema, scenes, ctaBand } from '../lib/components.mjs';

const cap = infra.ddosCapacity.value;

const faqs = [
  ['Is DDoS protection free?', `Yes. Protection from our ${cap} mitigation network is included free on VPS plans, and game servers include game-aware filtering. The DDoS row on each plan shows the protection it includes. Ask sales about options for dedicated servers.`],
  ['Do I need to switch protection on?', 'No. Traffic is monitored continuously and mitigation starts automatically when an attack is detected. You do not need to open a ticket or change DNS to activate it.'],
  ['Which attacks are filtered?', 'Volumetric floods (UDP, ICMP and amplification attacks such as DNS, NTP, SSDP, CLDAP and Memcached), protocol attacks (SYN, ACK and fragmentation floods) and game-specific floods against UDP game ports. For HTTP floods and other application-layer attacks, combine network protection with rate limiting or a WAF.'],
  ['Does mitigation add latency?', 'Clean traffic is forwarded to your server after filtering, normally with little or no noticeable added latency. If you see packet loss or legitimate players being blocked, send us timestamps and affected ports so we can tune the filtering.'],
  ['Do I still need a firewall?', 'Yes. DDoS filtering stops floods before they reach your port. It does not replace patching, a host firewall, strong passwords or SSH keys, or application rate limits.'],
];

const stats = [
  [cap, 'Mitigation capacity'],
  ['L3–L7', 'Attack layers covered'],
  ['24/7', 'Always-on detection and mitigation'],
  ['£0', 'Included free on VPS plans'],
];

const details = [
  ['Mitigation capacity', `${cap} of network-wide filtering capacity. This is separate from your server port speed, which is listed on each plan.`],
  ['Activation', 'Always-on monitoring with automatic mitigation. No manual activation, DNS change or ticket is needed.'],
  ['Layer 3 / 4', 'UDP, ICMP, SYN, ACK, RST and fragmentation floods, plus reflection and amplification (DNS, NTP, SSDP, CLDAP, Memcached, Chargen).'],
  ['Game traffic', 'UDP-aware rules that separate real player sessions from floods, including query floods and malformed packets aimed at game ports.'],
  ['Layer 7', 'HTTP/S floods can be reduced with rate limiting and filtering on request. For login abuse, scraping and application bugs, use a WAF and application-level limits as well.'],
  ['Clean traffic', 'Legitimate traffic keeps flowing to your server during an attack, so your service stays reachable while the flood is filtered.'],
  ['Origin protection', 'Put an <a href="/edge-servers/">edge server</a> in front of your origin to hide its IP address and filter traffic close to your users.'],
  ['Incident support', 'Engineers investigate attacks, false positives and routing 24/7. Send the service ID, affected IP and ports, and the start time with timezone.'],
];

export const protectionContent = () => `
<section class="section"><div class="container">
  <div class="stats stats-plain" data-reveal>
    ${stats.map(([v, l]) => `<div class="stat"><b>${v}</b><span>${l}</span></div>`).join('')}
  </div>
${sectionHead({ eyebrow: 'How it works', title: 'Detect. Divert. Scrub. Deliver.', text: `Every packet that reaches our network is analysed. When an attack starts, traffic for the targeted IP is routed through ${cap} of mitigation capacity, and only clean traffic continues to your server.` })}
<ol class="steps">
${[['Detect', 'Traffic patterns, packet rates and protocol behaviour are analysed continuously to spot an attack within moments of it starting.'], ['Divert', 'Traffic for the targeted IP is routed through filtering infrastructure at the edge of the network, before it reaches your port.'], ['Scrub', 'Layer 3/4 floods, amplification and malformed packets are dropped. Game and protocol-aware rules keep real sessions connected.'], ['Deliver', 'Clean traffic is forwarded to your server. When the attack ends, routing returns to normal automatically.']].map(([t, d], i) => `<li data-reveal><span class="step-n">0${i + 1}</span><h3>${t}</h3><p class="muted">${d}</p></li>`).join('')}
</ol></div></section>
<section class="section section-soft"><div class="container">
${sectionHead({ title: 'Protection against every type of attack', text: 'Volumetric, protocol and application attacks need different defences. Our network handles the first two automatically and helps you with the third.' })}
${featureGrid([
  { icon: 'globe', title: 'Volumetric floods', text: `UDP floods, ICMP floods and amplification attacks (DNS, NTP, SSDP, CLDAP, Memcached) are absorbed by ${cap} of capacity before they can saturate your link.` },
  { icon: 'pulse', title: 'Protocol attacks · Layer 3/4', text: 'SYN, ACK and RST floods, fragmentation and state-exhaustion attacks are filtered before they reach your server.' },
  { icon: 'game', title: 'Game server protection', text: 'UDP-aware filtering for Minecraft, Rust, ARK, Arma 3 and other game servers, tuned to keep real players connected during an attack.' },
  { icon: 'shield', title: 'Application attacks · Layer 7', text: 'HTTP/S floods can be reduced with rate limiting and filtering. Pair them with a WAF or CDN for login abuse and scraping.' },
  { icon: 'bolt', title: 'Edge servers', text: 'Hide your origin IP behind a protected edge server so attackers cannot bypass filtering by hitting your server directly.' },
  { icon: 'lock', title: 'Host security', text: 'Patch software, close unused ports, use SSH keys and restrict admin access. Network protection and host security work together.' },
])}</div></section>
<section class="section"><div class="container narrow prose">
<h2>Technical details</h2>
<table class="tbl"><thead><tr><th>Feature</th><th>Details</th></tr></thead><tbody>
${details.map(([k, v]) => `<tr><td>${k}</td><td>${v}</td></tr>`).join('\n')}
</tbody></table>
<h2>Protection by product</h2>
<table class="tbl"><thead><tr><th>Product</th><th>Protection</th></tr></thead><tbody>
<tr><td><a href="/vps/">Linux VPS</a></td><td>DDoS protection included free</td></tr>
<tr><td><a href="/windows-vps/">Windows VPS</a>, <a href="/ssd-vps/">SSD VPS</a>, <a href="/cloud-vps/">Cloud VPS</a>, <a href="/lightsail-vps/">Lightsail VPS</a></td><td>Anti-DDoS Pro included free</td></tr>
<tr><td><a href="/game-servers/">Game servers</a></td><td>Game-aware Anti-DDoS filtering included</td></tr>
<tr><td><a href="/dedicated-servers/">Dedicated servers</a></td><td>Protection options available. Ask sales for your configuration</td></tr>
<tr><td><a href="/edge-servers/">Edge servers</a></td><td>Filtering at the edge, with your origin IP hidden</td></tr>
</tbody></table>
<p>Your uptime commitment and service credits are set out in our <a href="/sla/">Service Level Agreement</a>. Using our network to attack others is forbidden by the <a href="/acceptable-use/">Acceptable Use Policy</a>. Under attack right now? <a href="/support/">Open a ticket</a> and an engineer will help 24/7.</p>
</div></section>
${faqSection({ title: 'DDoS protection FAQs', faqs })}`;

export const protectionPage = {
  path: '/ddos-protection/',
  title: `DDoS Protection – ${cap} Always-On Mitigation | RMDHost`,
  description: `Free, always-on DDoS protection with ${cap} of mitigation capacity. Layer 3/4 and game-aware filtering for VPS, game and dedicated servers, plus Layer 7 options.`,
  schema: [breadcrumbSchema([['Home', '/'], ['DDoS protection', '/ddos-protection/']]), faqSchema(faqs)],
  body: () => `${pageHero({ eyebrow: 'Network security', h1: `DDoS protection with ${cap} of capacity`, lede: 'Always-on, multi-layer filtering that absorbs volumetric, protocol and game-specific attacks before they reach your server. Included free on VPS plans.', actions: `<div class="hero-cta left" data-reveal><a class="btn btn-white btn-lg" href="/pricing/">View protected plans</a><a class="btn btn-ghost-light btn-lg" href="/edge-servers/">Edge servers ${icon('arrow')}</a></div>`, visual: scenes.shield(), crumbs: [['Home', '/'], ['DDoS protection', '/ddos-protection/']] })}${protectionContent()}${ctaBand({ title: 'Stay online.<br>Whatever comes.', text: `${cap} DDoS protection is included free with every VPS.`, href: '/pricing/', cta: 'Choose a protected plan' })}`,
};

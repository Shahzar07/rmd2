import { pageHero, sectionHead, featureGrid, faqSection, faqSchema, breadcrumbSchema, scenes, ctaBand } from '../lib/components.mjs';

const faqs = [
  ['Which plans include protection?', 'Check the DDoS row on the selected package. Protection, network provider and filtering limits can differ by product and location.'],
  ['Does a protected server also need a firewall?', 'Yes. Network filtering does not replace patching, access controls, application rate limits or a host firewall.'],
  ['Is 30 Tbps available on my service?', 'No RMDHost-wide capacity figure has been confirmed. Ask for the mitigation capacity and policy applicable to your exact package.'],
  ['Can mitigation affect legitimate players?', 'Filtering or emergency routing can affect sessions and latency. Share timestamps, affected ports and examples with support so false positives can be investigated.'],
  ['What should I send when reporting an attack?', 'Open a support ticket with your service ID, affected IP and ports, start time with timezone, symptoms and any safe packet or log samples. Never send passwords.'],
];

export const protectionContent = () => `
<section class="section"><div class="container">
${sectionHead({ eyebrow: 'Understand your protection', title: 'Filter the attack. Keep the service reachable.', text: 'Network mitigation separates abusive traffic from legitimate connections. The exact filtering path and capacity depend on the service and upstream network.' })}
<ol class="steps">
${[['Observe','Traffic patterns, packet rates and protocol behaviour help identify unusual demand.'],['Mitigate','Upstream filters can drop attack traffic or apply protocol-specific rules.'],['Forward','Permitted traffic continues toward the server, subject to the service port and routing policy.'],['Investigate','Engineers review impact, routing and false positives when an incident is reported.']].map(([t,d],i)=>`<li data-reveal><span class="step-n">0${i+1}</span><h3>${t}</h3><p class="muted">${d}</p></li>`).join('')}
</ol></div></section>
<section class="section section-soft"><div class="container">
${sectionHead({ title: 'Network attacks and application security', text: 'Different attacks need different controls. Confirm the protections available for your protocol before deploying.' })}
${featureGrid([
{icon:'globe',title:'Volumetric floods',text:'High traffic volume can exhaust links. Upstream filtering capacity matters before traffic reaches your server port.'},
{icon:'pulse',title:'Protocol attacks · Layer 3/4',text:'SYN, UDP and reflection floods target network or transport resources. Filtering rules depend on the network and service.'},
{icon:'game',title:'Game traffic',text:'UDP game sessions need rules that distinguish legitimate packets from floods. Ask which games and query protocols are covered.'},
{icon:'shield',title:'Application attacks · Layer 7',text:'HTTP floods and costly application requests may require a WAF, caching or application rate limits. A network DDoS plan alone does not establish this coverage.'},
{icon:'lock',title:'Host security',text:'Patch software, close unused ports, restrict administrative access and use strong credentials or SSH keys.'},
{icon:'backup',title:'Recovery planning',text:'Maintain independent backups and incident contacts. DDoS filtering does not recover deleted data or repair compromised software.'}
])}</div></section>
<section class="section"><div class="container narrow prose">
<h2>Technical details to confirm with your plan</h2>
<table class="tbl"><thead><tr><th>Detail</th><th>What it means for your service</th></tr></thead><tbody>
<tr><td>Mitigation capacity</td><td>Shared filtering capacity is different from your server port speed. There is no confirmed 30 Tbps RMDHost-wide specification.</td></tr>
<tr><td>Activation</td><td>Confirm whether filtering is always on or starts after detection, and whether routing changes during an incident.</td></tr>
<tr><td>Coverage</td><td>Confirm protected IPs, ports, protocols and any exclusions, especially for game queries and voice traffic.</td></tr>
<tr><td>Limits and emergency action</td><td>Severe incidents may require traffic restrictions or temporary null routing. Ask for the policy applicable to your package.</td></tr>
<tr><td>Availability commitments</td><td>Uptime targets, exclusions and service credits are defined by the service agreement. Protection is not a promise of zero downtime.</td></tr>
</tbody></table>
<p>For workloads close to players or users, location and routing influence latency. Filtering capacity and physical proximity describe different aspects of the network.</p>
<p>Read the <a href="/sla/">Service Level Agreement</a>, <a href="/acceptable-use/">Acceptable Use Policy</a> and <a href="/fair-use/">Fair Use Policy</a>, or <a href="/support/">ask support about your configuration</a>.</p>
</div></section>
${faqSection({title:'DDoS protection FAQs',faqs})}`;

export const protectionPage = {
 path:'/ddos-protection/', title:'DDoS Protection – Network Filtering & Coverage | RMDHost',
 description:'Understand network DDoS filtering, Layer 3/4 attacks, game protocols, application security and the protection available with your RMDHost plan.',
 schema:[breadcrumbSchema([['Home','/'],['DDoS protection','/ddos-protection/']]),faqSchema(faqs)],
 body:()=>`${pageHero({eyebrow:'Network security',h1:'DDoS protection. Clear coverage. Real support.',lede:'Understand what protects your server, how mitigation works and which technical limits apply to your workload.',visual:scenes.shield(),crumbs:[['Home','/'],['DDoS protection','/ddos-protection/']]})}${protectionContent()}${ctaBand({title:'Plan your protection.<br>Talk to an engineer.',href:'/support/',cta:'Discuss your workload'})}`,
};

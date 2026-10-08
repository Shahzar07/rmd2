// Shared content: FAQs, reviews, locations, blog, knowledge base, tutorials.

export const homeFaqs = [
  ['What is RMDHost?', 'RMDHost is an international hosting provider offering Linux VPS, Windows VPS, cloud servers, dedicated servers, game servers and private cloud storage from data centres in the UK, Europe and North America.'],
  ['Which server is right for me?', 'Start with a Linux VPS for websites and apps, choose Windows VPS for Remote Desktop and .NET, and move to a dedicated server when you need a whole machine for heavy traffic, large databases or virtualisation. Our sales team can recommend a plan for free.'],
  ['Do you charge setup fees?', 'No setup fees apply to VPS plans or in-stock instant dedicated servers.'],
  ['Is DDoS protection included?', 'Yes. Every VPS, dedicated and game server includes network-level DDoS protection at no extra cost. Game servers add game-aware filtering.'],
  ['Where are your data centres?', 'Derby and Leeds (UK), Amsterdam (Netherlands), Frankfurt (Germany), Riga (Latvia) and New York (USA), with additional instant dedicated capacity across the USA.'],
  ['Can I pay in USD or GBP?', 'Yes. Use the currency selector at the top of the page to view prices in GBP or USD. You choose your billing currency at checkout.'],
  ['How fast is server delivery?', 'VPS plans are provisioned instantly. Standard dedicated servers are delivered within 24 hours and instant dedicated servers in the USA are online within minutes.'],
  ['Do you offer refunds?', 'Contact our billing team within 7 days of your first order if the service is not right for you – we will review every request individually. See our Terms of Service for details.'],
  ['Can I upgrade later?', 'Yes. VPS plans can be upgraded from the client area at any time and dedicated servers can be customised with more RAM, storage or IP addresses.'],
  ['How do I contact support?', 'Our engineers are available 24/7 by ticket and email. Open a ticket from the client area or email support@rmdhost.com.'],
];

// ⚠️ SAMPLE REVIEWS – replace with real customer reviews (e.g. from
// HostAdvice or Trustpilot) before going live, then set `sample: false`.
export const reviews = [
  { sample: true, name: 'Daniel R.', role: 'Agency owner', product: 'Linux VPS', rating: 5, text: 'We moved twelve client WordPress sites onto two Premium Berg servers. Pages load faster and support answered every ticket within minutes.' },
  { sample: true, name: 'Aisha K.', role: 'Forex trader', product: 'Windows VPS', rating: 5, text: 'My MetaTrader bots have run around the clock on a Winberg x8 without a single disconnect. RDP is smooth even from my phone.' },
  { sample: true, name: 'Marco P.', role: 'Minecraft network admin', product: 'Game Servers', rating: 5, text: 'Anti-DDoS Game actually works. We were hit during a launch weekend and players never noticed.' },
  { sample: true, name: 'Sophie L.', role: 'CTO, SaaS startup', product: 'Dedicated', rating: 5, text: 'The Uranus box handles our Postgres cluster with room to spare. Hardware swaps were handled before we even noticed a disk warning.' },
  { sample: true, name: 'James T.', role: 'Developer', product: 'SSD VPS', rating: 4, text: 'Great value NVMe VPS with a clean control panel. Daily backups saved me after a bad deploy.' },
  { sample: true, name: 'Nina V.', role: 'Photographer', product: 'OwnCloud', rating: 5, text: 'I replaced Dropbox with my own OwnCloud server in Germany. Clients get private galleries and I keep control of my files.' },
];

export const locations = [
  { city: 'Derby', country: 'United Kingdom', code: 'GB', region: 'Europe', lat: 52.92, lon: -1.48, services: ['VPS', 'Windows VPS', 'Dedicated'], latency: 8 },
  { city: 'Leeds', country: 'United Kingdom', code: 'GB', region: 'Europe', lat: 53.8, lon: -1.55, services: ['VPS', 'Windows VPS', 'Dedicated'], latency: 9 },
  { city: 'Amsterdam', country: 'Netherlands', code: 'NL', region: 'Europe', lat: 52.37, lon: 4.9, services: ['VPS', 'Windows VPS', 'Dedicated'], latency: 12 },
  { city: 'Frankfurt', country: 'Germany', code: 'DE', region: 'Europe', lat: 50.11, lon: 8.68, services: ['VPS', 'Dedicated', 'OwnCloud', 'Game'], latency: 14 },
  { city: 'Riga', country: 'Latvia', code: 'LV', region: 'Europe', lat: 56.95, lon: 24.1, services: ['VPS', 'Windows VPS', 'Dedicated'], latency: 31 },
  { city: 'New York', country: 'United States', code: 'US', region: 'North America', lat: 40.71, lon: -74.0, services: ['VPS', 'Windows VPS', 'Dedicated', 'Instant', 'Game'], latency: 72 },
];

export const posts = [
  {
    slug: 'vps-vs-dedicated-server',
    title: 'VPS vs dedicated server: which one do you need?',
    date: '2026-09-18',
    tag: 'Guides',
    excerpt: 'A practical comparison of cost, performance and control to help you choose between a VPS and a dedicated server.',
    body: [
      ['p', 'Choosing between a Virtual Private Server and a dedicated server comes down to three things: how much performance you need, how predictable that performance must be, and how much you want to spend.'],
      ['h2', 'When a VPS is the right choice'],
      ['p', 'A VPS gives you guaranteed vCores, RAM and storage on a shared physical host. It is ideal for websites, WordPress, small databases, development environments and most business applications. Plans start from £8.99 per month and can be upgraded in minutes.'],
      ['h2', 'When to move to dedicated'],
      ['p', 'A dedicated server reserves an entire machine for you. Choose one when you run large databases, virtualisation hosts, high-traffic ecommerce or game servers, or when compliance requires physical isolation.'],
      ['h2', 'A simple rule of thumb'],
      ['p', 'If your VPS regularly runs above 70% CPU or you need more than 32 GB of RAM, it is time to look at dedicated hardware. Our sales team can benchmark your workload for free.'],
    ],
  },
  {
    slug: 'secure-a-new-linux-vps',
    title: '10 steps to secure a new Linux VPS',
    date: '2026-08-27',
    tag: 'Security',
    excerpt: 'Harden your server in the first ten minutes: SSH keys, firewalls, updates and more.',
    body: [
      ['p', 'A fresh VPS is exposed to the internet the moment it boots. These ten steps take ten minutes and block the vast majority of automated attacks.'],
      ['h2', 'The checklist'],
      ['ol', ['Update all packages', 'Create a non-root sudo user', 'Add your SSH key', 'Disable password login', 'Disable root SSH login', 'Enable a firewall (ufw or firewalld)', 'Install fail2ban', 'Enable automatic security updates', 'Set the correct timezone and NTP', 'Schedule snapshot backups']],
      ['p', 'Every RMDHost VPS already includes network-level DDoS protection, so you can focus on hardening the operating system.'],
    ],
  },
  {
    slug: 'self-host-n8n-on-a-vps',
    title: 'How to self-host n8n on an AI VPS',
    date: '2026-07-30',
    tag: 'AI & automation',
    excerpt: 'Run unlimited workflows without per-task pricing by hosting n8n on your own server.',
    body: [
      ['p', 'n8n is an open-source automation platform that connects hundreds of apps. Hosting it yourself removes execution limits and keeps your data private.'],
      ['h2', 'What you need'],
      ['p', 'Any VPS with 2 vCores and 4 GB RAM is enough to start. Install Docker, then run the official n8n container behind a reverse proxy with HTTPS.'],
      ['h2', 'Going further'],
      ['p', 'Pair n8n with a local LLM through Ollama on an AI VPS to build private AI agents that never send your data to third parties.'],
    ],
  },
];

export const kb = [
  { cat: 'Getting started', items: ['Logging in to the client area', 'Ordering your first VPS', 'Connecting to your server over SSH', 'Connecting to Windows VPS with Remote Desktop', 'Reinstalling the operating system'] },
  { cat: 'Billing', items: ['Changing your billing currency', 'Updating your payment method', 'Understanding invoices and VAT', 'Upgrading or downgrading a plan', 'Cancelling a service'] },
  { cat: 'VPS management', items: ['Using the VNC console', 'Creating and restoring snapshots', 'Adding an extra IP address', 'Configuring IPv6', 'Resetting the root password'] },
  { cat: 'Dedicated servers', items: ['Requesting a hardware upgrade', 'Accessing IPMI', 'Replacing a failed disk', 'Setting up software RAID', 'Rescue mode'] },
  { cat: 'Domains & DNS', items: ['Pointing a domain to your server', 'Creating reverse DNS (PTR) records', 'Transferring a domain to RMDHost', 'Managing nameservers'] },
  { cat: 'Security', items: ['Enabling two-factor authentication', 'Reporting abuse', 'How DDoS protection works', 'Hardening SSH'] },
];

export const tutorials = [
  { title: 'Install WordPress with Nginx on Ubuntu 24.04', level: 'Beginner', time: '15 min', tag: 'Web' },
  { title: 'Deploy a Node.js app with PM2 and Nginx', level: 'Intermediate', time: '20 min', tag: 'Web' },
  { title: 'Run Docker and Docker Compose on a VPS', level: 'Beginner', time: '10 min', tag: 'Containers' },
  { title: 'Self-host n8n with HTTPS', level: 'Intermediate', time: '25 min', tag: 'Automation' },
  { title: 'Run Llama locally with Ollama and Open WebUI', level: 'Intermediate', time: '20 min', tag: 'AI' },
  { title: 'Set up a Minecraft server with Pterodactyl', level: 'Intermediate', time: '30 min', tag: 'Gaming' },
  { title: 'Configure a WireGuard VPN', level: 'Intermediate', time: '15 min', tag: 'Networking' },
  { title: 'Install Proxmox VE on a dedicated server', level: 'Advanced', time: '40 min', tag: 'Virtualisation' },
  { title: 'Automate backups to object storage with restic', level: 'Intermediate', time: '20 min', tag: 'Backups' },
];

export const statusServices = [
  { name: 'Client area & billing', group: 'Platform' },
  { name: 'Website', group: 'Platform' },
  { name: 'Support ticket system', group: 'Platform' },
  { name: 'Derby (UK) network', group: 'Data centres' },
  { name: 'Leeds (UK) network', group: 'Data centres' },
  { name: 'Amsterdam (NL) network', group: 'Data centres' },
  { name: 'Frankfurt (DE) network', group: 'Data centres' },
  { name: 'Riga (LV) network', group: 'Data centres' },
  { name: 'New York (US) network', group: 'Data centres' },
  { name: 'VPS provisioning', group: 'Services' },
  { name: 'Backups & snapshots', group: 'Services' },
  { name: 'DDoS mitigation', group: 'Services' },
];

// Customer logos / references. Replace with real customers (with permission).
export const references = [
  { name: 'Northwind Digital', sector: 'Web agency', product: 'Linux VPS' },
  { name: 'Brightline Trading', sector: 'Fintech', product: 'Windows VPS' },
  { name: 'Pixelforge Studios', sector: 'Gaming', product: 'Game Servers' },
  { name: 'Cobalt Analytics', sector: 'SaaS', product: 'Dedicated' },
  { name: 'Harbour Media', sector: 'Publishing', product: 'Cloud VPS' },
  { name: 'Atlas Logistics', sector: 'Logistics', product: 'Dedicated' },
  { name: 'Greenfield Schools', sector: 'Education', product: 'OwnCloud' },
  { name: 'Vertex Labs', sector: 'AI research', product: 'AI VPS' },
];

// Global site settings. Edit this file to change company details, links,
// currencies and navigation. Every page is rebuilt from it by `npm run build`.

import { readFileSync } from 'node:fs';

// Network claims (data centres, SLA, DDoS capacity, port speed): edit
// infrastructure.json to change them on every page at once.
export const infra = JSON.parse(readFileSync(new URL('./infrastructure.json', import.meta.url), 'utf8'));

export const site = {
  name: 'RMDHost',
  domain: 'rmdhost.com',
  url: 'https://rmdhost.com', // canonical URL used for sitemap + SEO tags
  tagline: 'High-performance VPS & dedicated servers',
  email: 'support@rmdhost.com',
  salesEmail: 'sales@rmdhost.com',
  founded: 2010,

  // Client area / billing system (WHMCS or similar). Order buttons fall back
  // to `${clientArea}/cart.php` when a plan has no `order` link of its own.
  clientArea: 'https://my.digitalberg.com',
  get orderFallback() { return `${this.clientArea}/cart.php`; },
  get loginUrl() { return `${this.clientArea}/login`; },
  get domainSearchUrl() { return `${this.clientArea}/cart.php?a=add&domain=register&query=`; },
  get ticketUrl() { return `${this.clientArea}/submitticket.php`; },

  // Prices in the data files are stored in GBP. Other currencies are
  // converted with these rates unless a plan provides its own `usd` price.
  currency: {
    default: 'GBP',
    list: [
      { code: 'GBP', symbol: '£', rate: 1 },
      { code: 'USD', symbol: '$', rate: 1.27 },
    ],
  },

  social: {
    x: 'https://x.com/rmdhost',
    linkedin: 'https://www.linkedin.com/company/rmdhost',
    facebook: 'https://www.facebook.com/rmdhost',
    youtube: 'https://www.youtube.com/@rmdhost',
    github: 'https://github.com/rmdhost',
  },

  stats: [
    { value: 10, suffix: 'K+', label: 'Active clients' },
    { value: 15, suffix: '+', label: 'Years in hosting' },
    { value: 100, suffix: '%', label: 'Network uptime SLA' },
    { value: 40, suffix: '+', label: 'Data centres · owned & leased' },
  ],
};

// Mega menu + mobile navigation
export const nav = [
  { label: 'Pricing', href: '/pricing/' },
  {
    label: 'Products',
    mega: [
      {
        title: 'Virtual servers',
        items: [
          { label: 'Linux VPS', href: '/vps/', desc: 'Root access on SSD-boosted nodes', icon: 'server' },
          { label: 'Windows VPS', href: '/windows-vps/', desc: 'Ryzen + NVMe with Remote Desktop', icon: 'windows' },
          { label: 'SSD VPS (OpenStack)', href: '/ssd-vps/', desc: 'NVMe VPS with free control panel', icon: 'bolt' },
          { label: 'Cloud VPS', href: '/cloud-vps/', desc: 'OpenStack KVM with snapshots', icon: 'cloud' },
          { label: 'Lightsail VPS', href: '/lightsail-vps/', desc: 'Fixed-price cloud VPS bundles', icon: 'cloud', tag: 'New' },
          { label: 'AI VPS', href: '/ai-vps/', desc: 'Servers ready for AI workloads', icon: 'spark', tag: 'Coming Soon' },
          { label: 'MacOS VPS', href: '/macos-vps/', desc: 'Virtual Macs for builds and testing', icon: 'apple', tag: 'Coming Soon' },
        ],
      },
      {
        title: 'Dedicated servers',
        items: [
          { label: 'Dedicated Servers', href: '/dedicated-servers/', desc: 'Bare metal Intel Xeon & Core', icon: 'rack' },
          { label: 'Instant Dedicated USA', href: '/instant-dedicated-servers-usa/', desc: 'Online in minutes, not days', icon: 'rocket' },
          { label: 'MacOS Dedicated', href: '/macos-dedicated-servers/', desc: 'Dedicated Mac hardware', icon: 'apple', tag: 'Coming Soon' },
          { label: 'Game Servers', href: '/game-servers/', desc: 'Anti-DDoS Game protection', icon: 'game' },
        ],
      },
      {
        title: 'Storage',
        items: [
          { label: 'OwnCloud Storage', href: '/owncloud-storage/', desc: 'Private cloud with E2E encryption', icon: 'folder' },
        ],
      },
    ],
    // Promotional copy supplied by RMDHost.
    promo: {
      visual: 'rack',
      eyebrow: 'Dedicated & VPS',
      title: 'Award-Winning Dedicated/VPS Servers',
      text: 'Get award-winning dedicated or VPS hosting designed for speed and simplicity, with free migration help, NVMe SSD options, backups and 24/7 support from in-house server experts.',
      href: '/pricing/',
      cta: 'Compare servers',
    },
  },
  {
    label: 'Company',
    mega: [
      {
        title: 'Company',
        items: [
          { label: 'About us', href: '/about/', desc: 'Who we are and how we work', icon: 'users' },
          { label: 'References', href: '/references/', desc: 'Teams that run on RMDHost', icon: 'star' },
          { label: 'Sustainability', href: '/sustainability/', desc: 'Greener infrastructure', icon: 'leaf' },
        ],
      },
      {
        title: 'Infrastructure',
        items: [
          { label: 'Data centres', href: '/data-centres/', desc: '40+ owned & leased facilities', icon: 'globe' },
          { label: 'DDoS protection', href: '/ddos-protection/', desc: '30 Tbps+ always-on filtering', icon: 'shield' },
          { label: 'Edge servers', href: '/edge-servers/', desc: 'Protected entry points near players', icon: 'bolt' },
          { label: 'Network status', href: '/network-status/', desc: 'Live service health', icon: 'pulse' },
        ],
      },
    ],
    promo: {
      visual: 'map',
      eyebrow: 'Infrastructure',
      title: '40+ data centres, owned & leased',
      text: 'Deploy in core locations such as Derby, Leeds, Amsterdam, Frankfurt, Riga, New York and Tampa – with 30 Tbps+ DDoS protection and a 100% network uptime SLA.',
      href: '/data-centres/',
      cta: 'Explore data centres',
    },
  },
  {
    label: 'Resources',
    mega: [
      {
        title: 'Learn',
        items: [
          { label: 'Knowledge base', href: '/knowledge-base/', desc: 'Answers to common questions', icon: 'book' },
          { label: 'Tutorials', href: '/tutorials/', desc: 'Step-by-step server guides', icon: 'terminal' },
          { label: 'Blog', href: '/blog/', desc: 'News, guides and releases', icon: 'pen' },
        ],
      },
      {
        title: 'Help',
        items: [
          { label: 'Support', href: '/support/', desc: '24/7 expert help', icon: 'headset' },
          { label: 'FAQ', href: '/faq/', desc: 'Billing, servers and more', icon: 'help' },
          { label: 'Network status', href: '/network-status/', desc: 'Live service health', icon: 'pulse' },
        ],
      },
    ],
    promo: {
      visual: 'terminal',
      eyebrow: 'Guides',
      title: 'Deploy, secure and scale your server',
      text: 'Step-by-step tutorials for SSH hardening, Docker, WordPress on Nginx, WireGuard and Proxmox – written by our in-house server team.',
      href: '/tutorials/',
      cta: 'Browse tutorials',
    },
  },
  { label: 'Support', href: '/support/' },
];

export const footer = [
  {
    title: 'Hosting',
    links: [
      ['Linux VPS', '/vps/'],
      ['Windows VPS', '/windows-vps/'],
      ['SSD VPS (OpenStack)', '/ssd-vps/'],
      ['Cloud VPS', '/cloud-vps/'],
      ['Lightsail VPS', '/lightsail-vps/'],
      ['AI VPS', '/ai-vps/'],
      ['MacOS VPS', '/macos-vps/'],
      ['OwnCloud Storage', '/owncloud-storage/'],
    ],
  },
  {
    title: 'Servers',
    links: [
      ['Dedicated Servers', '/dedicated-servers/'],
      ['Instant Dedicated USA', '/instant-dedicated-servers-usa/'],
      ['MacOS Dedicated', '/macos-dedicated-servers/'],
      ['Game Servers', '/game-servers/'],
      ['Pricing', '/pricing/'],
    ],
  },
  {
    title: 'Infrastructure',
    links: [
      ['Data centres', '/data-centres/'],
      ['DDoS protection', '/ddos-protection/'],
      ['Edge servers', '/edge-servers/'],
      ['Network status', '/network-status/'],
      ['Sustainability', '/sustainability/'],
    ],
  },
  {
    title: 'Company',
    links: [
      ['About us', '/about/'],
      ['References', '/references/'],
      ['Blog', '/blog/'],
      ['Privacy policy', '/privacy-policy/'],
      ['Terms of service', '/terms-of-service/'],
      ['Cookie policy', '/cookie-policy/'],
      ['Acceptable use', '/acceptable-use/'],
      ['Fair use', '/fair-use/'],
      ['Service level agreement', '/sla/'],
    ],
  },
  {
    title: 'Support',
    links: [
      ['Support centre', '/support/'],
      ['Knowledge base', '/knowledge-base/'],
      ['Tutorials', '/tutorials/'],
      ['FAQ', '/faq/'],
      ['Client area', 'CLIENT_AREA'],
    ],
  },
];

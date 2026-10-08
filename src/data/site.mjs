// Global site settings. Edit this file to change company details, links,
// currencies and navigation. Every page is rebuilt from it by `npm run build`.

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
  clientArea: 'https://my.rmdhost.com',
  get orderFallback() { return `${this.clientArea}/cart.php`; },
  get loginUrl() { return `${this.clientArea}/clientarea.php`; },
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
    { value: 99.99, suffix: '%', decimals: 2, label: 'Uptime SLA' },
    { value: 6, suffix: '', label: 'Data centre locations' },
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
          { label: 'AI VPS', href: '/ai-vps/', desc: 'Servers ready for AI workloads', icon: 'spark', tag: 'New' },
          { label: 'macOS VPS', href: '/macos-vps/', desc: 'Virtual Macs for builds and testing', icon: 'apple', tag: 'Soon' },
        ],
      },
      {
        title: 'Dedicated servers',
        items: [
          { label: 'Dedicated Servers', href: '/dedicated-servers/', desc: 'Bare metal Intel Xeon & Core', icon: 'rack' },
          { label: 'Instant Dedicated USA', href: '/instant-dedicated-servers-usa/', desc: 'Online in minutes, not days', icon: 'rocket' },
          { label: 'macOS Dedicated', href: '/macos-dedicated-servers/', desc: 'Dedicated Mac hardware', icon: 'apple', tag: 'Soon' },
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
    promo: {
      eyebrow: 'Most popular',
      title: 'Linux VPS from £8.99/mo',
      text: 'Unlimited bandwidth, full root access and no setup fee.',
      href: '/vps/',
      cta: 'Explore VPS',
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
          { label: 'Data centres', href: '/data-centres/', desc: 'Six locations across UK, EU & US', icon: 'globe' },
          { label: 'DDoS protection', href: '/ddos-protection/', desc: 'Edge filtering on every plan', icon: 'shield' },
          { label: 'Network status', href: '/network-status/', desc: 'Live service health', icon: 'pulse' },
        ],
      },
    ],
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
      ['AI VPS', '/ai-vps/'],
      ['macOS VPS', '/macos-vps/'],
      ['OwnCloud Storage', '/owncloud-storage/'],
    ],
  },
  {
    title: 'Servers',
    links: [
      ['Dedicated Servers', '/dedicated-servers/'],
      ['Instant Dedicated USA', '/instant-dedicated-servers-usa/'],
      ['macOS Dedicated', '/macos-dedicated-servers/'],
      ['Game Servers', '/game-servers/'],
      ['Pricing', '/pricing/'],
    ],
  },
  {
    title: 'Infrastructure',
    links: [
      ['Data centres', '/data-centres/'],
      ['DDoS protection', '/ddos-protection/'],
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

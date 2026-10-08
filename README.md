# RMDHost.com website

A fast, static, black-and-white hosting website for RMDHost. The layout, page flow and motion follow the Hostinger.com style (dark glowing hero, promo grid, tabbed product scenes, expanding support cards, pricing cards, dotted world map, review masonry, FAQ and CTA band). All code, artwork and copy are original, and the site uses RMDHost branding only.

- **31 pages**: home, 11 product pages, pricing, data centres, DDoS, network status, about, references, sustainability, FAQ, blog (+3 posts), knowledge base, tutorials, support, privacy, terms, cookie policy and 404.
- **No dependencies**: you only need Node 18 or newer. Nothing to `npm install`.
- **SEO**: a unique title, description, canonical URL and H1 on every page, Open Graph tags, Organization/Product/FAQ/Breadcrumb/BlogPosting JSON-LD, plus `sitemap.xml` and `robots.txt`.
- **Features**: light/dark mode, GBP/USD currency switch, mega menu, domain search, cookie consent (Accept all / Reject all / Manage preferences) and a mobile layout.

## Run locally

```bash
npm run dev        # builds to public/ and serves http://localhost:8080
npm run build      # build only
```

## Deploy (Netlify)

`netlify.toml` is already set up. The build command is `npm run build` and the publish directory is `public`. Connect the repo in Netlify, or drag the `public/` folder into Netlify Drop.

## Where to change things

| What | File |
| --- | --- |
| Company details, client-area URL, currencies and exchange rate, navigation, footer | `src/data/site.mjs` |
| **Servers, specs, prices and order links** | `src/data/products.mjs` |
| FAQs, reviews, locations, blog posts, KB, tutorials, status services | `src/data/content.mjs` |
| Page layouts | `src/pages/*.mjs` |
| Header, footer and cookie banner | `src/lib/layout.mjs` |
| Plan boxes, animated scenes and the map | `src/lib/components.mjs` |
| Styles / animations | `src/static/assets/css/main.css` |
| Interactions | `src/static/assets/js/main.js` |

Run `npm run build` after any change.

### Adding or editing a plan

Each plan box shows **CPU | RAM | Storage | Bandwidth | Location | DDoS | Price | More details | Order now**:

```js
{ id: 'plus-berg', name: 'Plus Berg', price: 10.99, badge: 'Most popular',
  cpu: '2 vCores', ram: '8 GB', storage: '200 GB SSD', bandwidth: 'Unlimited',
  location: 'UK · EU · USA', ddos: 'Included',
  extras: ['1 IPv4', 'No setup fee'],               // "More details"
  order: 'https://my.rmdhost.com/cart.php?a=add&pid=12', // optional
  usd: 13.99 }                                     // optional fixed USD price
```

- Prices are monthly and in **GBP**. USD is converted with the rate in `site.mjs` unless a plan sets `usd`.
- `price: null` shows "Price coming soon" with a **Contact sales** button. The AI VPS, macOS VPS and macOS Dedicated pages use this until final prices are ready.
- Plans without an `order` link go to `${clientArea}/cart.php?plan=<id>`.

## Before going live

1. Set the real **client area URL** in `src/data/site.mjs`. Then add each plan's `order` link (the WHMCS `pid`).
2. Replace the **sample reviews and references** in `src/data/content.mjs` with real customer content and set `sample: false`. While samples remain, they show a "Sample" tag and the build prints a warning.
3. Confirm the specs and prices for **AI VPS, macOS VPS and macOS Dedicated**, which are placeholders.
4. Have a lawyer review the **Privacy policy, Terms and Cookie policy** templates.
5. Connect **analytics** after consent: `main.js` fires a `rmd:consent` event (see the comment at the end of the cookie section).
6. Point the **network status** page at your monitoring provider. It currently shows a static "operational" layout.
7. The contact form uses `mailto:`. Swap it for Netlify Forms or your ticket system if you prefer.

## Regenerating the world map

```bash
curl -sSLo land.json https://cdn.jsdelivr.net/npm/world-atlas@2.0.2/land-110m.json
node scripts/make-map.mjs land.json
```

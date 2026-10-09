# RMDHost.com website

A fast, static hosting website for RMDHost: technical black/white design with square edges and a terminal-green accent. The layout, page flow and motion follow the Hostinger.com style (dark glowing hero, promo grid, tabbed product scenes, expanding support cards, pricing cards, dotted world map, review masonry, FAQ and CTA band). All code, artwork and copy are original, and the site uses RMDHost branding only.

- **31 pages**: home, 11 product pages, pricing, data centres, DDoS, network status, about, references, sustainability, FAQ, blog (+3 posts), knowledge base, tutorials, support, privacy, terms, cookie policy and 404.
- **No dependencies**: you only need Node 18 or newer. Nothing to `npm install`.
- **SEO**: a unique title, description, canonical URL and H1 on every page, Open Graph tags, Organization/Product/FAQ/Breadcrumb/BlogPosting JSON-LD, plus `sitemap.xml` and `robots.txt`.
- **Features**: light/dark mode, GBP/USD currency switch, mega menu, domain search, cookie consent (Accept all / Reject all / Manage preferences) and a mobile layout.

## Photos and videos

All photos and videos in `src/static/assets/media/` were generated for RMDHost: photos with **Nano Banana 2.1**, videos with **Seedance 2.5** (via Higgsfield). Each photo has a full-size and a mobile (`-sm`) WebP. Each video has MP4 and WebM versions plus a `-poster.webp`, and only plays while it's on screen. To swap one, replace the file with the same name. If a photo's dimensions change, update `src/data/media.mjs`.

## Homepage content & WordPress

The homepage hero, offer, offer/review banner, review score/count/link, CTAs and stats live in **`src/data/homepage.json`**. Nothing in that block is hard-coded in templates, and empty fields are simply hidden.

To edit them from WordPress instead:

1. Copy `wordpress/rmdhost-homepage/` into `wp-content/plugins/` and activate **RMDHost Homepage Content**.
2. Go to **Settings → RMDHost Homepage**, fill in the fields and paste your Vercel/Netlify **deploy hook** URL. Saving triggers a rebuild.
3. In the hosting project, set the environment variable `WP_URL=https://your-wordpress-site`.

At build time, the site reads `WP_URL/wp-json/rmdhost/v1/homepage`. Any filled WordPress field overrides the JSON. If WordPress can't be reached, the build falls back to the JSON.

**Review score:** HostAdvice blocks automated access, so the DigitalBerg score and review count start empty. Until a score is entered, the banner shows a neutral icon with a "Read reviews" link rather than stars. When you add the figures, copy them exactly from the HostAdvice page, which is labelled as DigitalBerg reviews.

## Before going live (Phase 1 homepage)

- **AWS Lightsail VPS** (`/aws-lightsail-vps/`): the packages, prices and order links come from DigitalBerg's "Amazon Lightsail VPS" page. Availability, AWS wording and fulfilment still need confirming. The page shows a "To confirm" note (`review` in `products.mjs`) and a trademark disclaimer, and it makes no partnership claim.
- **Mega-menu promo** "Award-Winning Dedicated/VPS Servers" is RMDHost-supplied copy (`src/data/site.mjs`). Keep it only if the award can be referenced.
- **Pricing tabs** show all four DigitalBerg packages per tab with their real cart links. Location and DDoS rows only state what each DigitalBerg page states. Dedicated servers show "Ask sales" for DDoS because the source page makes no DDoS claim.

## Run locally

```bash
npm run dev        # builds to public/ and serves http://localhost:8080
npm run build      # build only
```

## Deploy

- **Vercel** (current preview: rmd2.vercel.app): build command `npm run build`, output directory `public`.
- **Netlify**: `netlify.toml` is already set up with the same settings.

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

## Before going live (other pages)

1. The client area is set to `my.digitalberg.com`, and the account icon goes to `/login`. Linux, Windows, SSD and Cloud VPS, Lightsail and dedicated plans have real cart links. Instant dedicated and game servers still use the generic cart link.
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

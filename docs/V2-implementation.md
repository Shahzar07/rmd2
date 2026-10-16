# RMDHost Version 2 implementation

Implements the supplied Homepage and Pricing V2 brief on a separate branch based on `claude/busy-keller-6wsmcp` at `e5a009956801ef366a11296431a8aba24cee5429`, the head of draft PR #1. The default branch is `claude/gallant-gauss-yk5b9h`. Neither original branch was rewritten or merged. This PR is stacked on PR #1 so the diff contains only V2 work. Merge the earlier work first, then retarget this PR to the default branch if required.

## Requirements mapping

| Brief item | Implementation |
| --- | --- |
| Homepage scope | Existing sections, styling and motion retained. MacOS naming, selected-port stat and map proportions updated. |
| Infrastructure figures | Requested values and evidence gaps are in `src/data/infrastructure.json`. Unverified 40+, 100% network SLA and 30 Tbps claims are held. |
| Map | Natural Earth 50m coastlines retained. Equirectangular viewBox corrected to equal longitude/latitude scale without extra SVG stretching. Six documented city pins only. Responsive world/Europe inset retained. All flat projections have geographic distortion. |
| Pricing banner | Requested headline installed. Intro uses verified processor families and plan-specific SSD/NVMe wording. Gen4, global 30 Tbps and universal setup-free claims await evidence. |
| Protected products | Linux, Windows, SSD, Cloud, Lightsail and Dedicated plan objects, prices and known checkout URLs preserved. Regression test compares them with prior branch. |
| OwnCloud | Four source cart destinations recovered: product IDs 286, 287, 288 and 289. |
| AI VPS | 13 CPU-based SolidVPS packages across Intel, Ryzen and EPYC. No GPU, blanket NVMe or IPv6 claims. |
| MacOS VPS | Rabisu X8, X16 and X32 packages. No invented locations or port speeds. |
| MacOS Dedicated | M1-01, M1-02 and M4-03. Supplier hero says “from $79.95” but lowest plan card is $99.95; actual card price is used. |
| Games | All 22 supplier packages. Unspecified fields require confirmation. Unlimited slots are not represented as unlimited performance. |
| Order links | AI, both Mac ranges and game packages show Coming Soon. No third-party checkout substituted for future RMDHost links. Other missing destinations no longer generate fictitious carts. |
| Game sections | Supplier panel preview, management features, seven game cards and performance/latency/protection explanations. |
| SEO/AEO | Unique titles/descriptions/H1s, 3–5 FAQs per product, product links, descriptive image text. Offer schema includes only priced plans with actual checkout destinations. Combined FAQ IDs remain unique. |
| DDoS page | Original technical content: volumetric traffic, L3/L4, game protocols, L7, host security, mitigation limits and incident reporting. Competitor capacity is not attributed to RMDHost. |
| Policies | Original Terms, Privacy, AUP, Fair Use and SLA drafts. Footer links added. Drafts are noindex and excluded from static sitemap pending approval. Existing cookie page retained. |
| Backend | WordPress plans remain editable. Read-only public catalogue endpoint synchronizes published plans into static builds with WP_URL. Shared generated sections keep static/WordPress content aligned. |

## Supplier evidence checked 15 October 2026

- OwnCloud: https://digitalberg.com/owncloud-storage/
- AI packages: https://www.solidvps.com/ai-ready-vps.php and its linked `assets/js/ai-ready.js` data.
- Mac VPS: https://www.rabisu.com/panel/store/macos-vds
- Mac dedicated: https://www.solidvps.com/macos-dedicated.php
- Games: https://www.rabisu.com/panel/store/game-server
- Map cities: https://digitalberg.com/data-center/
- VPS uptime marketing: https://digitalberg.com/vps/ — not proof of a global RMDHost network SLA.

The OwnCloud page exposes the cart links; automated checkout requests timed out. Link extraction is verified, but purchase completion/current fulfilment is not tested. No order was placed.

Design and structure references reviewed: Rabisu `/rust-server-hosting` and `/game-server`; EUGameHost `/ddos-protection`, `/terms`, `/privacy`, `/sla`, `/fair-use`, `/acceptable-use`, `/edge-servers` and `/rust-server-hosting`. Policy prose is original; competitor contractual promises and network figures were not adopted.

## Currency

New suppliers quote USD. Each plan stores exact supplier USD in `usd`. GBP is an explicitly labelled estimate using the existing 1.27 conversion setting, not a live FX quote. Final currency, tax, resale price and margin need owner confirmation. Original GBP products are unchanged.

## Backend setup

1. Install updated theme and RMDHost Core on staging. Back up before running the existing demo importer: it updates demo records, page content and menus. Retired demo plans become drafts; custom plans without demo keys are preserved.
2. Edit published plans in **RMDHost → Server plans** and assign a product group. Price, USD price, specs and order URL are editable. Blank order URLs show Coming Soon.
3. For static builds set `WP_URL=https://your-wordpress-install`. Configure a hosting deploy hook in **RMDHost → Static build**. `/wp-json/rmdhost/v1/catalogue` exports public package fields only, never customer data or the hook.
4. Plan saves schedule a debounced rebuild through WP-Cron. Low-traffic WordPress sites should use a real cron runner. Manual deployments also refresh the catalogue. Invalid/unavailable CMS responses fall back to local packages.
5. Homepage overrides use the existing optional RMDHost Homepage Content plugin. Static package editing and homepage editing use separate settings in the existing architecture.
6. WordPress policy drafts carry `_rmd_policy_draft`. After final approval, clear that flag and the visible draft note. For static policies, remove `noindex` and the draft note in `src/pages/policies.mjs`.

## Owner confirmations before release

- Evidence for 40+ owned/leased facilities, network SLA terms, 30 Tbps capacity and NVMe Gen4 coverage.
- RMDHost checkout links and resale prices for new packages; stock, location, OS versions and protection coverage.
- Legal entity/address, jurisdiction, actual processors and retention practices, cancellation/refund terms, binding SLA target and credits.
- Permission to use supplier/publisher media; see `docs/media-sources.md`.
- Existing sample reviews/references and a static status display predate this update and remain unverified.

## Verification completed

- `npm run build`: 35 static pages plus WordPress asset/catalogue synchronization.
- `npm test`: eight passing tests, including protected-plan preservation, exact supplier USD values, missing-checkout behavior, schema/HTML/internal-link audits and a local HTTP test of the configured WordPress endpoint.
- PHP 8.3 syntax check: all 112 WordPress PHP files passed.
- Headless Chromium at 1440, 768 and 390 pixels: 24 page/viewport combinations, all product pricing tabs, More Details controls, real mobile-menu currency selection and game images passed without JavaScript errors or horizontal page overflow.
- Desktop/mobile map, pricing and game screenshots inspected. No production order or deployment was performed. A complete native WordPress installation was not available; PHP syntax and endpoint contract checks do not replace staging integration testing.

## QA follow-up · 16 October 2026

Changes made against *RMDHost DevOps Homepage and Pricing V2 QA*:

| QA item | Change |
| --- | --- |
| Statistics boxes | Hero stats now read 40+ · Data centres · owned & leased, 100% · Network uptime SLA, 30 Tbps+ · DDoS protection capacity, 10 Gbit/s · Ports on dedicated servers. |
| "6 data centres" wording | Replaced on the homepage (hero, meta, map heading, FAQ, stats row), mega menu, Data centres page, About page and WordPress defaults. Figures come from `src/data/infrastructure.json`. |
| Map | Redrawn in the Equal Earth (equal-area) projection with curved graticule. Markers show confirmed core sites only: the six DigitalBerg cities plus Tampa (MacOS Dedicated). |
| Pricing banner and meta | Exact requested text; meta description updated. |
| Game control panel | Caption and alt text no longer name the supplier; supplier name and server IP removed from the screenshot. |
| Supported games | Each card links to that game's plan card (`#plan-<id>`) with its lowest price. Benefits cover performance, low latency and DDoS protection. |
| VAT sentence | Homepage line replaced with the checkout-tax wording used on the pricing page. The footer "Prices are listed without VAT" and the "Excl. VAT" plan notes are kept as the tax disclosure. |
| Structured data / ALT text | Coming Soon products now emit `Service` schema instead of an offer-less `Product`; Product schema gains `url`. Game and panel images have descriptive ALT text. |
| AI VPS tag | Menu tag changed from "New" to "Coming Soon" to match the homepage. |
| CS2 | Removed from homepage copy and the project recommender; listed games only. |
| Lightsail vs Cloud VPS | Plans and order links unchanged as instructed; a note on the Lightsail tab and page explains that the bundles share the Cloud VPS platform and checkout. |
| Linux VPS "100% uptime guarantee" | Plans unchanged as instructed; now consistent with the published 100% Network Uptime SLA. |
| DDoS page | Rewritten with 30 Tbps+ capacity, how-it-works, attack types, technical details and protection by product. |
| SLA / AUP | SLA published (indexable) with a 100% network uptime target, credit table, exclusions and claim process. AUP expanded and published. |
| Edge Servers | New `/edge-servers/` page with a `#what` section, technical details, FAQs and locations; added to the menu, footer, sitemap and WordPress importer. |

Owner sign-off still needed: evidence for 40+ facilities, 30 Tbps+ capacity, NVMe Gen4 and the 10 Gbit/s dedicated-port claim (only MacMini M4-03 lists 10 Gbit/s; the other dedicated servers list 1 Gbit/s); the SLA credit percentages, 14-day claim window and exclusions; the AUP crypto-mining rule; and the OwnCloud cart IDs 286–289.

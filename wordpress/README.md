# RMDHost for WordPress

The RMDHost site as a WordPress theme (`themes/rmdhost`) with a companion plugin (`plugins/rmdhost-core`). The theme outputs the same HTML, CSS and JavaScript as the static site, so every page looks and behaves identically. Section heights were checked page by page against the static build at 1440 px and 390 px.

| | Theme only | Theme + RMDHost Core |
|---|---|---|
| All pages, mega menu, currency switch, dark mode, cookie banner | ✓ | ✓ |
| Customizer: hero, offer/review banner, stats, homepage sections, colours, footer | ✓ | ✓ |
| Plans, locations, reviews, FAQs | Bundled catalogue | Editable in **RMDHost** menu |
| Elementor widgets (30), block + patterns, `[rmdhost_section]` shortcode | – | ✓ |
| One-click demo import, AJAX contact form with saved messages | – (mailto form) | ✓ |

## Install

1. Run `npm run package:wp` (or zip the two folders yourself). You get `dist/rmdhost.zip` and `dist/rmdhost-core.zip`.
2. **Appearance → Themes → Add New → Upload** `rmdhost.zip`, then activate it.
3. **Plugins → Add New → Upload** `rmdhost-core.zip`, then activate it. Elementor and WooCommerce are optional; the theme suggests them in a dismissible notice.
4. **RMDHost → Welcome & import → Import demo content.** This creates:
   - 28 pages with the right templates (homepage, 12 product pages, pricing, data centres, DDoS, network status, about, references, sustainability, FAQ, knowledge base, tutorials, support, blog and the three legal pages)
   - the mega menu with icons, tags and promo panels; the footer columns; the legal menu
   - 3 blog posts, 41 server plans, 6 locations, 6 sample reviews and 44 FAQs
   - optionally, WooCommerce products for every plan (the store currency is set to GBP if the store has no orders)
   - optionally, an Elementor homepage built from the RMDHost widgets

   Running the import again updates the demo items rather than duplicating them. It never deletes content you made yourself.

The importer also runs from WP-CLI: `wp eval 'RMDHost_Core\Importer::run();'`.

## Where to edit what

| What | Where |
|---|---|
| Hero text, offer, CTAs, offer/review banner, rating, stats | Customize → RMDHost Theme → Hero / Offer banner / Hero stats |
| Show, hide or reorder homepage sections | Customize → RMDHost Theme → Homepage sections (or build the homepage in Elementor or with blocks) |
| Signal colour, header buttons, currency, USD rate, client-area links | Customize → RMDHost Theme |
| Footer text, payment badges, social links, cookie banner | Customize → RMDHost Theme → Footer / Cookies |
| Plans, prices, specs, order links | RMDHost → Server plans (assign a **Product group**) |
| Map pins | RMDHost → Locations (lat/lon plus label offset) |
| Reviews | RMDHost → Reviews (untick "Sample" for real reviews) |
| FAQs | RMDHost → FAQs (group "general" = homepage) |
| Contact form messages | RMDHost → Messages (also emailed to the sales address) |
| Mega menu | Appearance → Menus. Depth 1 = top item (promo panel fields), depth 2 = column, depth 3 = link (icon, tag, description) |
| Hero on any page | "RMDHost page settings" box: hide hero, title, eyebrow, intro, animated visual, transparent header |
| Product landing page | Page template **Server product** + choose the product in "RMDHost page settings" |

If you don't add any items of a content type (for example, no Locations), the theme keeps using its bundled list for that type.

## Page templates

`Server product`, `Pricing (all plans)`, `Data centres`, `Network status`, `Support & contact`, `FAQ (all products)`, `References`, `Knowledge base`, `Tutorials`, `Full width (sections)` and `Blank canvas (header + footer)`. Text you write in the editor appears inside these templates. Pages built with Elementor render edge to edge.

## Page builders

- **Elementor:** the **RMDHost** widget category contains one widget per section, from the homepage hero to the product plans and the data-centre map. Every text, list, link, photo and repeater is a control, with a vertical padding and background control in the Style tab. Widgets render the theme's template parts, so they look exactly like the theme pages, including in the editor preview. With Elementor Pro, the header and footer theme locations can be replaced.
- **Block editor:** the **RMDHost section** block. Pick a section and edit its fields in the sidebar. The preview is the real section, rendered in an isolated frame with the theme CSS and JS. Block patterns: About, DDoS, Sustainability, Product landing and Full homepage.
- **Any builder:** `[rmdhost_section name="plans" group="vps"]`, `[rmdhost_section name="pricing" groups="vps,dedicated-servers" limit="3"]`.

## WooCommerce

- The shop, product, cart, checkout and account pages are styled with square corners, black buttons and the theme's type scale. The header shows a live cart count.
- Products get a **Server plan (RMDHost)** box: CPU, RAM, storage, bandwidth, location, DDoS, badge and "more details". These specs show on the product page.
- **Customize → RMDHost Theme → Store → Plan boxes use: WooCommerce products** builds every plan box on the site from products in the category whose slug matches the product group (for example `vps`). In this mode, "Order now" goes straight to checkout.

## SEO

The theme outputs a meta description, Open Graph and Twitter tags, and JSON-LD (Organization, WebSite, Product/AggregateOffer on product pages, FAQPage, BlogPosting and BreadcrumbList). This output switches off automatically when Yoast, Rank Math, SEOPress, AIOSEO or The SEO Framework is active. On a fresh site, the importer uses the static site's URLs (`/vps/`, `/blog/post-name/`).

## For developers

- `npm run build` builds the static site **and** syncs the theme: CSS/JS/media are copied, and the icons, animated scenes, world map and `inc/data/catalog.json` are generated from `src/`. Edit `src/`, not the generated files (`inc/generated/`, `template-parts/scenes/`).
- Sections live in `template-parts/sections/{name}.php`. Render one with `rmdhost_section( 'pricing', array( 'limit' => 3 ) )`. Defaults are in `inc/defaults.php`, and the editable fields used by Elementor and the block editor are in `plugins/rmdhost-core/includes/class-schema.php`.
- Filters: `rmdhost/groups`, `rmdhost/plans`, `rmdhost/locations`, `rmdhost/reviews`, `rmdhost/faqs`, `rmdhost/kb`, `rmdhost/tutorials`, `rmdhost/references`, `rmdhost/stats`, `rmdhost/status_services`, `rmdhost/default_nav`, `rmdhost/default_footer`, `rmdhost/sections`, `rmdhost/section_defaults`, `rmdhost/breadcrumbs`, `rmdhost/transparent_header`, `rmdhost/fonts_url` (return `''` to self-host fonts), `rmdhost/content_container_class`, `rmdhost_core/contact_recipient`. Action: `rmdhost/header_actions`.
- JavaScript: `window.RMDMount(element)` binds tabs, carousels, counters and the other interactive pieces inside markup added after page load. Elementor's editor calls it automatically.
- Coding standards: `phpcs` with `wordpress/phpcs.xml.dist` (WordPress-Extra + Docs) passes with no errors or warnings.
- Translations: `themes/rmdhost/languages/rmdhost.pot` and `plugins/rmdhost-core/languages/rmdhost-core.pot` (`wp i18n make-pot`).

Tested with WordPress 7.1, PHP 8.3, WooCommerce 11.2 and Elementor 4.3.

## Notes

- The "To confirm" note on the AWS Lightsail page is shown only to logged-in editors in WordPress.
- Reviews and references are marked "Sample". Replace them with real, permitted reviews before launch.
- The bundled photos and videos were made for RMDHost. Replace them before redistributing the theme.

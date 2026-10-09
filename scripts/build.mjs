// Static site generator: renders every page in src/pages to public/.
//   npm run build   → public/
//   npm run dev     → build + local server on http://localhost:8080

import { mkdirSync, writeFileSync, cpSync, rmSync, existsSync } from 'node:fs';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';
import { site } from '../src/data/site.mjs';
import { reviews, references } from '../src/data/content.mjs';
import { document } from '../src/lib/layout.mjs';
import home from '../src/pages/home.mjs';
import productPages from '../src/pages/product.mjs';
import otherPages from '../src/pages/pages.mjs';
import { loadHomepage } from '../src/lib/cms.mjs';

const root = join(dirname(fileURLToPath(import.meta.url)), '..');
const out = join(root, 'public');

if (existsSync(out)) rmSync(out, { recursive: true });
mkdirSync(out, { recursive: true });
cpSync(join(root, 'src/static'), out, { recursive: true });

const ctx = { home: await loadHomepage() };
const pages = [home, ...productPages, ...otherPages];
for (const page of pages) {
  const html = document({ ...page, body: page.body(ctx) });
  const file = page.file ? join(out, page.file) : join(out, page.path, 'index.html');
  mkdirSync(dirname(file), { recursive: true });
  writeFileSync(file, html);
}

const today = new Date().toISOString().slice(0, 10);
const indexable = pages.filter((p) => !p.noindex);
writeFileSync(join(out, 'sitemap.xml'), `<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
${indexable.map((p) => `  <url><loc>${site.url}${p.path}</loc><lastmod>${today}</lastmod><priority>${p.path === '/' ? '1.0' : p.path.split('/').length > 3 ? '0.6' : '0.8'}</priority></url>`).join('\n')}
</urlset>
`);
writeFileSync(join(out, 'robots.txt'), `User-agent: *\nAllow: /\n\nSitemap: ${site.url}/sitemap.xml\n`);

console.log(`Built ${pages.length} pages → public/`);
if (reviews.some((r) => r.sample) || references.length) {
  console.warn('⚠  Sample reviews/references are still in src/data/content.mjs – replace them with real ones before launch.');
}

// Homepage content: src/data/homepage.json, optionally overridden by the
// WordPress plugin in wordpress/rmdhost-homepage (Settings → RMDHost Homepage).
// Set WP_URL (e.g. https://cms.rmdhost.com) in the build environment to enable.

import { readFileSync } from 'node:fs';

const local = JSON.parse(readFileSync(new URL('../data/homepage.json', import.meta.url), 'utf8'));

const isObj = (v) => v && typeof v === 'object' && !Array.isArray(v);
// Only non-empty WordPress values replace the local defaults.
function merge(base, over) {
  if (Array.isArray(over)) return over.length ? over : base;
  if (!isObj(base) || !isObj(over)) return over === '' || over == null ? base : over;
  const out = { ...base };
  for (const [k, v] of Object.entries(over)) out[k] = k in base ? merge(base[k], v) : v;
  return out;
}

export async function loadHomepage() {
  const wp = process.env.WP_URL;
  if (!wp) return local;
  try {
    const res = await fetch(`${wp.replace(/\/$/, '')}/wp-json/rmdhost/v1/homepage`, { signal: AbortSignal.timeout(8000) });
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    const remote = await res.json();
    console.log('Homepage content loaded from WordPress');
    return merge(local, remote);
  } catch (e) {
    console.warn(`⚠  WordPress content unavailable (${e.message}) – using src/data/homepage.json`);
    return local;
  }
}

// The public catalogue contains published plan fields only, never account data.
// Static builds fetch it once; missing WP_URL or an unavailable CMS uses the repo.
export async function loadCatalogue() {
  if (!process.env.WP_URL) return null;
  try {
    const url = `${process.env.WP_URL.replace(/\/$/, '')}/wp-json/rmdhost/v1/catalogue`;
    const res = await fetch(url, { signal: AbortSignal.timeout(8000) });
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    const value = await res.json();
    if (!Array.isArray(value.products)) throw new Error('Invalid catalogue response');
    return value;
  } catch (error) {
    console.warn(`WordPress catalogue unavailable (${error.message}); using local packages.`);
    return null;
  }
}

export function mergeCatalogue(localProducts, remote) {
  if (!remote) return localProducts;
  const textFields = ['id', 'name', 'cpu', 'ram', 'storage', 'bandwidth', 'location', 'ddos', 'badge', 'note'];
  const cleanText = (value) => String(value ?? '').replace(/<[^>]*>/g, '').slice(0, 1500);
  const validPrice = (value) => value == null || value === '' || (Number.isFinite(Number(value)) && Number(value) >= 0);
  try {
    return localProducts.map((product) => {
      const edited = remote.products.find((p) => p.slug === product.slug);
      if (!edited) return product;
      if (!Array.isArray(edited.plans) || edited.plans.length > 200) throw new Error(`Invalid plans: ${product.slug}`);
      const ids = new Set();
      const plans = edited.plans.map((plan) => {
        if (!plan.id || !plan.name || ids.has(plan.id) || !validPrice(plan.price) || !validPrice(plan.usd)) throw new Error('Invalid plan');
        ids.add(plan.id);
        const out = Object.fromEntries(textFields.map((key) => [key, cleanText(plan[key])]));
        if (!/^[a-z0-9_-]+$/i.test(out.id)) throw new Error('Invalid plan ID');
        out.price = plan.price == null || plan.price === '' ? null : Number(plan.price);
        if (plan.usd !== '' && plan.usd != null) out.usd = Number(plan.usd);
        out.order = /^https?:\/\//.test(plan.order || '') ? new URL(plan.order).href : '';
        out.extras = (Array.isArray(plan.extras) ? plan.extras : String(plan.extras || '').split('\n')).filter(Boolean).map(cleanText);
        return out;
      });
      const prices = plans.map((p) => p.price).filter((p) => p != null);
      return { ...product, plans, from: prices.length ? Math.min(...prices) : null };
    });
  } catch (error) {
    console.warn(`Invalid WordPress catalogue (${error.message}); using local packages.`);
    return localProducts;
  }
}

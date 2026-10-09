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

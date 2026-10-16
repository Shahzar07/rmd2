// Equal Earth projection (Šavrič, Patterson & Jenny, 2018). An equal-area
// world projection: countries keep their true relative size, so Europe,
// Africa and the Americas are drawn in proportion instead of being stretched
// towards the poles like a plain lon/lat grid.
//
// Shared by scripts/make-map.mjs (dot grid) and src/lib/components.mjs
// (markers, links, graticule). The PHP theme mirrors it in rmdhost_lonlat().

const A1 = 1.340264, A2 = -0.081106, A3 = 0.000893, A4 = 0.003796;
const M = Math.sqrt(3) / 2;
const RAD = Math.PI / 180;

// lon/lat in degrees → unit coordinates (x right, y up).
export function project(lon, lat) {
  const t = Math.asin(M * Math.sin(lat * RAD));
  const t2 = t * t, t6 = t2 * t2 * t2;
  const x = (2 * Math.sqrt(3) * lon * RAD * Math.cos(t)) / (3 * (9 * A4 * t6 * t2 + 7 * A3 * t6 + 3 * A2 * t2 + A1));
  const y = t * (A1 + A2 * t2 + t6 * (A3 + A4 * t2));
  return [x, y];
}

// Unit coordinates → lon/lat in degrees, or null outside the globe.
export function invert(x, y) {
  let t = y;
  for (let i = 0; i < 12; i++) {
    const t2 = t * t, t6 = t2 * t2 * t2;
    const fy = t * (A1 + A2 * t2 + t6 * (A3 + A4 * t2)) - y;
    const fpy = A1 + 3 * A2 * t2 + t6 * (7 * A3 + 9 * A4 * t2);
    const d = fy / fpy;
    t -= d;
    if (Math.abs(d) < 1e-12) break;
  }
  const t2 = t * t, t6 = t2 * t2 * t2;
  const s = Math.sin(t) / M;
  if (Math.abs(s) > 1) return null;
  const lon = (3 * x * (A1 + 3 * A2 * t2 + t6 * (7 * A3 + 9 * A4 * t2))) / (2 * Math.sqrt(3) * Math.cos(t)) / RAD;
  if (Math.abs(lon) > 180) return null;
  return [lon, Math.asin(s) / RAD];
}

// Half-width of the projected world at the equator (lon = ±180°).
export const X_MAX = project(180, 0)[0];

// Map units for a map `w` wide, cropped to latTop…latBottom.
export function frame(w, latTop, latBottom) {
  const k = w / (2 * X_MAX);
  const top = project(0, latTop)[1];
  return { k, y0: k * top, h: k * (top - project(0, latBottom)[1]) };
}

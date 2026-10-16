import { sectionHead, money } from './components.mjs';
import { icon } from './icons.mjs';
import { bySlug } from '../data/products.mjs';
import { infra } from '../data/site.mjs';

// [game, image, alt text, plan ID or ID prefix]. Each card links to the
// game's plan card(s) in the pricing table above.
const games = [
  ['Minecraft', 'minecraft.webp', 'Minecraft game server hosting – blocky landscape artwork', 'minecraft'],
  ['Rust', 'rust.jpg', 'Rust game server hosting – survival game key art', 'rust'],
  ['7 Days to Die', '7-days-to-die.jpg', '7 Days to Die game server hosting – zombie survival artwork', '7-days-to-die'],
  ['Conan Exiles', 'conan-exiles.jpg', 'Conan Exiles game server hosting – barbarian survival artwork', 'conan-exiles'],
  ['ARK: Survival Evolved', 'ark.jpg', 'ARK: Survival Evolved game server hosting – dinosaur survival artwork', 'ark-survival-evolved'],
  ['Garry’s Mod', 'garrys-mod.jpg', 'Garry’s Mod game server hosting – sandbox game artwork', 'garry-s-mod'],
  ['Unturned', 'unturned.jpg', 'Unturned game server hosting – zombie survival artwork', 'unturned'],
];

const plansFor = (key) => (bySlug['game-servers']?.plans || []).filter((p) => p.id === key || p.id.startsWith(`${key}-`));

function gameCard([name, image, alt, key]) {
  const plans = plansFor(key);
  const cheapest = plans.filter((p) => p.price != null).sort((a, b) => a.price - b.price)[0];
  const href = plans.length ? `#plan-${plans[0].id}` : `/support/?topic=${encodeURIComponent(`${name} server`)}`;
  const label = plans.length ? `View ${plans.length > 1 ? `${plans.length} plans` : 'plan'}${cheapest ? ` · from ${money(cheapest.price, { usd: cheapest.usd, per: '' })}` : ''}` : 'Ask about hosting';
  return `<a class="game-card" data-reveal href="${href}"><img src="/assets/img/games/${image}" width="460" height="215" loading="lazy" alt="${alt}"><div><h3>${name}</h3><span><span>${label}</span>${icon('arrowUpRight')}</span></div></a>`;
}

export const gameSections = () => `
<section class="section section-soft" id="control-panel"><div class="container">
  ${sectionHead({ eyebrow: 'Manage your world', title: 'Powerful Game Control Panel', text: 'Console, files and player tools in one place. Available tools vary by game and package; confirm your configuration before ordering.' })}
  <div class="game-panel-layout">
    <figure class="game-panel-shot" data-reveal><img src="/assets/img/games/panel-console.webp" width="1218" height="738" loading="lazy" alt="Game control panel showing the live server console, CPU and memory usage"><figcaption>Game control panel preview. Available tools vary by game.</figcaption></figure>
    <div class="game-panel-features">
      ${[['terminal','Console access','Read logs and manage server commands.'],['folder','File management','Organise worlds, configuration files and uploads.'],['wrench','Mods & versions','Manage supported mods and game versions.'],['users','Player tools','Manage access and moderate your community.']].map(([ic,t,d]) => `<article data-reveal>${icon(ic)}<div><h3 class="h4">${t}</h3><p class="muted">${d}</p></div></article>`).join('')}
    </div>
  </div>
</div></section>
<section class="section" id="supported-games"><div class="container">
  ${sectionHead({ eyebrow: 'Find your next world', title: 'Supported Game Server Types', text: 'Pick your game to jump to its plans. Not sure which size you need? Tell us your game version, mods and expected player count and we’ll help.' })}
  <div class="game-cards">${games.map(gameCard).join('')}</div>
  <div class="game-benefits">${[['cpu','Performance','Game-tuned CPU and RAM for your world, mods and player count, with fast storage for quick saves and restarts.'],['globe','Low latency','Host close to your players and keep ping low. Add an <a href="/edge-servers/">edge server</a> to reach players in other regions.'],['shield','DDoS protection',`Anti-DDoS on every game package, backed by ${infra.ddosCapacity.value} of <a href="/ddos-protection/">mitigation capacity</a> with game-aware filtering.`]].map(([ic,t,d])=>`<article>${icon(ic)}<h3>${t}</h3><p class="muted">${d}</p></article>`).join('')}</div>
</div></section>`;

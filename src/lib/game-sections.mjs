import { sectionHead } from './components.mjs';
import { icon } from './icons.mjs';

const games = [
  ['Minecraft', 'minecraft.webp', 'Minecraft block and landscape artwork', 'minecraft-gold'],
  ['Rust', 'rust.jpg', 'Rust survival game artwork', 'rust'],
  ['7 Days to Die', '7-days-to-die.jpg', '7 Days to Die survival game artwork', '7-days-to-die'],
  ['Conan Exiles', 'conan-exiles.jpg', 'Conan Exiles game artwork', 'conan-exiles'],
  ['ARK: Survival Evolved', 'ark.jpg', 'ARK Survival Evolved game artwork', 'ark-survival-evolved'],
  ["Garry’s Mod", 'garrys-mod.jpg', 'Garrys Mod sandbox game artwork', 'garry-s-mod'],
  ['Unturned', 'unturned.jpg', 'Unturned survival game artwork', 'unturned'],
];

export const gameSections = () => `
<section class="section section-soft" id="control-panel"><div class="container">
  ${sectionHead({ eyebrow: 'Manage your world', title: 'Powerful Game Control Panel', text: 'Console, files and player tools in one place. Available tools vary by game and package; confirm your configuration before ordering.' })}
  <div class="game-panel-layout">
    <figure class="game-panel-shot" data-reveal><img src="/assets/img/games/panel-console.webp" width="1218" height="738" loading="lazy" alt="Supplier game control panel showing server console and resource usage"><figcaption>Supplier interface preview · Rabisu. The delivered panel may vary by game.</figcaption></figure>
    <div class="game-panel-features">
      ${[['terminal','Console access','Read logs and manage server commands.'],['folder','File management','Organise worlds, configuration files and uploads.'],['wrench','Mods & versions','Manage supported mods and game versions.'],['users','Player tools','Manage access and moderate your community.']].map(([ic,t,d]) => `<article data-reveal>${icon(ic)}<div><h3 class="h4">${t}</h3><p class="muted">${d}</p></div></article>`).join('')}
    </div>
  </div>
</div></section>
<section class="section" id="supported-games"><div class="container">
  ${sectionHead({ eyebrow: 'Find your next world', title: 'Supported Game Server Types', text: 'Explore the listed packages. Tell us your game version, region, mods and expected player count so we can help size your server.' })}
  <div class="game-cards">${games.map(([name,image,alt,id])=>`<a class="game-card" data-reveal href="/support/?topic=${encodeURIComponent(name+' server')}"><img src="/assets/img/games/${image}" width="460" height="215" loading="lazy" alt="${alt}"><div><h3>${name}</h3><span>Ask about hosting ${icon('arrowUpRight')}</span></div></a>`).join('')}</div>
  <div class="game-benefits">${[['cpu','Performance','Choose CPU and RAM for your world and mods.'],['globe','Low-latency planning','Select a region close to your players; test connectivity first.'],['shield','DDoS protection','Available on listed packages. Confirm game-specific coverage.']].map(([ic,t,d])=>`<article>${icon(ic)}<h3>${t}</h3><p class="muted">${d}</p></article>`).join('')}</div>
</div></section>`;

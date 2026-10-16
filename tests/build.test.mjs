import { test } from 'node:test';
import assert from 'node:assert/strict';
import { readdirSync, readFileSync, existsSync } from 'node:fs';
import { join } from 'node:path';
function files(path) {return readdirSync(path,{withFileTypes:true}).flatMap(e=>e.isDirectory()?files(join(path,e.name)):[join(path,e.name)]);}
test('generated pages have one H1, unique IDs, valid schemas and real internal targets',()=>{
 const pages=files('public').filter(p=>p.endsWith('.html'));assert.equal(pages.length,36);
 for(const file of pages){
  const html=readFileSync(file,'utf8');assert.equal((html.match(/<h1\b/g)||[]).length,1,file);
  const ids=[...html.matchAll(/\bid="([^"]+)"/g)].map(m=>m[1]);assert.equal(ids.length,new Set(ids).size,file);
  assert.ok(!html.includes('Prices exclude VAT and are billed monthly.'),file);assert.ok(!/href=""/.test(html),file);
  for(const [,url]of html.matchAll(/(?:href|src)="(\/(?!\/)[^"]*)"/g)){const path=url.split(/[?#]/)[0];assert.ok(existsSync(join('public',path)),`${file}: ${path}`);}
  for(const [,schema]of html.matchAll(/<script type="application\/ld\+json">([\s\S]*?)<\/script>/g))assert.doesNotThrow(()=>JSON.parse(schema),file);
 }
});

test('V2 QA items: stats, wording, banner, game links and supplier names',()=>{
 const read=(p)=>readFileSync(join('public',p),'utf8');
 const home=read('index.html');
 for(const [v,l] of [['40+','Data centres · owned &amp; leased'],['100%','Network uptime SLA'],['30 Tbps+','DDoS protection capacity'],['10 Gbit/s','Ports on dedicated servers']])assert.ok(home.includes(`<li><b>${v}</b><span>${l}</span></li>`),v);
 assert.ok(!home.includes('Prices exclude VAT'));assert.ok(!home.includes('CS2'));
 assert.ok(read('pricing/index.html').includes('Intel, AMD Ryzen™ and AMD EPYC processors, NVMe Gen4 SSD storage, and free 30 Tbps+ DDoS protection. Flexible monthly billing, no VPS setup fees, and 24/7 support.'));
 const game=read('game-servers/index.html');
 const links=[...game.matchAll(/class="game-card"[^>]*href="#(plan-[^"]+)"/g)].map(m=>m[1]);
 assert.equal(links.length,7);for(const id of links)assert.ok(game.includes(`id="${id}"`),id);
 for(const file of files('public').filter(p=>p.endsWith('.html'))){
  const html=readFileSync(file,'utf8');
  assert.ok(!/Rabisu|SolidVPS|supplier interface/i.test(html),file);
  assert.ok(!/\b(6|six) data cent|six locations|99\.99%|no confirmed 30 Tbps/i.test(html),file);
  assert.ok(!html.includes('${'),file);
 }
 assert.ok(read('edge-servers/index.html').includes('id="what"'));
 assert.ok(home.includes('href="/edge-servers/"'));
});

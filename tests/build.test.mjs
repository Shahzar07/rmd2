import { test } from 'node:test';
import assert from 'node:assert/strict';
import { readdirSync, readFileSync, existsSync } from 'node:fs';
import { join } from 'node:path';
function files(path) {return readdirSync(path,{withFileTypes:true}).flatMap(e=>e.isDirectory()?files(join(path,e.name)):[join(path,e.name)]);}
test('generated pages have one H1, unique IDs, valid schemas and real internal targets',()=>{
 const pages=files('public').filter(p=>p.endsWith('.html'));assert.equal(pages.length,35);
 for(const file of pages){
  const html=readFileSync(file,'utf8');assert.equal((html.match(/<h1\b/g)||[]).length,1,file);
  const ids=[...html.matchAll(/\bid="([^"]+)"/g)].map(m=>m[1]);assert.equal(ids.length,new Set(ids).size,file);
  assert.ok(!html.includes('Prices exclude VAT and are billed monthly.'),file);assert.ok(!/href=""/.test(html),file);
  for(const [,url]of html.matchAll(/(?:href|src)="(\/(?!\/)[^"]*)"/g)){const path=url.split(/[?#]/)[0];assert.ok(existsSync(join('public',path)),`${file}: ${path}`);}
  for(const [,schema]of html.matchAll(/<script type="application\/ld\+json">([\s\S]*?)<\/script>/g))assert.doesNotThrow(()=>JSON.parse(schema),file);
 }
});

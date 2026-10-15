import { test } from 'node:test';
import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';
import { products, bySlug } from '../src/data/products.mjs';
import { mergeCatalogue } from '../src/lib/cms.mjs';
import { planCard, productSchema, orderUrl } from '../src/lib/components.mjs';

const baseline = JSON.parse(readFileSync(new URL('./fixtures/protected-plans.json', import.meta.url), 'utf8'));
test('protected product plans retain every price, specification and checkout URL', () => {
 for(const slug of ['vps','windows-vps','ssd-vps','cloud-vps','lightsail-vps','dedicated-servers']) {
  const before=baseline.products.find(p=>p.slug===slug);
  assert.deepEqual(JSON.parse(JSON.stringify(bySlug[slug].plans)),before.plans,slug);
  if(before.table) assert.deepEqual(bySlug[slug].table,before.table);
 }
});
test('both catalogues use verified supplier packages and OwnCloud cart IDs',()=>{
 assert.equal(bySlug['ai-vps'].plans.length,13);
 assert.equal(bySlug['macos-vps'].plans.length,3);
 assert.equal(bySlug['macos-dedicated-servers'].plans.length,3);
 assert.equal(bySlug['game-servers'].plans.length,22);
 bySlug['owncloud-storage'].plans.forEach((p,i)=>assert.equal(p.order,`https://my.digitalberg.com/cart.php?a=add&pid=${286+i}`));
 for(const slug of ['ai-vps','macos-vps','macos-dedicated-servers','game-servers'])for(const p of bySlug[slug].plans){
  assert.ok(p.usd>0);assert.equal(p.price,Math.round(p.usd/1.27*100)/100);
  assert.match(p.note,/GBP estimate/);assert.equal(orderUrl(p),'');assert.match(planCard(p),/Coming Soon/);assert.ok(!planCard(p).includes('>Order now<'));
 }
});
test('no invented checkout URL or purchasable Offer for unavailable plans',()=>{
 assert.equal(orderUrl({id:'missing',price:9}),'');
 assert.equal(orderUrl({order:'javascript:alert(1)'}),'');
 assert.ok(!productSchema(bySlug['ai-vps']).offers);
 const offer=productSchema(bySlug.vps).offers;
 assert.equal(offer.lowPrice,8.99);assert.equal(offer.offerCount,4);
});
test('backend updates propagate price, specs, currency and cleared checkout links',()=>{
 const remote={products:[{slug:'vps',plans:[{...bySlug.vps.plans[0],price:0,usd:0,cpu:'6 vCPU',order:''}]}]};
 const merged=mergeCatalogue(products,remote);
 const vps=merged.find(p=>p.slug==='vps');
 assert.equal(vps.from,0);assert.equal(vps.plans[0].price,0);assert.equal(vps.plans[0].cpu,'6 vCPU');assert.equal(vps.plans[0].order,'');
 assert.deepEqual(bySlug.vps.plans,baseline.products.find(p=>p.slug==='vps').plans);
 const hidden=mergeCatalogue(products,{products:[{slug:'vps',plans:[]}]});
 assert.equal(hidden.find(p=>p.slug==='vps').plans.length,0);
});
test('invalid backend data falls back without partly mutating the catalogue',()=>{
 const bad={products:[{slug:'vps',plans:[{id:'invalid',name:'Invalid',price:-1}]}]};
 assert.equal(mergeCatalogue(products,bad),products);
 const bad2={products:[{slug:'vps',plans:[{...bySlug.vps.plans[0],usd:'NaN'}]}]};
 assert.equal(mergeCatalogue(products,bad2),products);
});
test('new product pages have 3–5 FAQs and policy drafts are excluded from sitemap',()=>{
 for(const p of products) assert.ok(p.faqs.length>=3&&p.faqs.length<=5,p.slug);
 const sitemap=readFileSync('public/sitemap.xml','utf8');
 for(const slug of ['terms-of-service','privacy-policy','acceptable-use','fair-use','sla']){
  assert.ok(!sitemap.includes(`/${slug}/`));assert.match(readFileSync(`public/${slug}/index.html`,'utf8'),/noindex/);
 }
});

test('configured WordPress endpoint is consumed by the real product module', async () => {
 const {createServer}=await import('node:http');
 const {spawn}=await import('node:child_process');
 const remote={products:[{slug:'vps',plans:[{...bySlug.vps.plans[0],name:'Edited backend plan',price:12.34,order:'https://billing.example.test/cart?pid=42'}]}]};
 const server=createServer((req,res)=>{
  assert.equal(req.url,'/wordpress/wp-json/rmdhost/v1/catalogue');
  res.writeHead(200,{'Content-Type':'application/json'});res.end(JSON.stringify(remote));
 });
 await new Promise(resolve=>server.listen(0,'127.0.0.1',resolve));
 try {
  const output=await new Promise((resolve,reject)=>{
   const child=spawn(process.execPath,['--input-type=module','-e',"import{bySlug}from'./src/data/products.mjs';console.log(JSON.stringify({price:bySlug.vps.from,name:bySlug.vps.plans[0].name,order:bySlug.vps.plans[0].order}))"],{env:{...process.env,WP_URL:`http://127.0.0.1:${server.address().port}/wordpress`}});
   let stdout='',stderr='';child.stdout.on('data',c=>stdout+=c);child.stderr.on('data',c=>stderr+=c);child.on('error',reject);child.on('close',code=>code?reject(new Error(stderr)):resolve(stdout));
  });
  assert.deepEqual(JSON.parse(output),{price:12.34,name:'Edited backend plan',order:'https://billing.example.test/cart?pid=42'});
 } finally { await new Promise(resolve=>server.close(resolve)); }
});

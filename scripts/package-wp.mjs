// Packages the WordPress theme and companion plugin as installable zips in dist/.
// Run `npm run build` first so the theme assets and catalogue are up to date.
import { execFileSync } from 'node:child_process';
import { mkdirSync, rmSync, existsSync } from 'node:fs';
import { join, dirname } from 'node:path';
import { fileURLToPath } from 'node:url';

const root = join(dirname(fileURLToPath(import.meta.url)), '..');
const dist = join(root, 'dist');
mkdirSync(dist, { recursive: true });
for (const [dir, name] of [['wordpress/themes', 'rmdhost'], ['wordpress/plugins', 'rmdhost-core']]) {
  const out = join(dist, `${name}.zip`);
  if (existsSync(out)) rmSync(out);
  execFileSync('zip', ['-rq', out, name, '-x', '*.DS_Store', '*/node_modules/*'], { cwd: join(root, dir), stdio: 'inherit' });
  console.log(`dist/${name}.zip`);
}

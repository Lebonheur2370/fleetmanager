// Copie les polices FontAwesome depuis node_modules vers public/webfonts.
// Nécessaire car le pipeline d'assets de Vite ne résout pas correctement
// les chemins relatifs des url() imbriqués dans les paquets npm profonds
// (cf. resources/sass/app.scss — $fa-font-path).
import { existsSync, mkdirSync, copyFileSync, readdirSync } from 'node:fs';
import { join, dirname } from 'node:path';
import { fileURLToPath } from 'node:url';

const __dirname = dirname(fileURLToPath(import.meta.url));
const source = join(__dirname, '..', 'node_modules', '@fortawesome', 'fontawesome-free', 'webfonts');
const destination = join(__dirname, '..', 'public', 'webfonts');

if (!existsSync(source)) {
    console.warn('[fontawesome] Dossier source introuvable, étape ignorée :', source);
    process.exit(0);
}

mkdirSync(destination, { recursive: true });

const fichiers = readdirSync(source).filter((f) => f.endsWith('.woff2') || f.endsWith('.ttf'));

for (const fichier of fichiers) {
    copyFileSync(join(source, fichier), join(destination, fichier));
}

console.log(`[fontawesome] ${fichiers.length} fichier(s) de police copié(s) vers public/webfonts.`);

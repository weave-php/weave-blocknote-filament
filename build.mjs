import { build } from 'esbuild';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));

await build({
    entryPoints: ['resources/js/blocknote-editor.tsx'],
    bundle: true,
    format: 'iife',
    target: ['es2022'],
    outfile: 'dist/blocknote-editor.js',
    minify: true,
    sourcemap: true,
    conditions: ['style'],
    alias: {
        yjs: path.resolve(__dirname, 'node_modules/yjs'),
        lib0: path.resolve(__dirname, 'node_modules/lib0'),
    },
    loader: {
        '.css': 'css',
        '.woff': 'file',
        '.woff2': 'file',
        '.ttf': 'file',
        '.eot': 'file',
        '.svg': 'file',
    },
});

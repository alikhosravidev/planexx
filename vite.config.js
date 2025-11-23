import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: getInputFiles(),
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js'),
            '~': path.resolve(__dirname, 'resources'),
            '@resources': path.resolve(__dirname, 'resources'),
        },
    },
});

function getInputFiles() {
    const input = [
        'resources/css/app.css',
        'resources/js/app.js',
    ];

    return input;
}

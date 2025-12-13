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
        'resources/js/pages/documents.js',
        'resources/js/pages/bpms-workflows.js',
        'app/Core/Organization/Resources/js/organization-chart.js',
    ];

    return input;
}

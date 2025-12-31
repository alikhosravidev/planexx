import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';
import fs from 'fs';

export default defineConfig({
    plugins: [
        laravel({
            input: getInputFiles(),
            refresh: true,
        }),
    ],
    resolve: {
        alias: getAliases(),
    },
});

/**
 * Auto-discover applications and their entry points
 */
function discoverApplications() {
    const applicationsPath = path.resolve(__dirname, 'Applications');

    if (!fs.existsSync(applicationsPath)) {
        return [];
    }

    const apps = fs.readdirSync(applicationsPath, { withFileTypes: true })
        .filter(dirent => dirent.isDirectory())
        .filter(dirent => dirent.name !== 'Contracts') // Exclude non-app directories
        .filter(dir => {
            const resourcesPath = path.join(applicationsPath, dir.name, 'Resources');
            return fs.existsSync(resourcesPath);
        })
        .map(dir => dir.name);

    return apps;
}

/**
 * Get all input files for Vite
 */
function getInputFiles() {
    const input = [];

    // Auto-discover Application Resources
    const applications = discoverApplications();

    applications.forEach(app => {
        const basePath = `Applications/${app}/Resources`;

        // Add CSS entry point
        const cssPath = `${basePath}/css/app.css`;
        if (fs.existsSync(cssPath)) {
            input.push(cssPath);
        }

        // Add JS entry point
        const jsPath = `${basePath}/js/app.js`;
        if (fs.existsSync(jsPath)) {
            input.push(jsPath);
        }

        // Add page-specific JS files
        const pagesPath = path.resolve(__dirname, basePath, 'js', 'pages');
        if (fs.existsSync(pagesPath)) {
            const pageFiles = fs.readdirSync(pagesPath)
                .filter(file => file.endsWith('.js'))
                .map(file => `${basePath}/js/pages/${file}`);
            input.push(...pageFiles);
        }
    });

    // Add Core Module Resources (like organization-chart)
    const orgChartPath = 'app/Core/Organization/Resources/js/organization-chart.js';
    if (fs.existsSync(orgChartPath)) {
        input.push(orgChartPath);
    }

    return input;
}

/**
 * Generate aliases for all applications
 */
function getAliases() {
    const aliases = {
        '@shared': path.resolve(__dirname, 'resources'),
        '@shared-js': path.resolve(__dirname, 'resources/js'),
        '@shared-css': path.resolve(__dirname, 'resources/css'),
    };

    // Add application-specific aliases
    const applications = discoverApplications();
    applications.forEach(app => {
        const appKey = app.toLowerCase();
        aliases[`@${appKey}`] = path.resolve(__dirname, `Applications/${app}/Resources`);
        aliases[`@${appKey}-js`] = path.resolve(__dirname, `Applications/${app}/Resources/js`);
        aliases[`@${appKey}-css`] = path.resolve(__dirname, `Applications/${app}/Resources/css`);
    });

    return aliases;
}

import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { VitePWA } from 'vite-plugin-pwa';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        VitePWA({
            registerType: 'autoUpdate',
            devOptions: {
                enabled: true,
            },
            manifest: {
                name: 'DevKnowledge',
                short_name: 'DevKnowledge',
                description: 'Personal developer knowledge base',
                theme_color: '#185FA5',
                background_color: '#F9FAFB',
                display: 'standalone',
                start_url: '/',
                icons: [
                    {
                        src: '/favicon.svg',
                        sizes: '192x192',
                        type: 'image/svg+xml',
                        purpose: 'any',
                    },
                    {
                        src: '/favicon.svg',
                        sizes: '512x512',
                        type: 'image/svg+xml',
                        purpose: 'any',
                    },
                ],
            },
            workbox: {
                globPatterns: ['**/*.{js,css,ico,png,svg,woff2}'],
                navigateFallback: '/offline.html',
                navigateFallbackDenylist: [/^\/admin/, /^\/login/, /^\/register/],
                runtimeCaching: [
                    {
                        urlPattern: ({ request, url }) =>
                            request.mode === 'navigate' && url.pathname.startsWith('/guides'),
                        handler: 'StaleWhileRevalidate',
                        options: {
                            cacheName: 'guides-pages',
                            expiration: {
                                maxEntries: 50,
                                maxAgeSeconds: 60 * 60 * 24,
                            },
                        },
                    },
                ],
            },
        }),
    ],
});

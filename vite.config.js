import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/lumen-chat.css',
                'resources/js/lumen-chat.js',
                'resources/css/quizzsense/quizzsense.css',
                'resources/js/quizzsense/app.js',
                'resources/js/paises/paises.js',
                'resources/css/centinela/centinela.css',
                'resources/js/centinela/app.js',
                'resources/css/home.css',
                'resources/css/information.css',
                'resources/js/information.js',
                'resources/css/navbar.css',
                'resources/js/navbar.js',
                'resources/css/footer.css',
                'resources/css/settings-menu.css',
                'resources/js/settings-menu.js',
                'resources/css/dark-theme.css',
                'resources/css/dark-theme-games.css',
                'resources/css/auth.css',
                'resources/css/child-auth.css',
                'resources/css/forgot-password.css',
                'resources/css/edit-profile.css',
                'resources/css/family-panel.css',
                'resources/css/activities.css',
                'resources/css/activities-start.css',
                'resources/css/stages.css',
            ],
            refresh: true,
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});

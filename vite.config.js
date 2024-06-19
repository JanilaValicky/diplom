import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // styles
                'resources/css/app.css',
                'resources/css/aos.css',
                'resources/css/theme.min.css',
                'resources/css/simplebar.min.css',
                'resources/css/style.min.css',
                'resources/css/fontawesome.min.css',
                'resources/css/allfa.css',
                'resources/bootstrap/bootstrap-icons.css',
                'resources/css/checkout.css',
                
                // scripts
                'resources/js/app.js',
                'resources/js/allfa.js',
                'resources/js/theme.bundle.min.js',
                'resources/js/theme.bundle.min2.js',
                'resources/js/login.js',
                'resources/js/logout.js',
                'resources/js/forms/index.js',
                'resources/js/forms/edit.js',
                'resources/js/register.js',
                'resources/js/brand/index.js',
                'resources/js/avatar.js',
                'resources/js/payment/index.js',
                'resources/js/stripe.js',
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            'ziggy-js': path.resolve('vendor/tightenco/ziggy'),
            'DataTable': 'DataTable',
            'DateTime': 'DateTime',
        }
    },
    build: {
        sourcemap: true,
    }
});

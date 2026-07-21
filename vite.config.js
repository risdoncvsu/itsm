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
                'Modules/Ecommerce/resources/css/liquidglass.css',
                'Modules/Ecommerce/resources/js/app.js',
                'Modules/Ecommerce/resources/js/bootstrap.js',
                'Modules/Ecommerce/resources/js/Category/Category.js',
                'Modules/Ecommerce/resources/js/Common/AmbientEffects.js',
                'Modules/Ecommerce/resources/js/Common/Preloader.js',
                'Modules/Ecommerce/resources/js/Common/TailwindConfig.js',
                'Modules/Ecommerce/resources/js/Common/Navbar.js',
                'Modules/Ecommerce/resources/js/HomePage/Homepage.js',
                'Modules/Ecommerce/resources/js/Pages/BuildOverview/BuildOverview.js',
                'Modules/Ecommerce/resources/js/Pages/BuildPc/BuildPc.js',
                'Modules/Ecommerce/resources/js/Pages/Configurator/Configurator.js',
                'Modules/Ecommerce/resources/js/Pages/Search/Search.js'
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
        cors: true,
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});

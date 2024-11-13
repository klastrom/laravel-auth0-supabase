let mix = require('laravel-mix');

mix.sass('resources/sass/app.scss', 'public/dist/css')
   .options({
       processCssUrls: false
   })
   .minify('public/dist/css/app.css')
   .version();

mix.browserSync({
    proxy: 'your-local-dev-url', // ここにローカル開発URLを指定
    files: [
        'resources/sass/**/*.scss',
        'resources/views/**/*.php',
    ]
});
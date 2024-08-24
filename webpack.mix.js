const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css')
   .sourceMaps();

// FullCalendar JS & CSS
mix.js('node_modules/@fullcalendar/core/main.js', 'public/js')
   .js('node_modules/@fullcalendar/daygrid/main.js', 'public/js')
   .js('node_modules/@fullcalendar/interaction/main.js', 'public/js');

mix.styles([
    'node_modules/@fullcalendar/core/main.css',
    'node_modules/@fullcalendar/daygrid/main.css',
], 'public/css/fullcalendar.css');

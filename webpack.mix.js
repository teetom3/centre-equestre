const mix = require('laravel-mix');

// Compilation des fichiers CSS principaux
mix.css('resources/css/app.css', 'public/css')
   .css('resources/css/pages/home.css', 'public/css/pages')
   .css('resources/css/pages/dashboard.css', 'public/css/pages');

// Compilation des fichiers CSS dans le sous-dossier "chevaux"
mix.css('resources/css/pages/chevaux/index.css', 'public/css/pages/chevaux')
   .css('resources/css/pages/chevaux/show.css', 'public/css/pages/chevaux')
   .css('resources/css/pages/chevaux/edit.css', 'public/css/pages/chevaux');

   // Compilation des fichiers CSS dans le sous-dossier "users"

   mix.css('resources/css/pages/users/dashboard.css.css', 'public/css/pages/users')
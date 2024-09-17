<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChevalController;
use App\Http\Controllers\PrestationController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LivrePrestationController;
use App\Http\Controllers\EvenementController;
use App\Http\Controllers\InscriptionController;
use App\Http\Controllers\DashboardController;
use App\Http\Middleware\GerantMiddleware;



Route::middleware([GerantMiddleware::class])->group(function (){

    
Route::get('/tableau-de-bord/gerant', [DashboardController::class, 'gerantDashboard'])->name('dashboard.gerant');

Route::get('/livre-prestations', [LivrePrestationController::class, 'indexForGerant'])->name('livre-prestations.index');


Route::get('users/{userId}/generate-invoice', [UserController::class, 'generateInvoice'])->name('users.generateInvoice');
Route::get('users/{user}/facturation', [UserController::class, 'facturation'])->name('users.facturation');

Route::get('/livreprestations', [LivrePrestationController::class, 'index'])->name('livreprestations.index');
Route::get('/ajout-prestations-masse', [LivrePrestationController::class, 'massAddForm'])->name('livre-prestations.mass-add');
Route::post('/ajout-prestations-masse', [LivrePrestationController::class, 'massAdd'])->name('livre-prestations.mass-add.store');
Route::resource('prestations', PrestationController::class);
Route::post('/livreprestations/{id}/change-state', [LivrePrestationController::class, 'changeState'])->name('livreprestation.changeState');
    });







Route::get('/dashboard-marechal', [DashboardController::class, 'marechalDashboard'])->name('dashboard.marechal');
Route::get('/tableau-de-bord/veterinaire', [DashboardController::class, 'veterinaireDashboard'])->name('dashboard.veterinaire');


Route::get('/', [EvenementController::class, 'landingPage'])->name('landingPage');



Route::resource('users', UserController::class);


Route::resource('chevaux', ChevalController::class)->parameters([
    'chevaux' => 'cheval'
]);




Route::resource('evenements', EvenementController::class);

Route::resource('inscriptions', InscriptionController::class);
Route::post('evenements/{evenement}/inscrire', [InscriptionController::class, 'inscrire'])->name('evenements.inscrire');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('livreprestation/{cheval}', [LivrePrestationController::class, 'store'])->name('livreprestation.store')->middleware('auth');




Route::delete('livreprestation/{id}', [LivrePrestationController::class, 'destroy'])->name('livreprestation.destroy')->middleware('auth');

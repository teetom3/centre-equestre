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

Route::get('/', [EvenementController::class, 'landingPage'])->name('landingPage');


Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

Route::get('users/{userId}/generate-invoice', [UserController::class, 'generateInvoice'])->name('users.generateInvoice');

Route::resource('users', UserController::class);
Route::get('users/{user}/facturation', [UserController::class, 'facturation'])->name('users.facturation');

Route::get('/livreprestations', [LivrePrestationController::class, 'index'])->name('livreprestations.index');


Route::resource('chevaux', ChevalController::class)->parameters([
    'chevaux' => 'cheval'
]);


Route::resource('prestations', PrestationController::class);

Route::resource('evenements', EvenementController::class);

Route::resource('inscriptions', InscriptionController::class);
Route::post('evenements/{evenement}/inscrire', [InscriptionController::class, 'inscrire'])->name('evenements.inscrire');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('livreprestation/{cheval}', [LivrePrestationController::class, 'store'])->name('livreprestation.store')->middleware('auth');

Route::post('/livreprestations/{id}/change-state', [LivrePrestationController::class, 'changeState'])->name('livreprestation.changeState');


Route::delete('livreprestation/{id}', [LivrePrestationController::class, 'destroy'])->name('livreprestation.destroy')->middleware('auth');

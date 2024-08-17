<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChevalController;
use App\Http\Controllers\PrestationController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LivrePrestationController;
use App\Http\Controllers\EvenementController;
use App\Http\Controllers\InscriptionController;

Route::get('/', [EvenementController::class, 'landingPage'])->name('landingPage');


Route::get('users/{userId}/generate-invoice', [UserController::class, 'generateInvoice'])->name('users.generateInvoice');

Route::resource('users', UserController::class);
Route::get('users/{user}/facturation', [UserController::class, 'facturation'])->name('users.facturation');

Route::resource('chevaux', ChevalController::class);

Route::resource('prestations', PrestationController::class);

Route::resource('evenements', EvenementController::class);

Route::resource('inscriptions', InscriptionController::class);
Route::post('evenements/{evenement}/inscrire', [InscriptionController::class, 'inscrire'])->name('evenements.inscrire');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('livreprestation/{cheval}', [LivrePrestationController::class, 'store'])->name('livreprestation.store')->middleware('auth');

Route::delete('livreprestation/{id}', [LivrePrestationController::class, 'destroy'])->name('livreprestation.destroy')->middleware('auth');

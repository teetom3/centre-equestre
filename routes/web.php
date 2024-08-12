<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChevalController;
use App\Http\Controllers\PrestationController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LivrePrestationController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('users', UserController::class);

Route::resource('chevaux', ChevalController::class);

Route::resource('prestations', PrestationController::class);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('livreprestation/{cheval}', [LivrePrestationController::class, 'store'])->name('livreprestation.store')->middleware('auth');

Route::delete('livreprestation/{id}', [LivrePrestationController::class, 'destroy'])->name('livreprestation.destroy')->middleware('auth');

<?php

use App\Http\Controllers\InscriptionNewController;
use App\Http\Controllers\InscriptionRecurrentController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome/Welcome');
})->name('home');


Route::get('inscription', function () {
    return Inertia::render('Inscription');
})->name('inscription');


Route::get('inscription/new', function () {
    return Inertia::render('Inscription/New');
})->name('inscription.new');

Route::post('inscription/new', [InscriptionNewController::class, 'store'])->name('inscription.new.post');

Route::get('inscription/recurrent', function () {
    return Inertia::render('Inscription/Recurrent');
})->name('inscription.recurrent');

Route::post('inscription/recurrent', [InscriptionRecurrentController::class, 'store'])->name('inscription.recurrent.post');

Route::get('inscription/successful', function () {
    return Inertia::render('Inscription/Successful');
})->name('inscription.successful');

Route::get('admin', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('admin');

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';

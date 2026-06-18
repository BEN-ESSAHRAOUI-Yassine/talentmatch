<?php

use App\Http\Controllers\CandidatController;
use App\Http\Controllers\OffreController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('offres', OffreController::class);

    Route::get('/offres/{offre}/candidats/create', [CandidatController::class, 'create'])->name('candidats.create');
    Route::post('/offres/{offre}/candidats', [CandidatController::class, 'store'])->name('candidats.store');
    Route::get('/offres/{offre}/candidats/{candidat}', [CandidatController::class, 'show'])->name('candidats.show');
});

require __DIR__.'/auth.php';

<?php

use App\Http\Controllers\AnalyseController;
use App\Http\Controllers\CandidatComparisonController;
use App\Http\Controllers\CandidatController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\OffreController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', DashboardController::class)->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('offres', OffreController::class);

    Route::get('/candidats', [CandidatController::class, 'index'])->name('candidats.index');
    Route::get('/offres/{offre}/candidats/create', [CandidatController::class, 'create'])->name('candidats.create');
    Route::post('/offres/{offre}/candidats', [CandidatController::class, 'store'])->name('candidats.store');
    Route::get('/offres/{offre}/candidats/classement', [CandidatComparisonController::class, 'classement'])->name('candidats.classement');
    Route::get('/offres/{offre}/candidats/comparer', [CandidatComparisonController::class, 'comparer'])->name('candidats.comparer');
    Route::get('/offres/{offre}/candidats/{candidat}', [CandidatController::class, 'show'])->name('candidats.show');

    Route::post('/analyses/{analyse}/retry', [AnalyseController::class, 'retry'])->name('analyses.retry');

    Route::post('/analyses/{analyse}/conversations', [ConversationController::class, 'store'])->name('conversations.store');
    Route::get('/analyses/{analyse}/conversations/{conversation}', [ConversationController::class, 'show'])->name('conversations.show');
    Route::post('/analyses/{analyse}/conversations/{conversation}/messages', [MessageController::class, 'store'])->name('messages.store');
});

require __DIR__.'/auth.php';

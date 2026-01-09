<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TestApiController;
use App\Http\Controllers\FormationSearchController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Routes Publiques (pas d'authentification requise)
|--------------------------------------------------------------------------
*/

// Page d'accueil avec carte publique
Route::get('/', [HomeController::class, 'index'])->name('home');

// API pour la carte (endpoint public)
Route::get('/api/formations-map', [HomeController::class, 'getFormationsForMap'])->name('api.formations.map');

/*
|--------------------------------------------------------------------------
| Routes de Test (à supprimer en production)
|--------------------------------------------------------------------------
*/

Route::get('/test-api', [TestApiController::class, 'index'])->name('test.api');
Route::get('/test-api/uniques', [TestApiController::class, 'uniques'])->name('test.api.uniques');
Route::get('/test-api/detail', [TestApiController::class, 'detailBTS'])->name('test.api.detail');

/*
|--------------------------------------------------------------------------
| Routes Authentifiées
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


    // Profil utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Recherche avancée de formations (PROTÉGÉ)
    Route::get('/formations/recherche', [FormationSearchController::class, 'index'])->name('formations.search');
    Route::post('/formations/recherche', [FormationSearchController::class, 'search'])->name('formations.search.post');
    Route::get('/formations/resultats', [FormationSearchController::class, 'results'])->name('formations.results');
});

// Routes d'authentification (login, register, etc.)
require __DIR__.'/auth.php';
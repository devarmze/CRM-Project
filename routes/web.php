<?php

use App\Http\Controllers\AttivitaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ContattoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InterazioneController;
use App\Http\Controllers\OpportunitaController;
use App\Http\Controllers\PoolController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Pool
    Route::get('/pool', [PoolController::class, 'index'])->name('pool.index');

    // Clienti + nested resources
    Route::resource('clienti', ClienteController::class);
    Route::post('/clienti/{cliente}/assign', [ClienteController::class, 'assign'])->name('clienti.assign');
    Route::post('/clienti/{cliente}/unassign', [ClienteController::class, 'unassign'])->name('clienti.unassign');
    Route::resource('clienti.contatti', ContattoController::class)->except(['index', 'show']);
    Route::resource('clienti.interazioni', InterazioneController::class)->except(['index', 'show']);

    Route::resource('opportunita', OpportunitaController::class);
    Route::resource('attivita', AttivitaController::class);

    // Admin only
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class);
    });
});

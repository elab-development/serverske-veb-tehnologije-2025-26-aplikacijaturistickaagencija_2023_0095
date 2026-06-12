<?php

use App\Http\Controllers\AranzmanController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Rute za autentifikaciju (javno dostupne)
Route::prefix('auth')->group(function () {
    Route::post('/registracija', [AuthController::class, 'registracija']);
    Route::post('/prijava', [AuthController::class, 'prijava']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/odjava', [AuthController::class, 'odjava']);
        Route::get('/korisnik', [AuthController::class, 'trenutniKorisnik']);
    });
});

// Javno dostupne rute — pregled aranžmana
Route::get('/aranzmani', [AranzmanController::class, 'index']);
Route::get('/aranzmani/{aranzman}', [AranzmanController::class, 'show']);

// Zaštićene rute — samo za autentifikovane korisnike
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/aranzmani', [AranzmanController::class, 'store']);
    Route::put('/aranzmani/{aranzman}', [AranzmanController::class, 'update']);
    Route::patch('/aranzmani/{aranzman}', [AranzmanController::class, 'update']);
    Route::delete('/aranzmani/{aranzman}', [AranzmanController::class, 'destroy']);
});

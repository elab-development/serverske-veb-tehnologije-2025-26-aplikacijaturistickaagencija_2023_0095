<?php

use App\Http\Controllers\AranzmanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DestinacijaController;
use App\Http\Controllers\RezervacijaController;
use Illuminate\Support\Facades\Route;

// Rute za autentifikaciju — throttle sprečava brute-force napade
Route::prefix('auth')->middleware('throttle:10,1')->group(function () {
    Route::post('/registracija', [AuthController::class, 'registracija']);
    Route::post('/prijava', [AuthController::class, 'prijava']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/odjava', [AuthController::class, 'odjava']);
        Route::post('/odjava-svuda', [AuthController::class, 'odjavaIzSvihUredaja']);
        Route::get('/korisnik', [AuthController::class, 'trenutniKorisnik']);
        Route::post('/promeni-lozinku', [AuthController::class, 'promeniLozinku']);
    });
});

// Javno dostupne rute — pregled aranžmana i destinacija (neulogovan korisnik)
Route::get('/aranzmani', [AranzmanController::class, 'index']);
Route::get('/aranzmani/{aranzman}', [AranzmanController::class, 'show']);
Route::get('/destinacije', [DestinacijaController::class, 'index']);
Route::get('/destinacije/{destinacija}', [DestinacijaController::class, 'show']);

// Zaštićene rute za ulogovane korisnike
Route::middleware('auth:sanctum')->group(function () {
    // Rezervacije — dostupno svim ulogovanim korisnicima
    Route::get('/rezervacije', [RezervacijaController::class, 'index']);
    Route::post('/rezervacije', [RezervacijaController::class, 'store']);
    Route::get('/rezervacije/{rezervacija}', [RezervacijaController::class, 'show']);
    Route::put('/rezervacije/{rezervacija}', [RezervacijaController::class, 'update']);
    Route::patch('/rezervacije/{rezervacija}', [RezervacijaController::class, 'update']);
    Route::delete('/rezervacije/{rezervacija}', [RezervacijaController::class, 'destroy']);

    // Aranžmani i destinacije — samo admin može kreirati, menjati i brisati
    Route::middleware('admin')->group(function () {
        Route::post('/aranzmani', [AranzmanController::class, 'store']);
        Route::put('/aranzmani/{aranzman}', [AranzmanController::class, 'update']);
        Route::patch('/aranzmani/{aranzman}', [AranzmanController::class, 'update']);
        Route::delete('/aranzmani/{aranzman}', [AranzmanController::class, 'destroy']);

        Route::post('/destinacije', [DestinacijaController::class, 'store']);
        Route::put('/destinacije/{destinacija}', [DestinacijaController::class, 'update']);
        Route::patch('/destinacije/{destinacija}', [DestinacijaController::class, 'update']);
        Route::delete('/destinacije/{destinacija}', [DestinacijaController::class, 'destroy']);
    });
});

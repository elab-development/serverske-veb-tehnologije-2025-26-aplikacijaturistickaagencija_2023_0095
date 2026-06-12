<?php

use App\Http\Controllers\AranzmanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DestinacijaController;
use App\Http\Controllers\RezervacijaController;
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

// Javno dostupne rute — pregled aranžmana i destinacija
Route::get('/aranzmani', [AranzmanController::class, 'index']);
Route::get('/aranzmani/{aranzman}', [AranzmanController::class, 'show']);
Route::get('/destinacije', [DestinacijaController::class, 'index']);
Route::get('/destinacije/{destinacija}', [DestinacijaController::class, 'show']);

// Zaštićene rute — samo za autentifikovane korisnike
Route::middleware('auth:sanctum')->group(function () {
    // Aranžmani — kreiranje, izmena, brisanje
    Route::post('/aranzmani', [AranzmanController::class, 'store']);
    Route::put('/aranzmani/{aranzman}', [AranzmanController::class, 'update']);
    Route::patch('/aranzmani/{aranzman}', [AranzmanController::class, 'update']);
    Route::delete('/aranzmani/{aranzman}', [AranzmanController::class, 'destroy']);

    // Destinacije — kreiranje, izmena, brisanje
    Route::post('/destinacije', [DestinacijaController::class, 'store']);
    Route::put('/destinacije/{destinacija}', [DestinacijaController::class, 'update']);
    Route::patch('/destinacije/{destinacija}', [DestinacijaController::class, 'update']);
    Route::delete('/destinacije/{destinacija}', [DestinacijaController::class, 'destroy']);

    // Rezervacije — sve rute zahtevaju autentifikaciju
    Route::get('/rezervacije', [RezervacijaController::class, 'index']);
    Route::post('/rezervacije', [RezervacijaController::class, 'store']);
    Route::get('/rezervacije/{rezervacija}', [RezervacijaController::class, 'show']);
    Route::put('/rezervacije/{rezervacija}', [RezervacijaController::class, 'update']);
    Route::patch('/rezervacije/{rezervacija}', [RezervacijaController::class, 'update']);
    Route::delete('/rezervacije/{rezervacija}', [RezervacijaController::class, 'destroy']);
});

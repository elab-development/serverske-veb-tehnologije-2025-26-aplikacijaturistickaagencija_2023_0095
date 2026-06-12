<?php

namespace App\Http\Controllers;

use App\Http\Resources\KorisnikResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function registracija(Request $request): JsonResponse
    {
        $validiraniPodaci = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'name.required'      => 'Ime je obavezno.',
            'email.required'     => 'Email adresa je obavezna.',
            'email.email'        => 'Email adresa nije ispravnog formata.',
            'email.unique'       => 'Korisnik sa ovom email adresom već postoji.',
            'password.required'  => 'Lozinka je obavezna.',
            'password.confirmed' => 'Lozinke se ne poklapaju.',
        ]);

        $korisnik = User::create([
            'name'     => $validiraniPodaci['name'],
            'email'    => $validiraniPodaci['email'],
            'password' => Hash::make($validiraniPodaci['password']),
        ]);

        $token = $korisnik->createToken('api-token')->plainTextToken;

        return response()->json([
            'poruka'   => 'Registracija uspešna.',
            'korisnik' => new KorisnikResource($korisnik),
            'token'    => $token,
        ], 201);
    }

    public function prijava(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required'    => 'Email adresa je obavezna.',
            'email.email'       => 'Email adresa nije ispravnog formata.',
            'password.required' => 'Lozinka je obavezna.',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['Podaci za prijavu nisu ispravni.'],
            ]);
        }

        $korisnik = $request->user();
        $korisnik->tokens()->delete();
        $token = $korisnik->createToken('api-token')->plainTextToken;

        return response()->json([
            'poruka'   => 'Prijava uspešna.',
            'korisnik' => new KorisnikResource($korisnik),
            'token'    => $token,
        ]);
    }

    public function odjava(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'poruka' => 'Uspešno ste se odjavili.',
        ]);
    }

    public function trenutniKorisnik(Request $request): KorisnikResource
    {
        return new KorisnikResource($request->user());
    }
}

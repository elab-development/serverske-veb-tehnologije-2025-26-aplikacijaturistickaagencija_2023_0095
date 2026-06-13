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

        $korisnik = User::where('email', $request->email)->first();

        if (!$korisnik || !Hash::check($request->password, $korisnik->password)) {
            throw ValidationException::withMessages([
                'email' => ['Podaci za prijavu nisu ispravni.'],
            ]);
        }

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

    public function odjavaIzSvihUredaja(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'poruka' => 'Odjavili ste se sa svih uređaja.',
        ]);
    }

    public function trenutniKorisnik(Request $request): KorisnikResource
    {
        return new KorisnikResource($request->user());
    }

    public function promeniLozinku(Request $request): JsonResponse
    {
        $request->validate([
            'trenutna_lozinka' => ['required'],
            'nova_lozinka'     => ['required', 'confirmed', Password::defaults()],
        ], [
            'trenutna_lozinka.required' => 'Trenutna lozinka je obavezna.',
            'nova_lozinka.required'     => 'Nova lozinka je obavezna.',
            'nova_lozinka.confirmed'    => 'Potvrda nove lozinke se ne poklapa.',
        ]);

        if (!Hash::check($request->trenutna_lozinka, $request->user()->password)) {
            return response()->json([
                'poruka' => 'Trenutna lozinka nije ispravna.',
            ], 422);
        }

        $request->user()->update([
            'password' => Hash::make($request->nova_lozinka),
        ]);

        $request->user()->tokens()->delete();
        $token = $request->user()->createToken('api-token')->plainTextToken;

        return response()->json([
            'poruka' => 'Lozinka je uspešno promenjena.',
            'token'  => $token,
        ]);
    }
}

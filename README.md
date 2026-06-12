# Turisticka Agencija - API

Laravel REST API projekat za turisticku agenciju, razvijen u okviru predmeta Serverske veb tehnologije (2025/26).

## Tehnologije

- Laravel 13 (PHP 8.3)
- SQLite (razvojna baza podataka)
- Postman (testiranje API-ja)

## Pokretanje projekta

1. Klonirati repozitorijum i instalirati zavisnosti:

   ```bash
   composer install
   ```

2. Kopirati `.env.example` u `.env` i generisati aplikacioni kljuc:

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. Kreirati SQLite bazu i pokrenuti migracije sa seederima:

   ```bash
   touch database/database.sqlite
   php artisan migrate --seed
   ```

4. Pokrenuti razvojni server:

   ```bash
   php artisan serve
   ```

## Modeli

- **Destinacija** - destinacije (gradovi/zemlje) koje agencija nudi
- **Aranzman** - putni aranzmani, vezani za destinaciju
- **Rezervacija** - rezervacije korisnika za odredjeni aranzman

## Dokumentacija

Dokumentacija API rute i Postman primeri ce biti dodati u okviru posebnog direktorijuma dokumentacije.

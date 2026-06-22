<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rezervacije_log', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rezervacija_id');
            $table->unsignedBigInteger('aranzman_id');
            $table->unsignedBigInteger('korisnik_id');
            $table->integer('broj_osoba');
            $table->decimal('ukupna_cena', 10, 2);
            $table->string('akcija', 20)->default('INSERT');
            $table->timestamp('kreirano')->useCurrent();
        });

        DB::unprepared(<<<'SQL'
            CREATE TRIGGER nakon_kreiranja_rezervacije
            AFTER INSERT ON rezervacije
            FOR EACH ROW
            BEGIN
                INSERT INTO rezervacije_log
                    (rezervacija_id, aranzman_id, korisnik_id, broj_osoba, ukupna_cena, akcija, kreirano)
                VALUES
                    (NEW.id, NEW.aranzman_id, NEW.korisnik_id, NEW.broj_osoba, NEW.ukupna_cena, 'INSERT', datetime('now'));
            END
            SQL);
    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS nakon_kreiranja_rezervacije');
        Schema::dropIfExists('rezervacije_log');
    }
};

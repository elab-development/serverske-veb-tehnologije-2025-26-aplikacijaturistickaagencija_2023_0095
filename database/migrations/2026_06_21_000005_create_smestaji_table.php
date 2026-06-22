<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('smestaji', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_smestaja_id')->constrained('partneri_smestaja')->cascadeOnDelete();
            $table->string('naziv');
            $table->enum('tip', ['hotel', 'hostel', 'apartman', 'villa', 'kamp']);
            $table->string('adresa')->nullable();
            $table->unsignedTinyInteger('broj_zvezdica')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('smestaji');
    }
};

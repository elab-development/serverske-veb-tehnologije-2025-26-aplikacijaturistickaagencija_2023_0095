<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prevozi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_prevoza_id')->constrained('partneri_prevoza')->cascadeOnDelete();
            $table->string('naziv');
            $table->enum('tip', ['avion', 'autobus', 'brod', 'voz']);
            $table->string('polaziste');
            $table->string('odrediste');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prevozi');
    }
};

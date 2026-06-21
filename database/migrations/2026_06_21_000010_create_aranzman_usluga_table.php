<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aranzman_usluga', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aranzman_id')->constrained('aranzmani')->cascadeOnDelete();
            $table->foreignId('usluga_id')->constrained('usluge')->cascadeOnDelete();
            $table->unique(['aranzman_id', 'usluga_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aranzman_usluga');
    }
};

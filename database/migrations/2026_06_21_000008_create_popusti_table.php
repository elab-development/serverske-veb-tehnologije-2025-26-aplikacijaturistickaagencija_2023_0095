<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('popusti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aranzman_id')->constrained('aranzmani')->cascadeOnDelete();
            $table->string('naziv');
            $table->enum('tip', ['popust', 'akcija', 'last_minute']);
            $table->unsignedTinyInteger('procenat');
            $table->date('datum_od')->nullable();
            $table->date('datum_do')->nullable();
            $table->boolean('aktivan')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('popusti');
    }
};

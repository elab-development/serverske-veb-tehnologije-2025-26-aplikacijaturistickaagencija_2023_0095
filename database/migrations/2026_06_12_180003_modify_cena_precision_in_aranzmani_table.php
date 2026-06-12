<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('aranzmani', function (Blueprint $table) {
            $table->decimal('cena', 10, 2)->unsigned()->change();
        });
    }

    public function down(): void
    {
        Schema::table('aranzmani', function (Blueprint $table) {
            $table->decimal('cena', 8, 2)->change();
        });
    }
};

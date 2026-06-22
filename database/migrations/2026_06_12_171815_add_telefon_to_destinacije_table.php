<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('destinacije', function (Blueprint $table) {
            $table->string('telefon')->nullable()->after('opis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('destinacije', function (Blueprint $table) {
            $table->dropColumn('telefon');
        });
    }
};

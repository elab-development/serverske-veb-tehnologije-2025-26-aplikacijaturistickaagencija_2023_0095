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
        Schema::table('rezervacije', function (Blueprint $table) {
            $table->unsignedBigInteger('korisnik_id')->nullable()->after('id');
            $table->unsignedBigInteger('aranzman_id')->nullable()->after('korisnik_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rezervacije', function (Blueprint $table) {
            $table->dropColumn(['korisnik_id', 'aranzman_id']);
        });
    }
};

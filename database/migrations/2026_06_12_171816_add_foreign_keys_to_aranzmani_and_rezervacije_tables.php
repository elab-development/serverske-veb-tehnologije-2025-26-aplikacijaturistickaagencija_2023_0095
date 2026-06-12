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
        Schema::table('aranzmani', function (Blueprint $table) {
            $table->foreign('destinacija_id')
                ->references('id')->on('destinacije')
                ->onDelete('cascade');
        });

        Schema::table('rezervacije', function (Blueprint $table) {
            $table->foreign('korisnik_id')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->foreign('aranzman_id')
                ->references('id')->on('aranzmani')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aranzmani', function (Blueprint $table) {
            $table->dropForeign(['destinacija_id']);
        });

        Schema::table('rezervacije', function (Blueprint $table) {
            $table->dropForeign(['korisnik_id']);
            $table->dropForeign(['aranzman_id']);
        });
    }
};

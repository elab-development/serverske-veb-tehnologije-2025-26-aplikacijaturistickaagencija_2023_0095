<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('aranzmani', function (Blueprint $table) {
            $table->unsignedBigInteger('prevoz_id')->nullable()->after('destinacija_id');
            $table->unsignedBigInteger('smestaj_id')->nullable()->after('prevoz_id');
        });
    }

    public function down(): void
    {
        Schema::table('aranzmani', function (Blueprint $table) {
            $table->dropColumn(['prevoz_id', 'smestaj_id']);
        });
    }
};

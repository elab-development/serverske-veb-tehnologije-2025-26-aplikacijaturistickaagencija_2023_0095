<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('aranzmani', function (Blueprint $table) {
            $table->foreign('prevoz_id')
                ->references('id')->on('prevozi')
                ->nullOnDelete();

            $table->foreign('smestaj_id')
                ->references('id')->on('smestaji')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('aranzmani', function (Blueprint $table) {
            $table->dropForeign(['prevoz_id']);
            $table->dropForeign(['smestaj_id']);
        });
    }
};

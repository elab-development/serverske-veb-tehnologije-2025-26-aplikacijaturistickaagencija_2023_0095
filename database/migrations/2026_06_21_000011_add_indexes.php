<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('popusti', function (Blueprint $table) {
            $table->index(['aktivan', 'datum_do'], 'idx_popusti_aktivan_datum');
            $table->index('tip', 'idx_popusti_tip');
        });

        Schema::table('prevozi', function (Blueprint $table) {
            $table->index('tip', 'idx_prevozi_tip');
        });

        Schema::table('smestaji', function (Blueprint $table) {
            $table->index('tip', 'idx_smestaji_tip');
            $table->index('broj_zvezdica', 'idx_smestaji_zvezdice');
        });
    }

    public function down(): void
    {
        Schema::table('popusti', function (Blueprint $table) {
            $table->dropIndex('idx_popusti_aktivan_datum');
            $table->dropIndex('idx_popusti_tip');
        });

        Schema::table('prevozi', function (Blueprint $table) {
            $table->dropIndex('idx_prevozi_tip');
        });

        Schema::table('smestaji', function (Blueprint $table) {
            $table->dropIndex('idx_smestaji_tip');
            $table->dropIndex('idx_smestaji_zvezdice');
        });
    }
};

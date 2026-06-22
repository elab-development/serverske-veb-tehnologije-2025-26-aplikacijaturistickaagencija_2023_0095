<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rezervacije', function (Blueprint $table) {
            $table->index('status', 'idx_rezervacije_status');
            $table->index(['korisnik_id', 'status'], 'idx_rezervacije_korisnik_status');
        });
    }

    public function down(): void
    {
        Schema::table('rezervacije', function (Blueprint $table) {
            $table->dropIndex('idx_rezervacije_status');
            $table->dropIndex('idx_rezervacije_korisnik_status');
        });
    }
};

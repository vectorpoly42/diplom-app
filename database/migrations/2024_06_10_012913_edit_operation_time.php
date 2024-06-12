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
        Schema::table('operations', function (Blueprint $table) {
            $table->dropColumn('time');
            $table->float('main_time'); // Основное время операции
            $table->float('auxiliary_time'); // вспомогательное время операции
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operations', function (Blueprint $table) {
            $table->float('time');
            $table->dropColumn('main_time');
            $table->dropColumn('auxiliary_time');
        });
    }
};

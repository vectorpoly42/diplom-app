<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveDetailIdsFromOrdersTable extends Migration
{
    public function up(): void
    {
        Schema::table('order', function (Blueprint $table) {
            $table->dropColumn('detail_ids');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->json('detail_ids')->nullable();
        });
    }
}

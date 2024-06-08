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
        Schema::create('detail_order', function (Blueprint $table) {
            $table->id();
            $table->foreignId('detail_id')->constrained('details')->onDelete('cascade'); // Внешний ключ на деталь
            $table->foreignId('order_id')->constrained('order')->onDelete('cascade'); // Внешний ключ на заявку
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_order');
    }
};

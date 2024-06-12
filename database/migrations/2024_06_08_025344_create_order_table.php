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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->json('detail_ids');
            $table->integer('number_of_details');
            $table->integer('number_of_devices');
            $table->integer('J_parameter');

            // Добавление полей для трех матриц
            $table->json('T_l_w')->nullable(); //длительности реализации операций с заданиями w-х типов на l-х приборах
            $table->json('A_w_i')->nullable(); // принадлежность i-го задания w-му набору заданий
            $table->json('P_l_w')->nullable(); // переналадки
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order');
    }
};

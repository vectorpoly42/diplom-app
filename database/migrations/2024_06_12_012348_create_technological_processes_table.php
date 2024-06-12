<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTechnologicalProcessesTable extends Migration
{
    public function up()
    {
        Schema::create('technological_processes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('detail_id');
            $table->json('operations'); // Поле для хранения списка операций в формате JSON
            $table->timestamps();
            // Добавление внешнего ключа
            $table->foreign('detail_id')->references('id')->on('details')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('technological_processes');
    }
}

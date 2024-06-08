<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailsTable extends Migration
{
    public function up() {
        Schema::create('details', function (Blueprint $table) {
            $table->id();
            $table->integer('type')->unsigned(); // тип детали
            $table->string('name'); // название детали
            $table->float('diameter'); // начальный диаметр
            $table->json('wear')->default(json_encode([])); // износ детали
            $table->timestamps();
        });
    }

    public function down(){
        Schema::dropIfExists('details');
    }
}

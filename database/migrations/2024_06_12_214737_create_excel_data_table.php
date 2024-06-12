<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExcelDataTable extends Migration
{
    public function up()
    {
        Schema::create('excel_data', function (Blueprint $table) {
            $table->id();
            $table->string('sheet_name');
            $table->json('data');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('excel_data');
    }
}

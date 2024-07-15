<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrediccionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prediccions', function (Blueprint $table) {
            $table->id();
            $table->string('RUC_EMPLEADOR');
            $table->integer('TIPO_EMPRESA');
            $table->float('MTO_TOTAL_DEMANDA');
            $table->integer('DEPARTAMENTO');
            $table->string('resultado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prediccions');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableRental extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rental', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('car-id')->unsigned();
            $table->integer('client-id')->unsigned();
            $table->date('date-from');
            $table->date('date-to');
            $table->foreign('client-id')->references('id')->on('client')->onDelete('restrict');
            $table->foreign('car-id')->references('id')->on('car')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('rental');
    }
}

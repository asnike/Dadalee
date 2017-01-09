<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEarningrateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('earningrates', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('realestate_id')->unsigned();

        $table->integer('price');
        $table->integer('deposit');
        $table->integer('monthlyfee')->nullable();
        $table->integer('investment');
        $table->integer('loan')->nullable();
        $table->integer('interestrate')->nullable();

        $table->timestamps();

        $table->foreign('realestate_id')->references('id')->on('realestates')->onUpate('cascade')->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

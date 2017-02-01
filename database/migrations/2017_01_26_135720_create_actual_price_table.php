<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActualPriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actualprices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sigungu');
            $table->string('main_no');
            $table->string('sub_no');
            $table->string('building_name');
            $table->float('exclusive_size');
            $table->float('land_size');
            $table->string('yearmonth');
            $table->string('day');
            $table->integer('price');
            $table->integer('floor');
            $table->date('completed_at');
            $table->string('new_address');
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
        //
    }
}

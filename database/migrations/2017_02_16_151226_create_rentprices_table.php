<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRentpricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('rental_costs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sigungu');
            $table->string('main_no');
            $table->string('sub_no');
            $table->string('building_name');
            $table->float('exclusive_size');
            $table->float('land_size');
            $table->string('yearmonth');
            $table->string('day');
            $table->integer('deposit');
            $table->integer('rental_cost')->nullable();
            $table->string('rental_type');
            $table->integer('floor')->nullable();
            $table->string('completed_at');
            $table->string('new_address')->nullable();
            $table->string('lng')->nullable();
            $table->string('lat')->nullable();
            $table->timestamps();

            $table->index('lng');
            $table->index('lat');
            $table->index(['main_no', 'sub_no']);
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

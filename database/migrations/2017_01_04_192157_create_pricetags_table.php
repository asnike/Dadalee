<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePricetagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('pricetags', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sigungu');
            $table->string('main_no');
            $table->string('sub_no');
            $table->string('building_name');
            $table->string('new_address')->nullable();
            $table->string('lng');
            $table->string('lat');
            $table->integer('user_id')->unsigned();

            $table->string('exclusive_size')->nullable();
            $table->date('completed_at')->nullable();
            $table->date('reported_at')->nullable();
            $table->integer('price')->nullable();
            $table->integer('deposit')->nullable();
            $table->integer('rental_cost')->nullable();
            $table->integer('floor')->nullable();

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
        Schema::dropIfExists('pricetags');
    }
}

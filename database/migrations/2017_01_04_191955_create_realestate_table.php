<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRealestateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('realestates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('address');
            $table->double('lat');
            $table->double('lng');
            $table->boolean('own')->default(0);
            $table->boolean('market')->default(0);
            $table->integer('floor')->nullable();
            $table->integer('deposit')->nullable();;
            $table->integer('rental_cost')->nullable();
            $table->date('completed_at')->nullable();
            $table->float('exclusive_size')->nullable();
            $table->text('memo')->nullable();

            $table->integer('user_id')->unsigned();

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onUpate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('realestates');
    }
}

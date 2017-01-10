<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTenantTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('tenants', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('realestate_id')->unsigned();

            $table->char('name');
            $table->integer('tel');
            $table->integer('pay_day');
            $table->char('pay_account_no');
            $table->char('pay_account_bank');
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

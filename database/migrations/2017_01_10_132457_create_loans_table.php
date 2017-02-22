<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('realestate_id')->unsigned();

            $table->integer('amount');
            $table->decimal('interest_rate');
            $table->decimal('repay_commission');
            $table->integer('unredeem_period')->default(0);
            $table->integer('repay_period');
            $table->integer('repay_method_id')->unsigned();
            $table->char('bank')->nullable();
            $table->char('account_no')->nullable();
            $table->text('options')->nullable();

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

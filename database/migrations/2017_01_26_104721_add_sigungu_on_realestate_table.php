<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSigunguOnRealestateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('realestates', function (Blueprint $table) {
            $table->string('sigungu')->after('address');
            $table->string('building_name')->after('sigungu')->nullable();
            $table->string('sub_no')->after('sigungu');
            $table->string('main_no')->after('sigungu');
            $table->string('new_address')->after('main_address');
            $table->string('sigungu_code')->after('sigungu');
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

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsOnEarningrateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('earningrates', function (Blueprint $table) {
            $table->integer('etc_cost')->after('investment')->nullable();
            $table->integer('tax')->after('investment');
            $table->integer('judicial_cost')->after('investment')->nullable();
            $table->integer('mediation_cost')->after('investment')->nullable();
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
        Schema::table('earningrates', function (Blueprint $table) {
            $table->dropColumn('etc_cost');
            $table->dropColumn('tax');
            $table->dropColumn('judicial_cost');
            $table->dropColumn('mediation_cost');
        });
    }
}

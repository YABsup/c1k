<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexes extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        //
        Schema::table('exchanges', function (Blueprint $table) {
            //
            $table->index('user_id');
            $table->index('status_id');
            $table->index('ref_code_id');
            $table->index('uuid');
        });
        Schema::table('withdraws', function (Blueprint $table) {
            //
            $table->index('user_id');
            $table->index('status_id');
        });
        Schema::table('users', function (Blueprint $table) {
            //
            $table->index('ref_code');
        });
        Schema::table('reservs', function (Blueprint $table) {
            //
            $table->index('coin_id');
        });
        Schema::table('pairs', function (Blueprint $table) {
            //
            $table->index('city_id');
            $table->index('active');
            $table->index('provider_id');
            $table->index('base_currency_id');
            $table->index('quote_currency_id');
        });
        Schema::table('coins', function (Blueprint $table) {
            //
            $table->index('active');

        });
        Schema::table('cities', function (Blueprint $table) {
            //
            $table->index('active');

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

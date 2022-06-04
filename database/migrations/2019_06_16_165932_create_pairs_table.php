<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePairsTable extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::create('pairs', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('active')->default(0);//
            $table->string('symbol')->nullable();//
            $table->integer('provider_id');//
            $table->integer('city_id');//
            $table->integer('base_currency_id');//
            $table->integer('quote_currency_id');//

            $table->decimal('bid_coef', 20,4);//
            $table->decimal('ask_coef', 20,4);//

            $table->decimal('base_min', 20,8);//
            $table->decimal('base_max', 20,8);//

            $table->decimal('quote_min', 20,8);//
            $table->decimal('quote_max', 20,8);//

            $table->integer('ask_position')->default(1); //
            $table->integer('bid_position')->default(1); //

            $table->integer('buy_enable')->default(1); //
            $table->integer('sell_enable')->default(1);//

            $table->decimal('bid_step', 20,8)->default(0.00000001);//
            $table->decimal('ask_step', 20,8)->default(0.00000001);//

            $table->timestamps();
        });

        $seeder = new PairsSeeder;
        $seeder->run();

    }

    /**
    * Reverse the migrations.
    *
    * @return void
    */
    public function down()
    {
        Schema::dropIfExists('pairs');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRatesTable extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::create('rates', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('provider');
            $table->string('symbol');
            $table->decimal('bid',20,8);
            $table->decimal('ask',20,8);

            $table->integer('bid_position')->default(0);
            $table->integer('ask_position')->default(0);
            $table->decimal('rate_to_pos_bid',20,8);
            $table->decimal('rate_to_pos_ask',20,8);


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
        Schema::dropIfExists('rates');
    }
}

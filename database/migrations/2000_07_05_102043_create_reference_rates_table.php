<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReferenceRatesTable extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::create('reference_rates', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('provider');
            $table->string('symbol');

            $table->decimal('bid', 20, 8);
            $table->decimal('ask', 20, 8);

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
        Schema::dropIfExists('reference_rates');
    }
}

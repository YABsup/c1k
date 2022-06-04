<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePriceProvidersTable extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::create('price_providers', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('code');
            $table->string('name');

            $table->timestamps();
        });

        $seeder = new PriceProvidersSeeder;
        $seeder->run();
    }

    /**
    * Reverse the migrations.
    *
    * @return void
    */
    public function down()
    {
        Schema::dropIfExists('price_providers');
    }
}

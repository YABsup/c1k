<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservsTable extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::create('reservs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('coin_id');
            $table->decimal('amount', 20,8);
            $table->timestamps();
        });

        $seeder = new ReservsSeeder;
        $seeder->run();

    }

    /**
    * Reverse the migrations.
    *
    * @return void
    */
    public function down()
    {
        Schema::dropIfExists('reservs');
    }
}

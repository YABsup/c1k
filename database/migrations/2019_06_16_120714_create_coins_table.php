<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoinsTable extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::create('coins', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('code',50)->unique();
            $table->string('name');
            $table->integer('active');
            $table->integer('round')->default(8);

            $table->string('adress_type')->default('wallet');

            $table->timestamps();
        });

        $seeder = new CoinsSeeder;
        $seeder->run();

    }

    /**
    * Reverse the migrations.
    *
    * @return void
    */
    public function down()
    {
        Schema::dropIfExists('coins');
    }
}

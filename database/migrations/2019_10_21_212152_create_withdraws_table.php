<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWithdrawsTable extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::create('withdraws', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');

            $table->string('fio')->nullable();
            $table->string('telegram')->nullable();
            $table->string('currency')->nullable();
            $table->string('address')->nullable();
            $table->integer('balance')->default(0);
            $table->integer('user_approved')->default(0);
            $table->integer('status_id')->default(0);
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
        Schema::dropIfExists('withdraws');
    }
}

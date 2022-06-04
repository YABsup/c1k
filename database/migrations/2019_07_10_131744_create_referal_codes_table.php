<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReferalCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referal_codes', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->integer('user_id');
            $table->string('name')->default('main');
            $table->string('hash');
            $table->integer('visits')->default(0);;
            $table->string('type')->default('base');;

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
        Schema::dropIfExists('referal_codes');
    }
}

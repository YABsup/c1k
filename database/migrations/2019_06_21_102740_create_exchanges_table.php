<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExchangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exchanges', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('user_id');//
            $table->string('side');
            $table->integer('category_pair_id');
            $table->string('gate')->nullable()->default('c1k.world');
            $table->decimal('amount_take',20,8);
            $table->decimal('amount_get',20,8);
            $table->string('first_name')->nullable();
            $table->string("address_from")->nullable();
            $table->string("address_to")->nullable();
            $table->string("viber")->nullable();
            $table->string("telegram")->nullable();
            $table->string("whatsapp")->nullable();
            $table->integer("status_id")->nullable();
            $table->string("email");
            $table->string("checkbox");
            $table->string("uuid");
            $table->decimal("profit",10,2)->default(0.0);
            $table->bigInteger("ref_code_id")->default(0);
            $table->decimal("ref_profit",10,2)->default(0.0);

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
        Schema::dropIfExists('exchanges');
    }
}

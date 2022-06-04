<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnketasTable extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::create('anketas', function (Blueprint $table) {
            $table->bigIncrements('id');


            $table->bigInteger('user_id')->nullable();

            $table->string('username')->nullable();
            $table->string('email')->nullable();
            $table->string('telegram')->nullable();
            $table->string('kind_of_activity')->nullable();
            $table->string('auditory_type')->nullable();
            $table->string('auditory_count')->nullable();


            $table->string('youtube_link')->nullable();
            $table->string('insta_link')->nullable();
            $table->string('telegram_link')->nullable();

            $table->string('additional_link')->nullable();
            $table->string('additional_info')->nullable();


            $table->string('platform_name')->nullable();
            $table->string('platform_link')->nullable();
            $table->string('platform_position')->nullable();
            $table->string('platform_age')->nullable();

            $table->string('verify_code');
            $table->integer('status')->default(0);
            $table->string('type')->default('base');

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
        Schema::dropIfExists('anketas');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->boolean( 'active' )->default(false);

            $table->string('slug')->nullable();
            $table->string('image')->nullable();
            $table->string('url_href')->nullable();

            $table->string('meta_title_ru')->nullable();
            $table->string('meta_description_ru')->nullable();
            $table->string('title_ru')->nullable();
            $table->string('url_title_ru')->nullable();
            $table->string('short_text_ru')->nullable();
            $table->text('text_ru')->nullable();

            $table->string('meta_title_ua')->nullable();
            $table->string('meta_description_ua')->nullable();
            $table->string('title_ua')->nullable();
            $table->string('url_title_ua')->nullable();
            $table->string('short_text_ua')->nullable();
	    $table->text('text_ua')->nullable();

            $table->string('meta_title_en')->nullable();
            $table->string('meta_description_en')->nullable();
            $table->string('title_en')->nullable();
            $table->string('url_title_en')->nullable();
            $table->string('short_text_en')->nullable();
            $table->text('text_en')->nullable();


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
        Schema::dropIfExists('blogs');
    }
}

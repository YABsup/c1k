<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');//
            $table->string('email',200)->unique();//
            $table->timestamp('email_verified_at')->nullable();//
            $table->string('password');//
            $table->string('role')->default('user');//
            $table->string('viber')->nullable();//
            $table->string('telegram')->nullable();//
            $table->string('whatsapp')->nullable();//
            $table->string('phone')->nullable();//

            $table->integer('partner')->nullable()->default(0);//

            $table->bigInteger('referer_id')->default(0)->nullable();//
            $table->decimal('balance',20,4)->default(0)->nullable();//
            $table->string('ref_code')->nullable();//
            $table->integer('verified')->nullable()->default(0);//
            $table->integer('verified_send')->nullable()->default(0);//
            $table->bigInteger('telegram_id')->nullable()->default(0);//
            $table->integer('visits')->default(0)->nullable();//

            $table->string('api_token')->nullable();//
            $table->string('api_secret')->nullable();//

            $table->rememberToken();//
            $table->timestamps();//
        });

        $seeder = new UserAdminSeeder();
        $seeder->run();

    }

    /**
    * Reverse the migrations.
    *
    * @return void
    */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}

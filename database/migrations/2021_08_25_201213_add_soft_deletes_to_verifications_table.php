<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoftDeletesToVerificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_verifications', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('admin_who_deleted')->nullable();

            $table->softDeletes();
        });
        Schema::table('card_verifications', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('admin_who_deleted')->nullable();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('verifications', function (Blueprint $table) {
            //
        });
    }
}

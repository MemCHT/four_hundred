<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->string('name',255);
            $table->string('email',255)->unique();
            $table->dateTime('email_verified_at')->nullable();
            $table->string('password',255);
            $table->rememberToken();
            $table->tinyInteger('status_id')->unsigned()->default(1);   //公開（正常）
            $table->string('icon',255)->default('default.png');
            $table->dateTime('created_at');
            $table->dateTime('updated_at');

            $table->foreign('status_id')
                ->references('id')->on('statuses')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
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

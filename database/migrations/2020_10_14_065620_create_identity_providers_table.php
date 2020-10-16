<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIdentityProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('identity_providers', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('provider_id');
            $table->string('provider_name');
            $table->primary(['provider_id', 'provider_name']);   //複合主キー
            $table->unique(['user_id', 'provider_name']);
            $table->dateTime('created_at');
            $table->dateTime('updated_at');

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('identity_providers');
    }
}

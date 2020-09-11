<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('blog_id')->unsigned();
            $table->string('title',40);
            $table->string('body',400);
            $table->boolean('status')->default(false);
            $table->dateTime('created_at');
            $table->dateTime('updated_at');

            $table->foreign('blog_id')->references('id')->on('blogs')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}

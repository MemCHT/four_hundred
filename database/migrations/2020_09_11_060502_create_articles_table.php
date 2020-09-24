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
            $table->tinyInteger('status_id')->unsigned()->default(2);  //非公開
            $table->dateTime('created_at');
            $table->dateTime('updated_at');

            $table->foreign('blog_id')
                ->references('id')->on('blogs')
                ->onUpdate('cascade')
                ->onDelete('cascade');

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
        Schema::dropIfExists('articles');
    }
}

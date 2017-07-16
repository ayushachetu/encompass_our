<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('es_name');
            $table->string('matrix');
            $table->string('es_matrix');
            $table->string('image');
            $table->string('es_image');
            $table->string('comment');
            $table->string('es_comment');
            $table->integer('priority');
            $table->integer('created_by');
            $table->boolean('is_deleted');
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
        Schema::drop('questions');
    }
}

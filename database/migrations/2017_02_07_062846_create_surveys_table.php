<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surveys', function (Blueprint $table) {
            $table->increments('id');
            $table->string('random_id')->unique();
            $table->string('name')->unique();
            $table->text('description');
            $table->string('type');
            $table->boolean('has_express');
            $table->boolean('is_active');
            $table->boolean('is_deleted');
            $table->string('bgcolor')->default('#449d44');
            $table->integer('created_by');
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
        Schema::drop('surveys');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubmittedSurveysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('submitted_surveys', function (Blueprint $table) {
            $table->increments('id');
            $table->string('survey_random_id');
            $table->integer('job');
            $table->integer('user_id');
            $table->boolean('random');
            $table->string('version');
            $table->string('industry');
            $table->string('include_ques');
            $table->integer('manager');
            $table->string('status');
            $table->timestamp('timestamp');
            $table->string('signature');
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
        Schema::drop('submitted_surveys');
    }
}

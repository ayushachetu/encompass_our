<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubmittedSurveysQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('submitted_surveys_questions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('submitted_survey_id');
            $table->integer('question_id');
            $table->string('question_name');
            $table->string('question_status');
            $table->tinyInteger('question_index');
            $table->boolean('is_current');
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
        Schema::drop('submitted_surveys_questions');
    }
}

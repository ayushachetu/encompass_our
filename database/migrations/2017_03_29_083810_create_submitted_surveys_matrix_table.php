<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubmittedSurveysMatrixTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('submitted_surveys_matrix', function (Blueprint $table) {
            $table->increments('id');
            $table->string('submitted_survey_id');
            $table->string('question_name');
            $table->string('matrix');
            $table->string('total_score');
            $table->string('ques_score');
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
        Schema::drop('submitted_surveys_matrix');
    }
}

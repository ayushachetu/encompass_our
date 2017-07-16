<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubmittedSurveysRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('submitted_surveys_ratings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('submitted_survey_id');
            $table->string('question_name');
            $table->string('rating_name');
            $table->tinyInteger('rating_level');
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
        Schema::drop('submitted_surveys_ratings');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubmittedSurveysQuestions extends Model {
	/**
	* The database table used by the model.
	*
	* @var string
	*/

	protected $table = 'submitted_surveys_questions';

	/**
	* The attributes that are mass assignable.
	*
	* @var array
	*/

	protected $fillable = [ 'submitted_survey_id', 'question_id', 'question_name', 'question_status', 'question_index', 'is_current' ];
}
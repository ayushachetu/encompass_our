<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubmittedSurveysMatrix extends Model {
	/**
	* The database table used by the model.
	*
	* @var string
	*/

	protected $table = 'submitted_surveys_matrix';

	/**
	* The attributes that are mass assignable.
	*
	* @var array
	*/

	protected $fillable = [ 'submitted_survey_id', 'question_name', 'matrix', 'total_score', 'ques_score' ];
}
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubmittedSurveysRatings extends Model {
	/**
	* The database table used by the model.
	*
	* @var string
	*/

	protected $table = 'submitted_surveys_ratings';

	/**
	* The attributes that are mass assignable.
	*
	* @var array
	*/

	protected $fillable = [ 'submitted_survey_id', 'question_name', 'rating_name', 'rating_level' ];
}
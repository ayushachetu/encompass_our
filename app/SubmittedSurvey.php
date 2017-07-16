<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubmittedSurvey extends Model {
	/**
	* The database table used by the model.
	*
	* @var string
	*/

	protected $table = 'submitted_surveys';

	/**
	* The attributes that are mass assignable.
	*
	* @var array
	*/

	protected $fillable = [ 'survey_random_id', 'job', 'user_id', 'random', 'version', 'industry', 'manager', 'include_ques', 'status', 'signature'. 'timestamp' ];
}
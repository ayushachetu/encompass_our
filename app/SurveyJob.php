<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SurveyJob extends Model {
	/**
	* The database table used by the model.
	*
	* @var string
	*/

	protected $table = 'surveys_jobs';

	/**
	* The attributes that are mass assignable.
	*
	* @var array
	*/

	protected $fillable = [ 'survey_id', 'job_number' ];
}
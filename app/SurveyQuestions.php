<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SurveyQuestions extends Model {
	/**
	* The database table used by the model.
	*
	* @var string
	 */

	protected $table = 'survey_question';

	/**
	* The attributes that are mass assignable.
	*
	* @var array
	 */

	protected $fillable = [ 'survey_id', 'question_id', 'created_by' ];
}

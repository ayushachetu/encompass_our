<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SurveyExtraQuestion extends Model {
	/**
	* The database table used by the model.
	*
	* @var string
	 */

	protected $table = 'survey_extra_questions';

	/**
	* The attributes that are mass assignable.
	*
	* @var array
	 */

	protected $fillable = [ 'name', 'matrix', 'image', 'comment',  'es_name', 'es_matrix', 'es_image', 'es_comment' ];
}
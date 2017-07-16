<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IndustryQuestion extends Model {

	/**
	* The database table used by the model.
	*
	* @var string
	 */

	protected $table = 'industry_question';

	/**
	* The attributes that are mass assignable.
	*
	* @var array
	 */

	protected $fillable = [ 'industry_id', 'question_id', 'created_by' ];

}
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model {

	/**
	* The database table used by the model.
	*
	* @var string
	 */

	protected $table = 'questions';

	/**
	* The attributes that are mass assignable.
	*
	* @var array
	 */

	protected $fillable = [ 'name', 'priority', 'created_by', 'matrix', 'image', 'comment',  'es_name', 'es_matrix', 'es_image', 'es_comment' ];

}
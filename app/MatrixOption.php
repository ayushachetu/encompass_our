<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MatrixOption extends Model {
	/**
	* The database table used by the model.
	*
	* @var string
	 */

	protected $table = 'matrix_options';

	/**
	* The attributes that are mass assignable.
	*
	* @var array
	 */

	protected $fillable = [ 'en_option', 'es_option', 'created_by', 'is_deleted' ];
}
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Industry extends Model {
	/**
	* The database table used by the model.
	*
	* @var string
	 */

	protected $table = 'industry';

	/**
	* The attributes that are mass assignable.
	*
	* @var array
	 */

	protected $fillable = [ 'industry_id', 'name', 'employee_number', 'active' ];
}
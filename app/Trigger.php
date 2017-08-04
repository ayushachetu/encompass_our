<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trigger extends Model {
	/**
	* The database table used by the model.
	*
	* @var string
	 */

	protected $table = 'triggers';

	/**
	* The attributes that are mass assignable.
	*
	* @var array
	 */

	protected $fillable = [ 'recipients_by_roles', 'custom_recipients', 'on_action', 'execution_time', 'data_to_send', 'custom_message',  'jobs', 'created_by', 'is_deleted', 'execution_unit' ];
}
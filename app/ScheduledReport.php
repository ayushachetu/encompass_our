<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScheduledReport extends Model {
	/**
	* The database table used by the model.
	*
	* @var string
	 */

	protected $table = 'scheduled_jobs';

	/**
	* The attributes that are mass assignable.
	*
	* @var array
	 */

	protected $fillable = [ 'is_recursive', 'frequency', 'send_on', 'recipients_by_roles', 'custom_recipients', 'custom_message',  'report_range', 'report_type', 'primary_filter', 'secondary_filter', 'created_by' ];
}
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table="quote";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'job_number','correlative','subject', 'description','client_name','client_email', 'total', 'discount','tax' ,'minutes', 'notes','status', 'draft','exported','start_date','managed_by', 'discount_field' , 'unit_field' , 'email_quote' , 'action_at', 'created_at'];


}

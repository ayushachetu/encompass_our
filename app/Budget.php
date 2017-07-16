<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table="budget";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['job_number','hours','date','monday_hours','tuesday_hours', 'wednesday_hours', 'thursday_hours', 'friday_hours', 'saturday_hours', 'sunday_hours'];


}

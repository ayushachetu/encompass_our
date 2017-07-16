<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Timekeeping extends Model
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table="timekeeping";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'winteam_id','job_number','employee_number', 'work_date', 'hours', 'lunch', 'overtimehours' ,'pay_rate'];



}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Billable_hours extends Model
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table="billable_hours";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['employee_number','job_number', 'work_date','regular_hours','lunch_hours', 'pay_rate', 'overtime_hours', 'square_foots', 'status'];


    


}

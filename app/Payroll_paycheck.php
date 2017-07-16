<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payroll_paycheck extends Model
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table="payroll_paycheck";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['employee_number','check_number','check_date','total'];



}

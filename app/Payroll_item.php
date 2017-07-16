<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payroll_item extends Model
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table="payroll_item";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'payroll_id','ssn','employee_number', 'birth_day', 'first_name', 'last_name', 'address' , 'city', 'state', 'job_title', 'postal_code', 'hire_date', 'rehire_date', 'department_name', 'employee_location_id', 'fein', 'pay_period_start_date', 'pay_period_end_date', 'pay_check_date', 'hours', 'hour_rate', 'employment_type', 'wages_type','part_full', 'overtimehours', 'wages'];



}

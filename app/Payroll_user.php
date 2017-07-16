<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payroll_user extends Model
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table="payroll_user";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id','ssn','employee_number', 'birth_date', 'first_name', 'last_name', 'address' , 'city', 'state', 'postal_code', 'hire_date', 'type', 'rate', 'job_title', 'part_full'];



}

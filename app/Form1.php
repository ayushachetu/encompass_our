<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Form1 extends Model
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table="form1";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['email','account_number', 'account_name', 'customer_name' , 'customer_email' , 'customer_cellphone' , 'scope_work', 'job_location', 'target_start', 'labor_hours','employee_pay_rate', 'material_cost', 'sub_contractor'];



}

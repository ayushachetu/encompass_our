<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Budget_monthly extends Model
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table="budget_monthly";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['monthly_budget','period1','period2','period3','period4', 'period5', 'period6', 'period7', 'period8', 'period9', 'period10', 'period11', 'period12','account_number','budget_type', 'fiscal_year','job_number','fs','jc'];


}

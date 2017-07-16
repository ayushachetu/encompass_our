<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Budget_gl extends Model
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table="budget_gl";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['job_number','period','period1','period2','period3','period4', 'period5', 'period6', 'period7', 'period8', 'period9', 'period10', 'period11', 'period12'];


}

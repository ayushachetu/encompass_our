<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table="expense";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['expense_type', 'job_number','amount','account_number', 'posting_date','type' ,'status'];


}

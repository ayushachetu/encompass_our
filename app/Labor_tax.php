<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Labor_tax extends Model
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table="labor_tax";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['account_number', 'job_number','daily_budget_id', 'budget_hour', 'budget_amount' ,'fica','futa','suta', 'workmans_compensation', 'medicare','date'];


}

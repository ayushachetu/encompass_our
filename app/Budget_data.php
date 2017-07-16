<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Budget_data extends Model
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table="budget_data";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['job_number','budget_hours','budget_dollars','fica','futa', 'suta', 'workmans_compensation', 'medicare', 'total', 'period'];


}

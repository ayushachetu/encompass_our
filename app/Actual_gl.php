<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Actual_gl extends Model
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table="actual_gl";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['job_number', 'actual_total', 'period'];


}

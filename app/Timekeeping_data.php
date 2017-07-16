<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Timekeeping_data extends Model
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table="timekeeping_data";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'job_number', 'hours', 'lunch', 'total', 'period'];



}

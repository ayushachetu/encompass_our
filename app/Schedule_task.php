<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule_task extends Model
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table="schedule_task";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','number', 'type'];



}

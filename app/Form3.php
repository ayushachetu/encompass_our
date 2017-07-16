<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Form3 extends Model
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table="form3";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','email', 'date_training', 'comment'];



}

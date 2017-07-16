<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Form4 extends Model
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table="form4";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','email', 'employee_name', 'employee_number', 'question_1' ,'question_2' ,'question_3' ,'question_4' ,'question_5' , 'question_6' , 'question_7' , 'question_8' , 'question_9' , 'question_10' ,'comment'];



}

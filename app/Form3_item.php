<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Form3_item extends Model
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table="form3_item";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id_form3','employee_name','employee_number','account_number', 'date_training'];



}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Form2 extends Model
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table="form2";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','email','site_name', 'site_account_number', 'type' , 'value'];



}

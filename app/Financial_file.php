<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Financial_file extends Model
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table="financial_file";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id','name','email'];



}

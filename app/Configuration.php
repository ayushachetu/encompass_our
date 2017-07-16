<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table="configuration";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['variable', 'value'];




}

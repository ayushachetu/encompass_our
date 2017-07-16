<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table="evaluation";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'evaluate_user_id','parameter1','parameter2', 'parameter3', 'parameter4','parameter5', 'description', 'status'];


}

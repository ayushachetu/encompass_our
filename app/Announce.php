<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Announce extends Model
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table="announce";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id','title', 'message', 'closing_date', 'status','permission'];




}

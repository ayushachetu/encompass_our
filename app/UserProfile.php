<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table="user_profile";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id','address', 'city','state','zipcode', 'home_phone', 'note', 'birthday'];

    /**
    * Disable create date.
    *
    * @var boolean
    */
    public $timestamps= false;


}

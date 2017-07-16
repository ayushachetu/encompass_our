<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table="account";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['account_number', 'type_description','description','category_description', 'category_type_description', 'financial'];


}

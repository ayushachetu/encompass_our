<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quote_category extends Model
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table="quote_category";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['type_id','name', 'type', 'active'];


}

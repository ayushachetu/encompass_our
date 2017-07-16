<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quote_data_detail extends Model
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table="quote_data_detail";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['task_id','description_id','description','cost','active'];


}

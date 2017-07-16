<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quote_data extends Model
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table="quote_data";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['task_id','task_code','category_id','measure_type','minutes','data_subject', 'base_price', 'price','category_type_id', 'category_section_id'];


}

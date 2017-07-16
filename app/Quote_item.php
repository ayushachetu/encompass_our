<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quote_item extends Model
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table="quote_item";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['quote_id', 'quote_data_id', 'quote_task_id', 'quantity','item_subject', 'price','tax' ,'discount', 'minutes', 'base_minutes', 'total' ,'labor' ,'labor_hours', 'days', 'material', 'sub_contract','margin','custom_item','parent_id','order'];


}

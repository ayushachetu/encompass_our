<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deal_pipeline extends Model
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table="deal_pipeline";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['pipeline_id','label', 'active', 'display_order'];



}

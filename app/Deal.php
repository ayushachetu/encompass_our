<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table="deal";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['deal_id','name', 'hubspot_owner_id', 'amount', 'pipeline_id', 'deal_stage', 'create_date', 'close_date', 'account_manager', 'deal_vertical'];



}

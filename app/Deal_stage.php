<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deal_stage extends Model
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table="deal_stage";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['pipeline_id', 'stage_id','label', 'probability', 'active', 'display_order', 'close_won'];

    /*
    array (size=6)
  'stageId' => string '5a9f2dd6-b887-42cb-85b0-025090b4aefb' (length=36)
  'label' => string 'Stalled' (length=7)
  'probability' => float 0.5
  'active' => boolean true
  'displayOrder' => int 5
  'closedWon' => boolean false
    */



}

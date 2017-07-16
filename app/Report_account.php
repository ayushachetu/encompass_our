<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report_account extends Model
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table="report_account";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['account_number', 'level','type','format_id'];


}

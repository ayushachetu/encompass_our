<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Accounting_gl extends Model
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table="accounting_gl";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['job_number', 'total', 'level', 'period'];


}

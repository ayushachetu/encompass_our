<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pay extends Model
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table="pay";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['employee_number','job_number', 'work_date','hours','lunch', 'pay_rate', 'overtime_hours', 'pay_file_id'];

    /**
    * Disable create date.
    *
    * @var boolean
    */
    public $timestamps= false;


}

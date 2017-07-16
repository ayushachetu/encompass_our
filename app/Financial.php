<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Financial extends Model
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table="financial";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id_financial_file','invoice_id','vendor_number','invoice_number','po_number', 'invoice_date','invoice_amount', 'account_number','job_number', 'distribution_amount', 'work_ticket_number'];


}

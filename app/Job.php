<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table="job";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['job_number','job_description','type_number', 'supervisor_id', 'region','country', 'division','manager', 'service' , 'mayor_account','area_supervisor' ,'is_parent', 'parent_job', 'square_feet', 'active', 'report','address1', 'address2', 'city', 'state', 'zip','longitude','latitude'];

    /**
     * Scope a query to only include users of a given type.
     *
     */

    public function scopeCountPortfolio($query, $manager)
    {
        return $query->where('manager', $manager)
                    ->where('is_parent', 0)
                    ->count();
    }

    /**
     * Scope a query to only include users of a given type.
     *
     */

    public function scopeCountPortfolioList($query, $manager_array)
    {
        return $query->whereIn('manager', $manager_array)
                    ->where('is_parent', 0)
                    ->count();
    }



}

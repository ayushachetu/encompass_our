<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['first_name', 'last_name', 'middle_name', 'email', 'role', 'primary_job', 'employee_number', 'manager_id', 'area_supervisor_id', 'job_supervisor_id', 'manager_parent', 'area_supervisor_parent', 'supervisor_parent',  'classification_id' , 'supervisor_id', 'type_id' , 'hubspot_owner_id' ,'email_personal' , 'active' ,'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];


    public function profile()
    {
        return $this->hasOne('App\UserProfile');
    }


    public function getFullNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;    

    }

     /**
     * Check if this user belongs to a role
     *
     * @return bool
     */
    
    public function hasRole($role)
    {
        return $this->role == $role;
    }


     /**
     * Check if this user belongs to a role
     *
     * @return integer
     */
    
    public function getRole()
    {
        return $this->role;
    }

     /**
     * Check if this user belongs to a role
     *
     * @return integer
     */
    
    public function getId()
    {
        return $this->id;
    }

     /**
     * Check if this user belongs to a role
     *
     * @return integer
     */
    
    public function getManagerId()
    {
        return $this->manager_id;
    }

    /**
     * Check if this user belongs to a role
     *
     * @return integer
     */
    
    public function gePrimayJob()
    {
        return $this->primary_job;
    }


    /**
     * Scope a query to only include users of a given type.
     *
     */

    public function scopeCountManagerEmployee($query, $manager_id)
    {
        return $query->where('manager_parent', $manager_id)
                    ->where('active', 1)
                    ->count();
    }

    /**
     * Scope a query to only include users of a given type.
     *
     */

    public function scopeCountManagerListEmployee($query, $manager_id_array)
    {
        return $query->whereIn('manager_parent', $manager_id_array)
                    ->where('active', 1)
                    ->count();
    }

    /**
     * Scope a query to only include users of a given type.
     *
     */

    public function scopeCountRoleListEmployee($query, $role, $user_id)
    {
        return $query->where('role', $role)
                      ->where('id', '<>' ,$user_id)
                      ->where('active', 1)
                      ->count();
    }

    /**
     * Scope a query to only include users of a given type.
     *
     */

    public function scopeCountManagerList($query)
    {
        return $query->where('manager_id', '<>' ,0)
                    ->where('active', 1)
                    ->count();
    }

}

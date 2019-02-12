<?php

namespace App;

use Illuminate\Foundation\Auth\User as  Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use SoftDeletes, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 'middlename', 'lastname', 'email', 'password', 'role', 'usergroup_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get the user group that owns the user.
     */
    public function usergroup()
    {
        return $this->belongsTo('App\Usergroup');
    }

    /**
     * Check if user's role equals superadmin
     *
     * @return bool
     */
    public function isSuperAdmin()
    {
        return ('superadmin' == $this->role);
    }

    /**
     * Check if user's role equals admin
     *
     * @return bool
     */
    public function isAdmin()
    {
        return (\Auth::user()->role == 'admin');
    }

    /**
     * Check if user's role equals employee
     *
     * @return bool
     */
    public function isEmployee()
    {
        return (\Auth::user()->role == 'employee');
    }

    /**
     * Check if user's role equals accountant
     *
     * @return bool
     */
    public function isAccountant()
    {
        return (\Auth::user()->role == 'accountant');
    }


}

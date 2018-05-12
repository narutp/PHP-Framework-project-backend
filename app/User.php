<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'lineid', 'profile_picture', 'address', 'is_admin', 'type', 'facebook', 'phone_number', 'supervisor_id', 'department'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function subordinates()
    {
        return $this->hasMany('App\User', 'supervisor_id');
    }

    public function supervisor()
    {
        return $this->belongsTo('App\User', 'supervisor_id');
    }

    public function tasks()
    {
        return $this->hasMany('App\Task', 'assignee_id');
    }

    public function leaves()
    {
        return $this->hasMany('App\Leave', 'user_id');
    }

    public function leaveSubstitute()
    {
        return $this->hasMany('App\Leave', 'substitute_id');
    }

    public function reassignTask()
    {
        return $this->hasMany('App\Task', 'reassignee_id')
    }
}

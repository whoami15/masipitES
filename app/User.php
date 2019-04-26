<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tbl_users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['username', 'password', 'role'];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token','role'];

    protected $appends = ['full_name'];

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->middle_name} {$this->last_name}";
    }

    public function learning_material(){
        return $this->hasMany('App\LearningMaterial', 'user_id', 'id');
    }

    public function grade_level_user(){
        return $this->belongsTo('App\GradeLevel', 'grade_level', 'id');
    }

    public function announcement(){
        return $this->belongsTo('App\Announcement', 'user_id', 'id');
    }

    public function security_keys(){
        return $this->hasMany('App\SecurityKeys', 'used_by_user_id', 'id');
    }

    public function lrn(){
        return $this->hasMany('App\Lrn', 'used_by_user_id', 'id');
    }

    public function news(){
        return $this->hasMany('App\News', 'user_id', 'id');
    }

    public function events(){
        return $this->hasMany('App\Events', 'user_id', 'id');
    }

    public function public_message(){
        return $this->hasMany('App\PublicMessage', 'user_id', 'id');
    }

    public function private_message(){
        return $this->hasMany('App\PrivateMessage', 'user_id', 'id');
    }
    
   
}

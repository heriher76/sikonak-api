<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'hp', 'status', 'address', 'photo', 'id_family', 'gcmtoken'
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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //Fix no role named blabla
    protected $guard_name = 'api';

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function family()
    {
        return $this->belongsTo('App\Family', 'id_family');
    }

    public function events()
    {
        return $this->hasMany('App\Event', 'id_user');
    }

    public function location()
    {
        return $this->hasOne('App\Location', 'id_user');
    }

    public function messages()
    {
        return $this->hasMany('App\Message', 'id_user');
    }

    public function logger()
    {
        return $this->hasOne('App\Logger', 'id_user');
    }

    public function timer()
    {
        return $this->hasOne('App\Timer', 'id_user');
    }
}

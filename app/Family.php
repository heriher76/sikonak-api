<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    public function users()
    {
        return $this->hasMany('App\User', 'id_family');
    }

    public function events()
    {
        return $this->hasMany('App\Event', 'id_family');
    }

    public function messages()
    {
      return $this->hasMany('App\Message', 'id_family');
    }
}

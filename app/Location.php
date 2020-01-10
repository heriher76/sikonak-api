<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lat', 'long', 'id_user'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'id_user');
    }
}

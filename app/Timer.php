<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Timer extends Model
{
    protected $fillable = ['until', 'id_user'];

    public function user()
    {
        return $this->belongsTo('App\User', 'id_user');
    }
}

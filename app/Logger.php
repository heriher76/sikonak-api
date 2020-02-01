<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Logger extends Model
{
    protected $fillable = ['status', 'id_user'];

    public function user()
    {
        return $this->belongsTo('App\User', 'id_user');
    }
}

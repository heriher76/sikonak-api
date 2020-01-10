<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'date', 'description', 'reminder', 'id_family', 'id_user'
    ];

    public function family()
    {
        return $this->belongsTo('App\Family', 'id_family');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'id_user');
    }
}

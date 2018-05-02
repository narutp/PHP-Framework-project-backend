<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    protected $fillable = [
        'status', 'type', 'start_date', 'end_date', 'substitute_id', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}

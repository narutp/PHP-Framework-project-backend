<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Leaves extends Model
{
    protected $fillable = [
        'status', 'type', 'start_date', 'end_date', 'substitute_id'
    ];
}

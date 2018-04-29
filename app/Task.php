<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tasks extends Model
{
    protected $fillable = [
        'task-name', 'description', 'start_date', 'end_date', 'assignee_id'
    ];
}

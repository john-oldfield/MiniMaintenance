<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public function UserTask()
    {
        return $this->belongsTo('App\UserTask');
    }
}

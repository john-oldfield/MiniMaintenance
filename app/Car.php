<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Car extends Model
{
    protected $fillable = [
        'mileage', 'model_id', 'make_id', 'engine_id', 'user_id'
    ];

    public function User(){
        return $this->belongsTo('App\User');
    }

    public function Make(){
        return $this->belongsTo('App\Make');
    }

    public function Model(){
        return $this->belongsTo('App\MiniModel');
    }

    public function Engine(){
        return $this->belongsTo('App\Engine');
    }
}

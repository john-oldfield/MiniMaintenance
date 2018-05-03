<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserTask extends Model
{
    protected $fillable = [
        'milesSince', 'daysSince', 'task_id', 'car_id', 'completed'
    ];

    public function Task(){
        return $this->belongsTo('App\Task');
    }

    public static function countOverdue(){
        $user_id = auth()->user()->id;
        $car = Car::find($user_id);
        $userTasks = UserTask::all()->where('car_id', '=', $car->id);

        $i = 0;
        foreach($userTasks as $ut)
        {
            if($ut->milesSince > $ut->Task->freqInMiles || $ut->daysSince > $ut->Task->freqInDays)
            {
                $i++;
            }
        }

        return $i;
    }

    public static function countUpcoming(){

        $user_id = auth()->user()->id;
        $car = Car::find($user_id);
        $userTasks = UserTask::all()->where('car_id', '=', $car->id);

        $i = 0;
        foreach($userTasks as $ut)
        {
            $milesTilDue = $ut->Task->freqInMiles - $ut->milesSince;
            $daysTilDue = $ut->Task->freqInDays - $ut->daysSince;

            if($milesTilDue <= 25 && $milesTilDue >= 0|| $daysTilDue <= 3 && $daysTilDue >=0)
            {
                $i++;
            }
        }
        return $i;
    }
}

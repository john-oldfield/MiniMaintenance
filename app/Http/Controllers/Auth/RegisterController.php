<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Car;
use App\Task;
use App\UserTask;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'make' => 'required',
            'model' => 'required',
            'engine' => 'required',
            'mileage' => 'required|numeric'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $car = Car::create([
            'mileage' => $data['mileage'],
            'make_id' => $data['make'],
            'model_id' => $data['model'],
            'engine_id' => $data['engine'],
            'user_id' => $user->id
        ]);

        $tasks = Task::all();
        foreach($tasks as $task)
        {
            UserTask::create([
                'milesSince' => 0,
                'daysSince' => 0,
                'task_id' => $task->id,
                'car_id' => $car->id,
                'completed' => 0
            ]);
        }

        return $user;
    }
}

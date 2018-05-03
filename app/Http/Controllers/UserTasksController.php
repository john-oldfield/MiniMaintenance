<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserTask;
use App\ServiceHistory;
use App\User;

class UserTasksController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $user_id = auth()->user()->id;
        $user = User::find($user_id);

        $data = UserTask::where('car_id', $user->Car->id)->get();
        $userTasks = UserTask::where('car_id', $user->Car->id)->paginate(10);
        $arr = array();
        foreach($data as $ut)
        {
            if($ut->milesSince > $ut->Task->freqInMiles && $ut->daysSince > $ut->Task->freqInDays)
            {
                $priority = array("Priority" => 1,  "Task"=> $ut);
                array_push($arr, $priority);
            }
            else if($ut->milesSince > $ut->Task->freqInMiles)
            {
                $priority = array("Priority" => 2,  "Task"=> $ut);
                array_push($arr, $priority);
            }
            else if($ut->daysSince > $ut->Task->freqInDays)
            {
                $priority = array("Priority" => 3,  "Task"=> $ut);
                array_push($arr, $priority);
            }
            else if(($ut->Task->freqInMiles - $ut->milesSince) <= 25 && ($ut->Task->freqInDays - $ut->daysSince) <= 3)
            {
                $priority = array("Priority" => 4,  "Task"=> $ut);
                array_push($arr, $priority);
            }
            else if(($ut->Task->freqInMiles - $ut->milesSince) <= 25)
            {
                $priority = array("Priority" => 5,  "Task"=> $ut);
                array_push($arr, $priority);
            }
            else if(($ut->Task->freqInDays - $ut->daysSince) <= 3)
            {
                $priority = array("Priority" => 6,  "Task"=> $ut);
                array_push($arr, $priority);
            }
            else
            {
                $priority = array("Priority" => 7,  "Task"=> $ut);
                array_push($arr, $priority);
            }
        }

        function sort_array_of_array(&$array, $subfield)
        {
            $sortarray = array();
            foreach ($array as $key => $row)
            {
                $sortarray[$key] = $row[$subfield];
            }

            array_multisort($sortarray, SORT_ASC, $array);
        }

        sort_array_of_array($arr, 'Priority');
        $collection = array();
        for($i = 0; $i < count($arr); $i++)
        {
            array_push($collection, $arr[$i]["Task"]);
        }
        
        return view('dashPages.todo')->with('userTasks', $collection);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'amount' => 'required|numeric',
            'receipt' => 'image|nullable|max:1999'
        ]);

        //Handle File Upload
        if($request->hasFile('receipt'))
        {
            //Get filename with extension
            $fileNameWithExt = $request->file('receipt')->getClientOriginalName();
            //Get just filename
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            //Get just extension
            $extension = $request->file('receipt')->getClientOriginalExtension();
            //Filename to store (TIMESTAMPS USED TO ENSURE MULTIPLE USERS CANNOT OVERWRITE FILES WITH SAME NAME)
            $fileNameToStore = $fileName.'_'.time().'.'.$extension;
            //Upload Image
            $path = $request->file('receipt')->storeAs('public/receipts', $fileNameToStore);
        }

        $user_id = auth()->user()->id;

        //As user tasks are already generated from a given set of tasks
        //the store function stores completed tasks into the service history table
        $history = new ServiceHistory;
        $history->task = $request->input('task');
        $history->descrip = $request->input('notes');
        $history->amount = $request->input('amount');
        $history->completedBy = $request->input('completedBy');
        if($request->hasFile('receipt'))
        {
            $history->receipt = $fileNameToStore;
        }
        else
        {
            $history->receipt = null;
        }
        $history->user_id = $user_id;
        $history->save();

        $userTask = UserTask::find($request->input('task_id'));
        $userTask->daysSince = 0;
        $userTask->milesSince = 0;
        $userTask->completed = $userTask->completed + 1;
        $userTask->save();

        return redirect('/todo')->with('success', 'Task History Saved');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $userTask = UserTask::find($id);
        return view('dashPages.editTask')->with('userTask', $userTask);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

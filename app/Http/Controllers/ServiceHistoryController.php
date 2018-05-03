<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\UserTask;
use App\ServiceHistory;
use PDF;

class ServiceHistoryController extends Controller
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
        //Get Currently Logged In User
        $user_id = auth()->user()->id;
        $history = new ServiceHistory;

        //Possible Filters
        $columns = ['personal', 'garage', 'specialist'];

        //Currently Selected Queries
        $queries = [];

        $strings = count(request()->all());

        if($strings > 0)
        {
            foreach($columns as $column)
            {
                if(request()->has($column))
                {
                    if(request($column) == "y")
                    {
                        array_push($queries, $column);
                    }
                }
            }

            if(request()->has('sort'))
            {
                if(request('sort') == "amount")
                {
                    $history = $history->orderByDesc('amount');
                }
                if(request('sort') == "task")
                {
                    $history = $history->orderBy('task');
                }
            }

            if(request()->has('receipt'))
            {
                if(request('receipt') == "y")
                {
                    $history = $history->where('receipt', '!=', "");
                }
            }

            $history = $history->whereIn('completedBy', $queries);

        }
        if($strings == 1 && request()->has('sort'))
        {
            if(request('sort') == "amount")
            {
                $history = new ServiceHistory;
                $history = $history->where('user_id', '=', $user_id)->orderByDesc('amount');
                $queries['sort'] = request('sort');
            }
            if(request('sort') == "task")
            {
                $history = new ServiceHistory;
                $history = $history->where('user_id', '=', $user_id)->orderBy('task');
                $queries['sort'] = request('sort');
            }
        }
        if($strings == 1 && request()->has('receipt'))
        {
            $history = new ServiceHistory;
            $history = $history->where('receipt', '!=', "");
            $queries['receipt'] = request('receipt');
        }
        else
        {
            $history = $history->where('user_id', '=', $user_id);   
        }        

        //Paginate Results
        $history = $history->paginate(10)->appends([
            'garage' => request('garage'),
            'personal' => request('personal'),
            'specialist' => request('specialist'),
            'sort' => request('sort'), 
            'receipt' => request('receipt')
        ]);

        return view('dashPages.serviceHistory', compact('history'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
    
    public function viewPdf()
    {
        $user_id = auth()->user()->id;
        $user = User::find($user_id);
        $history = new ServiceHistory;
 
        //Possible Filters
        $columns = ['personal', 'garage', 'specialist'];

        //Currently Selected Queries
        $queries = [];

        $strings = count(request()->all());
        if($strings > 0)
        {
            foreach($columns as $column)
            {
                if(request()->has($column))
                {
                    if(request($column) == "y")
                    {
                        array_push($queries, $column);
                    }
                }
            }

            if(request()->has('sort'))
            {
                if(request('sort') == "amount")
                {
                    $history = $history->orderByDesc('amount');
                }
                if(request('sort') == "task")
                {
                    $history = $history->orderBy('task');
                }
            }

            if(request()->has('receipt'))
            {
                if(request('receipt') == "y")
                {
                    $history = $history->where('receipt', '!=', "");
                }
            }

            $history = $history->whereIn('completedBy', $queries);

        }
         
        $history = $history->where('user_id', '=', $user_id)->get(); 
        $pdf = PDF::loadView('dashPages.historyPDF', compact('history', 'user'));
        return $pdf->stream('servicehistory.pdf');
    }
}

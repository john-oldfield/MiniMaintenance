<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Expenditure;
use App\User;

class ExpenditureController extends Controller
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
        $expenditures = new Expenditure;
        $expenditures = Expenditure::where('user_id', $user_id)->get();
        $colors = ["#0074D9", "#FF4136", "#2ECC40", "#FF851B", "#7FDBFF", "#B10DC9", "#FFDC00", "#001f3f", "#39CCCC", "#01FF70", "#85144b", "#F012BE", "#3D9970", "#111111", "#AAAAAA"];
        
        if(request()->has('timeSpan'))
        {
            if(request('timeSpan') == 'monthly')
            {
                //Bar Chart
                $todaysDate = date('Y-m-d H:i:s');
                $lastWeeksDate = date('Y-m-d H:i:s', strtotime("-1 month"));
                $begin = new \DateTime($lastWeeksDate);
                $end   = new \DateTime($todaysDate);
                $expenditures = $expenditures->where('created_at', '>', $lastWeeksDate);
                //Array of Dates
                $dates = array();
                //Array of Expenditure Arrays
                $data = array();
                //Array of Categories for Pie Chart
                $categories = array();
                //Array of Category Data
                $catData = array();

                for($i = $begin; $i <= $end; $i->modify('+1 day'))
                {
                    $loopDate = $i->format('d-m-Y');
                    array_push($dates, $loopDate);
                    foreach($expenditures as $e)
                    {
                        if(!in_array($e->category, $categories))
                        {
                            array_push($categories, $e->category);
                        }
                        
                        $recordDate = $e->created_at->format('d-m-Y');
                        if($recordDate == $loopDate)
                        {
                            array_push($catData, array($e->category => $e->amount));
                            //Array of Expenditure
                            $arr = array($loopDate => $e->amount);
                            array_push($data, $arr);
                        }
                    }
                }

                //Totals Per Day
                $catNumbers = array();
                foreach($catData as $value)
                    $catNumbers = array_merge($catNumbers, $value);

                foreach($catNumbers as $key => &$value)
                    $value = array_sum(array_column($catData, $key));
                
                $catVals = array();
                foreach($categories as $cats)
                {
                    $val = $catNumbers[$cats];
                    array_push($catVals, $val);
                }

                $pieChart = app()->chartjs
                ->name('pieChart')
                ->type('pie')
                ->size(['width' => 400, 'height' => 200])
                ->labels($categories)
                ->datasets([
                    [
                        'backgroundColor' => $colors,
                        'data' => $catVals
                    ]
                ])
                ->options([]);

                //Totals Per Day
                $amountOnDay = array();
                foreach($data as $value)
                    $amountOnDay = array_merge($amountOnDay, $value);

                foreach($amountOnDay as $key => &$value)
                    $value = array_sum(array_column($data, $key));
                $vals1 = array_values($data);

                $arrlength = count($dates);
                $expenditureValues = array();

                for($x = 0; $x < $arrlength; $x++) 
                {
                    $date = $dates[$x];
                    //If there is no value for a given day set value to 0
                    if(isset($amountOnDay[$date]) && $amountOnDay[$date] > 0)
                    {
                        array_push($expenditureValues, $amountOnDay[$date]);
                    }
                    else
                    {
                        array_push($expenditureValues, 0);
                    }
                }

                $expenditureBarChart = app()->chartjs
                ->name('ExpenditureBarChart')
                ->type('bar')
                ->size(['width' => 400, 'height' => 200])
                ->labels($dates)
                ->datasets([
                    [
                        "label" => "Spent",
                        'backgroundColor' => '#003366',
                        'data' => $expenditureValues
                    ]
                ])
                ->optionsRaw([
                    'legend' => [
                        'display' => false
                    ],
                    'scales' => [
                        'yAxes' => [
                            [
                                'ticks' => [
                                    'beginAtZero' => true,
                                    
                                ]
                            ]
                        ],
                        'xAxes' => [
                            [
                                'gridLines' =>[
                                    'display' => false
                                ]
                            ]
                                ]
                    ]
                ]);

                return view('dashPages.expenditure.expenditure', compact('pieChart', 'expenditureBarChart'))->with('expenditures', $expenditures);
            }
            if(request('timeSpan') == 'yearly')
            {
                 //Bar Chart
            $todaysDate = date('Y-m-d H:i:s');
            $lastWeeksDate = date('Y-m-d H:i:s', strtotime("-1 year"));
            $begin = new \DateTime($lastWeeksDate);
            $end   = new \DateTime($todaysDate);
            $expenditures = $expenditures->where('created_at', '>', $lastWeeksDate);
            //Array of Dates
            $dates = array();
            //Array of Expenditure Arrays
            $data = array();
            //Array of Categories for Pie Chart
            $categories = array();
            //Array of Category Data
            $catData = array();

            for($i = $begin; $i <= $end; $i->modify('+1 month'))
            {
                $loopDate = $i->format('m-Y');
                array_push($dates, $loopDate);
                foreach($expenditures as $e)
                {
                    if(!in_array($e->category, $categories))
                    {
                        array_push($categories, $e->category);
                    }
                    

                    $recordDate = $e->created_at->format('m-Y');
                    if($recordDate == $loopDate)
                    {
                        array_push($catData, array($e->category => $e->amount));
                        //Array of Expenditure
                        $arr = array($loopDate => $e->amount);
                        array_push($data, $arr);
                    }
                }
            }

            //Totals Per Day
            $catNumbers = array();
            foreach($catData as $value)
                $catNumbers = array_merge($catNumbers, $value);

            foreach($catNumbers as $key => &$value)
                $value = array_sum(array_column($catData, $key));
            
            $catVals = array();
            foreach($categories as $cats)
            {
                $val = $catNumbers[$cats];
                array_push($catVals, $val);
            }

            $pieChart = app()->chartjs
            ->name('pieChart')
            ->type('pie')
            ->size(['width' => 400, 'height' => 200])
            ->labels($categories)
            ->datasets([
                [
                    'backgroundColor' => $colors,
                    'data' => $catVals
                ]
            ])
            ->options([]);

            //Totals Per Day
            $amountOnDay = array();
            foreach($data as $value)
                $amountOnDay = array_merge($amountOnDay, $value);

            foreach($amountOnDay as $key => &$value)
                $value = array_sum(array_column($data, $key));
            $vals1 = array_values($data);

            $arrlength = count($dates);
            $expenditureValues = array();

            for($x = 0; $x < $arrlength; $x++) 
            {
                $date = $dates[$x];
                //If there is no value for a given day set value to 0
                if(isset($amountOnDay[$date]) && $amountOnDay[$date] > 0)
                {
                    array_push($expenditureValues, $amountOnDay[$date]);
                }
                else
                {
                    array_push($expenditureValues, 0);
                }
            }

            $expenditureBarChart = app()->chartjs
            ->name('ExpenditureBarChart')
            ->type('bar')
            ->size(['width' => 400, 'height' => 200])
            ->labels($dates)
            ->datasets([
                [
                    "label" => "Spent",
                    'backgroundColor' => '#003366',
                    'data' => $expenditureValues
                ]
            ])
            ->optionsRaw([
                'legend' => [
                    'display' => false
                ],
                'scales' => [
                    'yAxes' => [
                        [
                            'ticks' => [
                                'beginAtZero' => true,
                                
                            ]
                        ]
                    ],
                    'xAxes' => [
                        [
                            'gridLines' =>[
                                'display' => false
                            ]
                        ]
                            ]
                ]
            ]);

            return view('dashPages.expenditure.expenditure', compact('pieChart', 'expenditureBarChart'))->with('expenditures', $expenditures);
            }
        }
        else
        {
            //Bar Chart
            $todaysDate = date('Y-m-d H:i:s');
            $lastWeeksDate = date('Y-m-d H:i:s', strtotime("-1 week"));
            $begin = new \DateTime($lastWeeksDate);
            $end   = new \DateTime($todaysDate);
            $expenditures = $expenditures->where('created_at', '>', $lastWeeksDate);
            //Array of Dates
            $dates = array();
            //Array of Expenditure Arrays
            $data = array();
            //Array of Categories for Pie Chart
            $categories = array();
            //Array of Category Data
            $catData = array();

            for($i = $begin; $i <= $end; $i->modify('+1 day'))
            {
                $loopDate = $i->format('d-m-Y');
                array_push($dates, $loopDate);
                foreach($expenditures as $e)
                {
                    if(!in_array($e->category, $categories))
                    {
                        array_push($categories, $e->category);
                    }
                    

                    $recordDate = $e->created_at->format('d-m-Y');
                    if($recordDate == $loopDate)
                    {
                        array_push($catData, array($e->category => $e->amount));
                        //Array of Expenditure
                        $arr = array($loopDate => $e->amount);
                        array_push($data, $arr);
                    }
                }
            }

            //Totals Per Day
            $catNumbers = array();
            foreach($catData as $value)
                $catNumbers = array_merge($catNumbers, $value);

            foreach($catNumbers as $key => &$value)
                $value = array_sum(array_column($catData, $key));
            
            $catVals = array();
            foreach($categories as $cats)
            {
                $val = $catNumbers[$cats];
                array_push($catVals, $val);
            }

            $pieChart = app()->chartjs
            ->name('pieChart')
            ->type('pie')
            ->size(['width' => 400, 'height' => 200])
            ->labels($categories)
            ->datasets([
                [
                    'backgroundColor' => $colors,
                    'data' => $catVals
                ]
            ])
            ->options([]);

            //Totals Per Day
            $amountOnDay = array();
            foreach($data as $value)
                $amountOnDay = array_merge($amountOnDay, $value);

            foreach($amountOnDay as $key => &$value)
                $value = array_sum(array_column($data, $key));
            $vals1 = array_values($data);

            $arrlength = count($dates);
            $expenditureValues = array();

            for($x = 0; $x < $arrlength; $x++) 
            {
                $date = $dates[$x];
                //If there is no value for a given day set value to 0
                if(isset($amountOnDay[$date]) && $amountOnDay[$date] > 0)
                {
                    array_push($expenditureValues, $amountOnDay[$date]);
                }
                else
                {
                    array_push($expenditureValues, 0);
                }
            }

            $expenditureBarChart = app()->chartjs
            ->name('ExpenditureBarChart')
            ->type('bar')
            ->size(['width' => 400, 'height' => 200])
            ->labels($dates)
            ->datasets([
                [
                    "label" => "Spent",
                    'backgroundColor' => '#003366',
                    'data' => $expenditureValues
                ]
            ])
            ->optionsRaw([
                'legend' => [
                    'display' => false
                ],
                'scales' => [
                    'yAxes' => [
                        [
                            'ticks' => [
                                'beginAtZero' => true,
                                
                            ]
                        ]
                    ],
                    'xAxes' => [
                        [
                            'gridLines' =>[
                                'display' => false
                            ]
                        ]
                            ]
                ]
            ]);

            return view('dashPages.expenditure.expenditure', compact('pieChart', 'expenditureBarChart'))->with('expenditures', $expenditures);
        }
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
        $this->validate($request, [
            'category' => 'required',
            'cost' => 'required|numeric|min:0'
        ]);

        $user_id = auth()->user()->id;

        $expenditure = new Expenditure;
        $expenditure->category = $request->input('category');
        $expenditure->descrip = $request->input('description');
        $expenditure->amount = $request->input('cost');
        $expenditure->user_id = $user_id;
        $expenditure->save();

        return redirect()->to('/expenses');
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
}

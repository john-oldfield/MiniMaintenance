<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Fuel;
use App\User;
use App\Car;
use App\Expenditure;
use App\UserTask;

class FuelController extends Controller
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
        $fuel = new Fuel;
        $fuel = Fuel::where('user_id', $user_id);

        if(request()->has('timeSpan'))
        {
            if(request('timeSpan') == 'monthly')
            {
            //FILL UPS BAR CHART
            $todaysDate = date('Y-m-d H:i:s');
            $lastMonthsDate = date('Y-m-d H:i:s', strtotime("-1 month"));
            $begin = new \DateTime($lastMonthsDate);
            $end   = new \DateTime($todaysDate);
            $fuel = $fuel->where(['user_id' => $user_id])->whereBetween('created_at', [$lastMonthsDate, $todaysDate])->get();
            $dates = array();
            $data = array();
            $mpg = array();
            $litres = array();
            
            for($i = $begin; $i <= $end; $i->modify('+1 day'))
            {
                $loopDate = $i->format('d-m-Y');
                array_push($dates, $loopDate);
                foreach($fuel as $f)
                {
                    $recordDate = $f->created_at->format('d-m-Y');
                    if($recordDate == $loopDate)
                    {
                        $n = array($loopDate => 1);
                        $m = array($loopDate => $f->mpg);
                        $l = array($loopDate => $f->litres);
                        array_push($data, $n);
                        array_push($mpg, $m);
                        array_push($litres, $l);
                    }
                }
            }

            $litresOnDay = array();
            foreach($litres as $value)
                $litresOnDay = array_merge($litresOnDay, $value);

            foreach($litresOnDay as $key => &$value)
                $value = array_sum(array_column($litres, $key));
            $vals1 = array_values($litres);

            $arrlength = count($dates);
            $litresValues = array();

            for($x = 0; $x < $arrlength; $x++) {
                $date = $dates[$x];
                if(isset($litresOnDay[$date]) && $litresOnDay[$date] > 0)
                {
                    array_push($litresValues, $litresOnDay[$date]);
                }
                else
                {
                    array_push($litresValues, 0);
                }
            }

            //Calculate Average MPG
            $averageMpgs = array();
            $mpgValues = array();
            foreach($mpg as $val)
            {
                $averageMpgs[key($val)][] = $val[key($val)];

            }
            foreach($averageMpgs as $k=>&$v){
                $v    = array_sum($v)/count($v);
                array_push($mpgValues, $v);
            }

            $averageDates = array_keys($averageMpgs);

            $totalsOnDay = array();
            foreach($data as $value)
                $totalsOnDay = array_merge($totalsOnDay, $value);

            foreach($totalsOnDay as $key => &$value)
                $value = array_sum(array_column($data, $key));
            $vals1 = array_values($totalsOnDay);
            
            $arrlength = count($dates);

            $dataset = array();

            for($x = 0; $x < $arrlength; $x++) {
                $date = $dates[$x];
                if(isset($totalsOnDay[$date]) && $totalsOnDay[$date] > 0)
                {
                    array_push($dataset, $totalsOnDay[$date]);
                }
                else
                {
                    array_push($dataset, 0);
                }
            }

            $chartjs = app()->chartjs
            ->name('fillupsBarChart')
            ->type('bar')
            ->size(['width' => 400, 'height' => 200])
            ->labels($dates)
            ->datasets([
                [
                    "label" => "Fillups",
                    'backgroundColor' => '#003366',
                    'data' => $dataset
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
                                'beginAtZero' => true
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

            $litresBarChart = app()->chartjs
            ->name('litresBarChart')
            ->type('bar')
            ->size(['width' => 400, 'height' => 200])
            ->labels($dates)
            ->datasets([
                [
                    "label" => "Litres",
                    'backgroundColor' => '#003366',
                    'data' => $litresValues
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

        $lineChart = app()->chartjs
        ->name('lineChartTest')
        ->type('line')
        ->size(['width' => 400, 'height' => 200])
        ->labels($averageDates)
        ->datasets([
            [
                "label" => "Average MPG",
                "backgroundColor" => "transparent",
                'pointBackgroundColor' => "#007bff",
                'borderColor' => "#003366",
                'pointRadius' => '5',
                'lineTension' => '0',
                'data' => $mpgValues
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
                            'beginAtZero' => true
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

            return view('dashPages.fuel.fuel', compact('fuel', 'chartjs', 'lineChart', 'litresBarChart'))->with('car', $user->Car);
            }

        }

        if(request('timeSpan') == 'yearly')
        {
            //FILL UPS BAR CHART
            $todaysDate = date('Y-m-d H:i:s');
            $lastMonthsDate = date('Y-m-d H:i:s', strtotime("-1 year"));
            $begin = new \DateTime($lastMonthsDate);
            $end   = new \DateTime($todaysDate);
            $fuel = $fuel->where(['user_id' => $user_id])->whereBetween('created_at', [$lastMonthsDate, $todaysDate])->get();
            $dates = array();
            $formattedDates = array();
            $data = array();
            $mpg = array();
            $litres = array();
            
            for($i = $begin; $i <= $end; $i->modify('+1 month'))
            {
                $loopDate = $i->format('m-Y');
                array_push($dates, $loopDate);
                array_push($formattedDates, $i->format('M-Y'));
                foreach($fuel as $f)
                {
                    $recordDate = $f->created_at->format('m-Y');
                    if($recordDate == $loopDate)
                    {
                        $n = array($loopDate => 1);
                        $m = array($i->format('M-Y') => $f->mpg);
                        $l = array($loopDate => $f->litres);
                        array_push($data, $n);
                        array_push($mpg, $m);
                        array_push($litres, $l);
                    }
                }
            }

            $totalLitres = array();
            foreach($litres as $value)
                $totalLitres = array_merge($totalLitres, $value);

            foreach($totalLitres as $key => &$value)
                $value = array_sum(array_column($litres, $key));
            $vals1 = array_values($totalLitres);
            
            $arrlength = count($dates);

            $litresValues = array();

            for($x = 0; $x < $arrlength; $x++) {
                $date = $dates[$x];
                if(isset($totalLitres[$date]) && $totalLitres[$date] > 0)
                {
                    array_push($litresValues, $totalLitres[$date]);
                }
                else
                {
                    array_push($litresValues, 0);
                }
            }

            //Calculate Average MPG
            $averageMpgs = array();
            $mpgValues = array();
            foreach($mpg as $val)
            {
                $averageMpgs[key($val)][] = $val[key($val)];

            }
            foreach($averageMpgs as $k=>&$v){
                $v    = array_sum($v)/count($v);
                array_push($mpgValues, $v);
            }

            $averageDates = array_keys($averageMpgs);

            $totalsOnDay = array();
            foreach($data as $value)
                $totalsOnDay = array_merge($totalsOnDay, $value);

            foreach($totalsOnDay as $key => &$value)
                $value = array_sum(array_column($data, $key));
            $vals1 = array_values($totalsOnDay);
            
            $arrlength = count($dates);

            $dataset = array();

            for($x = 0; $x < $arrlength; $x++) {
                $date = $dates[$x];
                if(isset($totalsOnDay[$date]) && $totalsOnDay[$date] > 0)
                {
                    array_push($dataset, $totalsOnDay[$date]);
                }
                else
                {
                    array_push($dataset, 0);
                }
            }

        $chartjs = app()->chartjs
        ->name('fillupsBarChart')
        ->type('bar')
        ->size(['width' => 400, 'height' => 200])
        ->labels($formattedDates)
        ->datasets([
            [
                "label" => "Fillups",
                'backgroundColor' => '#003366',
                'data' => $dataset
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

        $lineChart = app()->chartjs
        ->name('lineChartTest')
        ->type('line')
        ->size(['width' => 400, 'height' => 200])
        ->labels($averageDates)
        ->datasets([
            [
                "label" => "Average MPG",
                "backgroundColor" => "transparent",
                'pointBackgroundColor' => "#007bff",
                'borderColor' => "#003366",
                'pointRadius' => '5',
                'lineTension' => '0',
                'data' => $mpgValues
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
                            'beginAtZero' => true
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

        $litresBarChart = app()->chartjs
            ->name('litresBarChart')
            ->type('bar')
            ->size(['width' => 400, 'height' => 200])
            ->labels($formattedDates)
            ->datasets([
                [
                    "label" => "Litres",
                    'backgroundColor' => '#003366',
                    'data' => $litresValues
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

        return view('dashPages.fuel.fuel', compact('fuel', 'chartjs', 'lineChart', 'litresBarChart'))->with('car', $user->Car);
   }
        else
        {
            $todaysDate = date('Y-m-d H:i:s');
            $lastWeeksDate = date('Y-m-d H:i:s', strtotime("-1 week"));
            $begin = new \DateTime($lastWeeksDate);
            $end   = new \DateTime($todaysDate);
            $fuel = $fuel->where(['user_id' => $user_id])->whereBetween('created_at', [$lastWeeksDate, $todaysDate])->get();
            $dates = array();
            $data = array();
            $mpg = array();
            $litres = array();
            
            for($i = $begin; $i <= $end; $i->modify('+1 day'))
            {
                $loopDate = $i->format('d-m-Y');
                array_push($dates, $loopDate);
                foreach($fuel as $f)
                {
                    $recordDate = $f->created_at->format('d-m-Y');
                    if($recordDate == $loopDate)
                    {
                        //Array to indicate a fillup 
                        $n = array($loopDate => 1);
                        //Array for single Mpg on a date
                        $m = array($loopDate => $f->mpg);
                        $l = array($loopDate => $f->litres);
                        array_push($data, $n);
                        array_push($mpg, $m);
                        array_push($litres, $l);
                    }
                }
            }

            $litresOnDay = array();
            foreach($litres as $value)
                $litresOnDay = array_merge($litresOnDay, $value);

            foreach($litresOnDay as $key => &$value)
                $value = array_sum(array_column($litres, $key));
            $vals1 = array_values($litres);

            $arrlength = count($dates);
            $litresValues = array();

            for($x = 0; $x < $arrlength; $x++) {
                $date = $dates[$x];
                if(isset($litresOnDay[$date]) && $litresOnDay[$date] > 0)
                {
                    array_push($litresValues, $litresOnDay[$date]);
                }
                else
                {
                    array_push($litresValues, 0);
                }
            }

            //Calculate Average MPG
            $averageMpgs = array();
            $mpgValues = array();
            foreach($mpg as $val)
            {
                $averageMpgs[key($val)][] = $val[key($val)];

            }
            foreach($averageMpgs as $k=>&$v){
                $v    = array_sum($v)/count($v);
                array_push($mpgValues, $v);
            }

            $averageDates = array_keys($averageMpgs);

            //Calculate Totals

            $totalsOnDay = array();
            foreach($data as $value)
                $totalsOnDay = array_merge($totalsOnDay, $value);

            foreach($totalsOnDay as $key => &$value)
                $value = array_sum(array_column($data, $key));
            $vals1 = array_values($totalsOnDay);
            

            $arrlength = count($dates);

            $dataset = array();

            for($x = 0; $x < $arrlength; $x++) {
                $date = $dates[$x];
                if(isset($totalsOnDay[$date]) && $totalsOnDay[$date] > 0)
                {
                    array_push($dataset, $totalsOnDay[$date]);
                }
                else
                {
                    array_push($dataset, 0);
                }
            }

            $litresBarChart = app()->chartjs
            ->name('litresBarChart')
            ->type('bar')
            ->size(['width' => 400, 'height' => 200])
            ->labels($dates)
            ->datasets([
                [
                    "label" => "Litres",
                    'backgroundColor' => '#003366',
                    'data' => $litresValues
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


            $chartjs = app()->chartjs
            ->name('fillupsBarChart')
            ->type('bar')
            ->size(['width' => 400, 'height' => 200])
            ->labels($dates)
            ->datasets([
                [
                    "label" => "Fillups",
                    'backgroundColor' => '#003366',
                    'data' => $dataset
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

        $lineChart = app()->chartjs
        ->name('lineChartTest')
        ->type('line')
        ->size(['width' => 400, 'height' => 200])
        ->labels($averageDates)
        ->datasets([
            [
                "label" => "Average MPG",
                "backgroundColor" => "transparent",
                'pointBackgroundColor' => "#007bff",
                'borderColor' => "#003366",
                'pointRadius' => '5',
                'lineTension' => '0',
                'data' => $mpgValues
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
                            'beginAtZero' => true
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
            
        }

        //Average MPG Line Chart


        return view('dashPages.fuel.fuel', compact('fuel', 'chartjs', 'lineChart', 'litresBarChart'))->with('car', $user->Car);
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
            'newMileage' => 'required|numeric|min:'.$request->input('oldMileage'),
            'cost' => 'required|numeric|min:0',
            'litres' => 'required|numeric|min:0' 
        ]);

         
        $gallons = (($request->input('litres') * 0.21997));
        $mpg = (($request->input('newMileage') - $request->input('oldMileage')) / $gallons);
        $user_id = auth()->user()->id;

        $fuel = new Fuel;
        $fuel->oldMiles = $request->input('oldMileage');
        $fuel->newMiles = $request->input('newMileage');

        $car = Car::where('user_id', $user_id)->first();
        $user_id = auth()->user()->id;
        $miles = ($request->input('newMileage') - $request->input('oldMileage'));
        $tasks = UserTask::all()->where('car_id', '=', $car->id);
        foreach($tasks as $task)
        {
            $milesSince = $task->milesSince;
            $task->update(['milesSince' => ($milesSince + $miles)]);
        }

        $fuel->litres = $request->input('litres');
        $fuel->mpg = $mpg;
        $fuel->fillupCost = $request->input('cost');
        $fuel->user_id = $user_id;
        $fuel->save();

        $expenditure = new Expenditure;
        $expenditure->category = "Fuel";
        $expenditure->descrip = "Auto Added when Logging Fillup";
        $expenditure->amount = $request->input('cost');
        $expenditure->user_id = $user_id;
        $expenditure->save();

        
        $car->mileage = $request->input('newMileage');
        $car->save();
        return redirect()->to('/fuel');

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

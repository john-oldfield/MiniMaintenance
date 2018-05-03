<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserTask;
use App\Task;
use App\User;
use App\Expenditure;
use DB;
use App\Car;
use App\Event;
use App\Fuel;

class DashboardController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        $user_id = auth()->user()->id;
        $user = User::find($user_id);
        $fuel = new Fuel;
        $fuel = Fuel::where('user_id', $user_id);

        $expenditures = new Expenditure;
        $expenditures = Expenditure::where('user_id', $user_id)->get();

        $colors = ["#0074D9", "#FF4136", "#2ECC40", "#FF851B", "#7FDBFF", "#B10DC9", "#FFDC00", "#001f3f", "#39CCCC", "#01FF70", "#85144b", "#F012BE", "#3D9970", "#111111", "#AAAAAA"];

        $todaysDate = date('Y-m-d H:i:s');
        $lastWeeksDate = date('Y-m-d H:i:s', strtotime("-1 week"));
        $begin = new \DateTime($lastWeeksDate);
        $end   = new \DateTime($todaysDate);
        $fuel = $fuel->where(['user_id' => $user_id])->whereBetween('created_at', [$lastWeeksDate, $todaysDate])->get();
        $dates = array();
        $data = array();
        $mpg = array();
        $litres = array();
        $categories = array();
        $catData = array();

        $pieData = array();
            
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

                foreach($expenditures as $e)
                {
                    $recordDate = $e->created_at->format('d-m-Y');
                    if($recordDate == $loopDate)
                    {
                        if(!in_array($e->category, $categories))
                        {
                            array_push($categories, $e->category);
                        }

                        array_push($catData, array($e->category => $e->amount));
                        //Array of Expenditure
                        $arr = array($loopDate => $e->amount);
                        array_push($pieData, $arr);
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
                            
        return view('dashboard', compact('lineChart', 'pieChart'))->with('car', $user->Car);
    }

    public function maintenanceRating()
    {
        $user_id = auth()->user()->id;
        $car = Car::where('user_id', $user_id)->get()->first();
        $tasks = UserTask::all()->where('car_id', $car->id);
        $tasks = count($tasks->where('completed', '>=', 1));
        $nTasks = count(Task::all());
        return round((($tasks/$nTasks) * 100));
    }

    public function updateMileage(Request $request, $id)
    {

        $user_id = auth()->user()->id;
        $car = Car::find($id);
        $carOldMileage = $car->mileage;
        
        $this->validate($request, [
            'mileage' => 'required|numeric|min:'.$carOldMileage
        ]);
        $newMileage = $request->input('mileage');
        $miles = $newMileage - $carOldMileage;
        $tasks = UserTask::all()->where('car_id', '=', $car->id);
        foreach($tasks as $task)
        {
            $milesSince = $task->milesSince;
            $task->update(['milesSince' => ($milesSince + $miles)]);
        }
        $car->mileage = $request->input('mileage');
        $car->save();

        return redirect()->to('/dashboard');
    }

    public function returnEvents()
    { 
        $todaysDate = date('Y-m-d h:i:s');
        $events = Event::all()->where('starttime', '>', $todaysDate)->toJson();
        return $events;
    }
}
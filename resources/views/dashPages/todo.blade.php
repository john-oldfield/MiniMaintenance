@extends('layouts.app')

@section('content')
<div id="todo-container"> 
    @if(count($userTasks) > 0) {{-- Check to make sure there are some tasks being returned --}}
    @foreach($userTasks as $ut)
    <div class="card">
        <div class="card-body">
        <h5>{{$ut->Task->descrip}}</h5>
            <span>Last completed on: {{$ut->updated_at->toDateString()}}</span>
            @switch($ut)
                {{-- OVERDUE IN BOTH DAYS AND MILES --}}
                @case($ut->milesSince > $ut->Task->freqInMiles && $ut->daysSince > $ut->Task->freqInDays)
                            <span class='todo-overdue'>Overdue by: {{abs($ut->milesSince - $ut->Task->freqInMiles)}} miles</span>
                            <span class='todo-overdue'>Overdue by: {{abs($ut->daysSince - $ut->Task->freqInDays)}} days</span>
                    @break
                {{-- OVERDUE IN MILES --}}
                @case($ut->milesSince > $ut->Task->freqInMiles)
                        <span class='todo-overdue'>Overdue by: {{abs($ut->milesSince - $ut->Task->freqInMiles)}} miles</span>
                        <span>Due in: {{abs($ut->daysSince - $ut->Task->freqInDays)}} days</span>
                    @break
                {{-- OVERDUE IN DAYS --}}
                @case($ut->daysSince > $ut->Task->freqInDays)
                        <span>Due in: {{abs($ut->milesSince - $ut->Task->freqInMiles)}} miles</span>
                        <span class='todo-overdue'>Overdue by: {{abs($ut->daysSince - $ut->Task->freqInDays)}} days</span>
                    @break
                {{-- DUE SOON IN BOTH DAYS AND MILES --}}
                @case(($ut->Task->freqInMiles - $ut->milesSince) <= 25 && ($ut->Task->freqInDays - $ut->daysSince) <= 3)
                        <span class='todo-due'>Due in: {{abs($ut->milesSince - $ut->Task->freqInMiles)}} miles</span>
                        <span class='todo-due'>Due in: {{abs($ut->daysSince - $ut->Task->freqInDays)}} days</span>
                    @break
                {{-- DUE SOON IN MILES --}}
                @case(($ut->Task->freqInMiles - $ut->milesSince) <= 25)
                        <span class='todo-due'>Due in: {{abs($ut->milesSince - $ut->Task->freqInMiles)}} miles</span>
                        <span>Due in: {{abs($ut->daysSince - $ut->Task->freqInDays)}} days</span>
                    @break
                {{-- DUE SOON IN DAYS --}}
                @case(($ut->Task->freqInDays - $ut->daysSince) <= 3)
                        <span>Due in: {{abs($ut->milesSince - $ut->Task->freqInMiles)}} miles</span>
                        <span class='todo-due'>Due in: {{abs($ut->daysSince - $ut->Task->freqInDays)}} days</span>
                    @break
                {{-- NO TASKS DUE --}}
                @case(($ut->Task->freqInDays - $ut->daysSince) > 3 || ($ut->Task->freqInMiles - $ut->milesSince) > 25)
                        <span>Due in: {{abs($ut->milesSince - $ut->Task->freqInMiles)}} miles</span>
                        <span>Due in: {{abs($ut->daysSince - $ut->Task->freqInDays)}} days</span>
                @break
            @endswitch
            <a href="/todo/{{$ut->id}}/edit" class="btn btn-primary float-right">Complete</a>
        </div>
    </div>
    @endforeach
@endif
</div>
@endsection
   
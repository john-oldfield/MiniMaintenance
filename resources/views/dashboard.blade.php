@extends('layouts.app')

@section('content')
    <div class="card-deck">
        @include("inc.dashItems.rating")
        @include("inc.dashItems.updateMileage")
        @include("inc.dashItems.taskSummary")        
    </div>
    <div class="card-deck">
        @include("inc.dashItems.avgMPG")
        @include("inc.dashItems.fuelCosts")
        @include("inc.dashItems.expenditurePie")
    </div>
    <div class="card-deck">
        @include("inc.dashItems.eventList")
    </div> 
@endsection
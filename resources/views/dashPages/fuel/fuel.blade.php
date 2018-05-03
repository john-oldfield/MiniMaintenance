@extends('layouts.app')

@section('content')   

<div class="card-deck">
        @include('dashPages.fuel.addMPG')
        @include('dashPages.fuel.mpg')
    </div>
    <div class="card-deck">
        @include('dashPages.fuel.litres')  
        @include('dashPages.fuel.fillups')
    </div>
    <div class="card-deck">
    @include('dashPages.fuel.recent')
    </div>
@endsection
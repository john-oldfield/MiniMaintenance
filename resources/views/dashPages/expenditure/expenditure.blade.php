@extends('layouts.app')

@section('content')   
    <div class="card-deck row">
        @include('dashPages.expenditure.addExpenditure')
        @include('dashPages.expenditure.topSpending')
    </div>
    <div class="card-deck row">
        @include('dashPages.expenditure.expenditureBar')
        @include('dashPages.expenditure.expenditurePie')
    </div>
    <div class="card-deck row">
        @include('dashPages.expenditure.recentExpenditures')
    </div>
@endsection
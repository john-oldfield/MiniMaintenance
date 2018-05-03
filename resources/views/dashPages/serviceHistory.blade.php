@extends('layouts.app')

@section('content')

    @php
        if(request('personal')){
            $personal = request('personal');
            if($personal == 'y'){
                $personal = 'n';
            }
            else if($personal == 'n'){
                $personal = 'y';
            }
        }
        else {
            $personal = 'y';
        }

        if(request('garage')){
            $garage = request('garage');
            if($garage == 'y'){
                $garage = 'n';
            }
            else if($garage == 'n'){
                $garage = 'y';
            }
        }
        else {
            $garage = 'y';
        }

        if(request('specialist')){
            $specialist = request('specialist');
            if($specialist == 'y'){
                $specialist = 'n';
            }
            else if($specialist == 'n'){
                $specialist = 'y';
            }
        }
        else {
            $specialist = 'y';
        }

        if(request('receipt')){
            $receipt = request('receipt');
            if($receipt == 'y'){
                $receipt = 'n';
            }
            else if($receipt == 'n'){
                $receipt = 'y';
            }
        }
        else {
            $receipt = 'y';
        }
    @endphp
    <div id="filters">
            <span>Sort:</span>
            <a href="{{route('serviceHistory.index', [
                'garage' => request('garage'),
                'personal' => request('personal'),
                'specialist' => request('specialist'), 
                'sort' => 'amount', 
                'page' => request('page'),
                'receipt' =>request('receipt')
                ])}}"
                    class=
                    @if(request('sort') == "amount")
                        active-link
                    @endif>Amount</a> |
            <a href="{{route('serviceHistory.index', [
                'garage' => request('garage'),
                'personal' => request('personal'), 
                'specialist' => request('specialist'), 
                'sort' => 'task', 
                'page' => request('page'),
                'receipt' => request('receipt')])}}"
                    class=
                    @if(request('sort') == "task")
                        active-link
                    @endif>Task Name</a>
                    <br>
        <span>Filter:</span>
        <a href="{{route('serviceHistory.index', [
                'personal' => $personal,
                'garage' => request('garage'),
                'specialist' => request('specialist'),
                'sort' => request('sort'), 
                'receipt' => request('receipt')
            ])}}" 
            class=
            @if(request('personal') == "y")
                active-link
            @endif
            >Personal</a> |
        <a href="{{route('serviceHistory.index', [
            'garage' => $garage,
            'personal' => request('personal'),
            'specialist' => request('specialist'),
            'sort' => request('sort'), 
            'receipt' => request('receipt')
        ])}}"
            class=
        @if(request('garage') == "y")
            active-link
        @endif>Garage</a> |
        <a href="{{route('serviceHistory.index', [
            'garage' => request('garage'),
            'personal' => request('personal'),
            'specialist' => $specialist,
            'sort' => request('sort'), 
            'receipt' => request('receipt')
        ])}}"
            class=
            @if(request('specialist') == "y")
                active-link
            @endif>Specialist</a> |
        <a href="{{route('serviceHistory.index', [
            'garage' => request('garage'),
            'personal' => request('personal'),
            'specialist' => request('specialist'),
            'sort' => request('sort'), 
            'receipt' => $receipt
        ])}}"
            class=
            @if(request('receipt') == "y")
                active-link
            @endif>Receipts Only</a>
        <br>
        <!-- Bodged until better solution-->
        <a class="filter-button" href="/serviceHistory?personal=y&garage=y&specialist=y">Reset Filters</a>
        <a class="filter-button" href="{{route('viewPDF', [
            'garage' => request('garage'),
            'personal' => request('personal'), 
            'specialist' => request('specialist'),
            'sort' => request('sort'), 
            'receipt' => request('receipt')])}}">View as PDF</a>
    </div>
    
    @if(count($history) > 0)
        <div class="table-responsive">
            <table class="table" id="historyTable">
                <thead>
                <tr>
                    <th scope="col">Task</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Completed By</th>
                    <th scope="col">Notes</th>
                    <th scope = "col">Receipt</th>
                </tr>
                </thead>
                <tbody>
                @foreach($history as $hist)
                
                    <tr>
                    <th>{{$hist->task}}</th>
                        <td>£{{number_format($hist->amount,2)}}</td>
                        <td>{{$hist->completedBy}}</td>
                        <td>
                            @if(!empty($hist->descrip))
                                {{$hist->descrip}}      
                            @else
                            -
                            @endif
                        </td>
                        <td>
                            @if(!empty($hist->receipt))
                                <a href="/storage/receipts/{{$hist->receipt}}" target="_blank">Open</a>
                            @else
                            -
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{$history->links()}}
        </div>
    @else
        <h1>No Results Found.</h1>
    @endif
@endsection
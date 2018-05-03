<div class="card dash-card bg-light">
    <div class="card-header">Recent Fillups</div>
    <div class="card-block">
        <div class="table-responsive">
            <table class="table">
                <tr>
                    <th>Date</th>
                    <th>Odometer</th>
                    <th>Fuel (Litres)</th>
                    <th>Cost</th>
                    <th>Consumption</th>
                </tr>
                    @php $sorted = $fuel->sortByDesc('created_at')->take(4) @endphp
    
                    @foreach($sorted as $s)
                        <tr>
                            <td>{{$s->created_at->format('d-M-Y')}}</td>
                            <td>{{$s->newMiles}}</td>
                            <td>{{$s->litres}}</td>
                            <td>£{{number_format($s->fillupCost, 2)}}</td>
                            <td>{{$s->mpg}} mpg</td>
                        </tr>
                    @endforeach
                </table>
        </div>
    </div>
</div>
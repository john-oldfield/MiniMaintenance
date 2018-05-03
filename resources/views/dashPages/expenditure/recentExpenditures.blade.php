<div class="card dash-card bg-light">
        <div class="card-header">Recent Expenditures</div>
        <div class="card-block">
            <table class="table">
                <tr>
                    <th>Date</th>
                    <th>Category</th>
                    <th>Cost</th>
                    <th>Description</th>
                </tr>
                    @php $sorted = $expenditures->sortByDesc('created_at')->take(10) @endphp
    
                    @foreach($sorted as $s)
                        <tr>
                            <td>{{$s->created_at->format('d-M-Y')}}</td>
                            <td>{{$s->category}}</td>
                            <td>£{{number_format($s->amount, 2)}}</td>
                            <td>{{$s->descrip}}</td>
                        </tr>
                    @endforeach
            </table>
        </div>
    </div>
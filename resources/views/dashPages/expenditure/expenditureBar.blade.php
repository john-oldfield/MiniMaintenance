<div class="card dash-card bg-light">
    <div class="card-header">
            @if(app('request')->input('timeSpan') == "")
            Expenditures this Week
        @elseif(app('request')->input('timeSpan') == "monthly")
            Expenditure this Month
        @elseif(app('request')->input('timeSpan') == "yearly")
            Expenditures this Year
        @endif
    </div>
    <div class="card-block">
        {!! $expenditureBarChart->render()!!} 
    </div>
</div>
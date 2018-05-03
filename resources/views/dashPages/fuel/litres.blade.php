<div class="card dash-card bg-light">
    <div class="card-header">
        @if(app('request')->input('timeSpan') == "")
            Litres this Week
        @elseif(app('request')->input('timeSpan') == "monthly")
            Litres this Month
        @elseif(app('request')->input('timeSpan') == "yearly")
            Litres this Year
        @endif
    </div>
    <div class="card-block">
        {!! $litresBarChart->render()!!}  
    </div>
</div>
<div class="card dash-card bg-light">
    <div class="card-header">
        @if(app('request')->input('timeSpan') == "")
            Fillups this Week
        @elseif(app('request')->input('timeSpan') == "monthly")
            Fillups this Month
        @elseif(app('request')->input('timeSpan') == "yearly")
            Fillups this Year
        @endif
    </div>
    <div class="card-block">
        <div id="fillup-bar-container">
            {!! $chartjs->render()!!} 
        </div>
    </div>
</div>
<div class="card dash-card text-center" id="totalSpent">
    <div class="container h-100">
        <div class="row h-100 justify-content-center align-items-center">
            <div class="col-12">
                <h2>
                    @if(app('request')->input('timeSpan') == "")
                        Total this Week
                    @elseif(app('request')->input('timeSpan') == "monthly")
                        Total this Month
                    @elseif(app('request')->input('timeSpan') == "yearly")
                        Total this Year
                    @endif
                </h2>
                <h1 class="card-text">£{{number_format($expenditures->sum('amount'), 2)}}</h1>
            </div>
        </div>  
    </div>
</div>
        
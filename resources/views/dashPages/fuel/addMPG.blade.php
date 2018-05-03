<div class="card dash-card bg-light">
    <div class="card-header">Log Fillup</div>
    <div class="card-block" id="addFillupForm">
        {!! Form::open(['action' => ['FuelController@store'], 'method' => 'POST']) !!}
        <div class="form-group">
            <p>Old Mileage: {{number_format($car->mileage)}}</p>
            {{Form::hidden('oldMileage', $car->mileage)}}
        </div>
        <div class="form-group">
            {{Form::text('newMileage', '', ['class' => 'form-control', 'placeholder' => 'New Mileage'])}}
        </div>
        <div class="form-group">
            {{Form::text('cost', '', ['class' => 'form-control', 'placeholder' => 'Fillup Cost'])}}
        </div>
        <div class="form-group">
                {{Form::text('litres', '', ['class' => 'form-control', 'placeholder' => 'Litres'])}}
            </div>
        {{Form::submit('Add', ['class'=>'btn btn-primary'])}}
        {!! Form::close()!!}
    </div>
</div>
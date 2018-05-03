<div class="card dash-card bg-light text-center">
    <div class="card-header">Update Mileage</div>
    <div class="card-block" id="updateMileageForm">
    {!! Form::open(['action' => ['DashboardController@updateMileage', $car->id], 'method' => 'POST']) !!}
        <p>Current Mileage: {{number_format($car->mileage)}}</p>
        <div class="form-group">
            {{Form::text('mileage', '', ['class' => 'form-control'])}}
        </div>
        {{Form::submit('Update', ['class'=>'btn btn-primary'])}}
    {!! Form::close()!!}
        </div>
</div>
<div class="card dash-card bg-light">
    <div class="card-header">Add Expenses</div>
    <div class="card-block" id="addExpenditureForm">
        {!! Form::open(['action' => ['ExpenditureController@store'], 'method' => 'POST']) !!}
        <div class="form-group">
            {{Form::select('category', ['Cleaning' => 'Cleaning', 'Events' => 'Event Fees', 'Fuel' => 'Fuel', 'Garage' => 'Garage Fees', 'Insurance' => 'Insurance', 'Parts' => 'Parts', 'Tax' => 'Tax', 'Tools' => 'Tools' ], null, ['placeholder' => 'Expense Category', 'class' => 'form-control'])}}
        </div>
        <div class="form-group">
            {{Form::text('cost', '', ['class' => 'form-control', 'placeholder' => 'Expense Cost'])}}
        </div>
        <div class="form-group">
            {{Form::textarea('description', '', ['id' => 'addExpenditureDropdown', 'class' => 'form-control', 'placeholder' => 'Enter a description of your expenditure...'])}}
        </div>
        {{Form::submit('Add', ['class'=>'btn btn-primary'])}}
        {!! Form::close()!!}
    </div>
</div>
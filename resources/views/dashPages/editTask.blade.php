@extends('layouts.non-dash')

@section('content')
<div class="container" id="editTaskContainer">
        <h1>Maintenance Completion</h1>
        {!! Form::open(['action' => 'UserTasksController@store', $userTask->id, 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
            <div class="form-group">
                {{Form::label('task', 'Task Name')}}
                {{Form::text('task', $userTask->Task->descrip, ['class' => 'form-control', 'readonly' => 'true'])}}
            </div>
            <div class="form-group">
                {{Form::label('notes', 'Task Notes')}}
                {{Form::textarea('notes', '', ['class' => 'form-control', 'placeholder' => 'e.g. MOT failure so was replaced.'])}}
            </div>
            <div class="form-group">
                {{Form::label('amount', 'Maintenance Cost')}}
                {{Form::text('amount', '0.00', ['class' => 'form-control'])}}
            </div>
            <div class="form-group">
                    {{Form::label('completedBy', 'Who was this maintenance completed by?')}}
                    {{Form::select('completedBy', array(
                        'personal' => 'Personally/Friend/Family', 
                        'garage' => 'General Car Garage',
                        'specialist'=> 'Mini/Classic Car Specialist'), 'Personally/Friend/Family', ['class' => 'form-control'])}}
            </div>
            <div class="form-group">
                {{Form::label('receipt', 'Receipt for Task')}}
                <br>
                {{Form::file('receipt')}}
            </div>
            {{Form::hidden('task_id', $userTask->id)}}
            {{Form::submit('Submit', ['class' => 'btn btn-primary'])}}
        {!! Form::close() !!}
</div>
        
@endsection
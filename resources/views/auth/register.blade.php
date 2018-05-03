@extends('layouts.non-dash')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card login-form">
                <div class="card-header">Register</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        
                        <!-- NAME INPUT -->
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <!-- EMAIL INPUT -->
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <!-- PASSWORD INPUT -->
                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                         <!-- PASSWORD CONFIRM INPUT -->
                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirm Password</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>
                        <hr>
                         <!-- MINI MAKE INPUT -->
                        <div class="form-group row">
                            <label for="mini-make" class="col-md-4 col-form-label text-md-right">Mini Make</label>
                            <div class="col-md-6">
                                <select class="form-control" id="make-select" name="make" required>
                                    <option selected value="">Please Select</option>
                                            <!-- Check if Makes Exists -->
                                            @if(count($makes)>0)
                                                <!-- Sort in case new makes are added -->
                                                @php $collection = $makes->sortBy('name'); @endphp
                                                @foreach($collection as $make)
                                                    <option value="{{$make->id}}">{{$make->name}}</option>
                                                @endforeach
                                                @else
                                                    <option>No Options Available.</option>
                                            @endif
                                </select>
                                @if ($errors->has('make-select'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('make-select') }}</strong>
                                    </span>
                                @endif
                            </div>     
                        </div>
                         <!-- MINI MODEL INPUT -->
                        <div class="form-group row">
                                <label for="mini-model" class="col-md-4 col-form-label text-md-right">Mini Model</label>
                                <div class="col-md-6">
                                    <select class="form-control" id="model-select" name="model" required>
                                            <option selected value="">Please Select</option>
                                            @if(count($models)>0)
                                                @php $collection = $models->sortBy('name'); @endphp
                                                @foreach($collection as $model)
                                                    <option value="{{$model->id}}">{{$model->name}}</option>
                                                @endforeach
                                                @else
                                                    <option>No Options Available.</option>
                                            @endif
                                    </select>
                                </div>     
                            </div>
                             <!-- ENGINE INPUT -->
                            <div class="form-group row">
                                    <label for="engine-select" class="col-md-4 col-form-label text-md-right">Mini Engine</label>
                                    <div class="col-md-6">
                                        <select class="form-control" id="engine-select" name="engine" required>
                                                <option selected value="">Please Select</option>
                                                @if(count($engines)>0)
                                                @php $collection = $engines->sortBy('cc'); @endphp
                                                @foreach($collection as $engine)
                                                    <option value="{{$engine->id}}">{{$engine->cc}} cc</option>
                                                @endforeach
                                                @else
                                                    <option>No Options Available.</option>
                                            @endif
                                        </select>
                                    </div>     
                                </div>
                                 <!-- MILEAGE INPUT -->
                                <div class="form-group row">
                                    <label for="mileage" class="col-md-4 col-form-label text-md-right">Mileage</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" id="mileage" name="mileage" required>
                                    </div>     
                                </div>
                        
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Register
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.admin')
@section('content')
<style>
    .input_addon {
        background: #7a7a7a;
        color: white;
    }

    .form-control:focus {
        border: 1px solid #eb7226;
        -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(235, 114, 38, 0.6);
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(235, 114, 38, , 0.6);
    }
    .default_option{
        background-color: #7a7a7a;
        color: white;
    }
    .form-control:disabled {
        background-color: #dddddd
    }
</style>
<div class="row ">
    <h1 class="text-center">New Agent</h1>
</div>
<form method="POST" action="{{ url('/admin/agents') }}" enctype="multipart/form-data" accept-charset="UTF-8">
    {{ csrf_field() }}
    <div class="row py-2">
        <div class="col-sm-6 col-xs-12">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text input_addon">Name</span>
                </div>
                <input class="form-control" type="text" name="name" id="user-name" value="{{ old('name') }}" required />
            </div>
            <div class="input-group mt-2">
                <div class="input-group-prepend">
                    <span class="input-group-text input_addon">Email</span>
                </div>
                <input class="form-control" type="email" name="email" id="email" value="{{ old('email') }}" required />
            </div>
            <div class="input-group mt-2">
                <div class="input-group-prepend">
                    <span class="input-group-text input_addon">Secondary Email</span>
                </div>
                <input class="form-control" type="email" name="secondary_email" id="secondary_email" value="{{ old('secondary_email') }}" required />
            </div>
            <div class="input-group mt-2">
                <div class="input-group-prepend">
                    <span class="input-group-text input_addon">Phone</span>
                </div>
                <input class="form-control" type="text" name="phone" id="phone" value="{{ old('phone') }}" required />
            </div>
            <div class="input-group mt-2">
                <div class="input-group-prepend">
                    <span class="input-group-text input_addon">CAM Account</span>
                </div>
                <input class="form-control" type="text" name="link" id="link" value="{{ old('cam_account') }}" required />
            </div>
            <div class="input-group mt-2">
                <div class="input-group-prepend">
                    <span class="input-group-text input_addon">Password</span>
                </div>
                <input class="form-control" type="password" name="password" id="password" value="{{ old('password') }}" required />
            </div>
            <div class="input-group mt-2">
                <div class="input-group-prepend">
                    <span class="input-group-text input_addon">Split</span>
                </div>
                <input 
                    class="form-control" 
                    type="number" 
                    name="split" 
                    id="split"
                    min="0"
                    max="100" 
                    required 
                />
                <div class="input-group-append">
                    <span class="input-group-text input_addon">%</span>
                </div>
            </div>
            <div class="input-group mt-2">
                <div class="input-group-prepend">
                    <span class="input-group-text input_addon">Crossover</span>
                </div>
                <input class="form-control" type="number" name="crossover" id="crossover" required />
                <div class="input-group-append">
                    <span class="input-group-text input_addon">$</span>
                </div>
            </div>
            <div class="input-group mt-2 mb-2">
                <div class="input-group-prepend">
                    <span class="input-group-text input_addon">Crossover Split</span>
                </div>
                <input 
                    class="form-control" 
                    type="number" 
                    name="crossover_split" 
                    id="crossover_split"
                    min="0"
                    max="100"
                    required 
                />
                <div class="input-group-append">
                    <span class="input-group-text input_addon">%</span>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xs-12">
            <div class="form-group text-center">
                <img id='img-upload' width="300px" height="300px" class="img-fluid" src="/img/no-photo.png" />
            </div>
            <div class="form-group custom-file mt-1">
                <input type="file" class="custom-file-input" name="photo" id="customFile">
                <label class="custom-file-label" for="customFile">Choose Agent Photo</label>
            </div>
            <div class="form-group row">
                <div class="col-sm-6 mt-2"><input class="btn btn-primary form-control" type="submit" value="Save"></div>
                <div class="col-sm-6 mt-2"><a href="{{ url('admin/agents') }}" class="btn form-control btn-danger">Cancel</a></div>
            </div>
        </div>
    </div>

</form>
    @include('errors.list')
@endsection
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
<h1>Edit Agent</h1>

<form method="POST"
    action="{{ route('agents.update', ['agent'=>$user->id]) }}"
    enctype="multipart/form-data"
    accept-charset="UTF-8">
    <input name="_method" type="hidden" value="PATCH">
    <input name="user_id" type="hidden" value="{{ $user->id }}"> {{ csrf_field() }}
    <div class="row py-2">
        <div class="col-sm-6 col-xs-12">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text input_addon">Name</span>
                </div>
                <input class="form-control" type="text" name="name" id="user-name" value="{{ old('name',$user->name) }}" required />
            </div>
            <div class="input-group mt-2">
                <div class="input-group-prepend">
                    <span class="input-group-text input_addon">CAM Account</span>
                </div>
                <input 
                    class="form-control" 
                    name="link"
                    type="text"
                    value="{{ old('cam_account', $camAccount) }}"
                    id="link" 
                    required 
                />
            </div>
            <div class="input-group mt-2">
                <div class="input-group-prepend">
                    <span class="input-group-text input_addon">Referrer Link</span>
                </div>
                <input 
                    class="form-control" 
                    type="text"
                    value="{{ route('agent.show', ['hash' => $user->getEncodeId()]) }}"
                    id="referrer-link" readonly
                />
            </div>
            <div class="input-group mt-2">
                <div class="input-group-prepend">
                    <span class="input-group-text input_addon">Email</span>
                </div>
                <input class="form-control" type="email" name="email" id="email" value="{{ old('email',$user->email) }}" required />
            </div>
            <div class="input-group mt-2">
                <div class="input-group-prepend">
                    <span class="input-group-text input_addon">Email</span>
                </div>
                <input 
                    class="form-control" 
                    name="secondary_email" 
                    type="text" 
                    value="{{ old('secondary_email', $user->secondary_email) }}" 
                    id="secondary_email" 
                    required 
                />
            </div>
            <div class="input-group mt-2">
                <div class="input-group-prepend">
                    <span class="input-group-text input_addon">Phone</span>
                </div>
                <input 
                    class="form-control" 
                    name="phone" 
                    type="text" 
                    value="{{ old('phone', $user->phone) }}" 
                    id="phone"
                    required
                />
            </div>
            <div class="input-group mt-2">
                <div class="input-group-prepend">
                    <span class="input-group-text input_addon">New Password</span>
                </div>
                <input 
                    class="form-control" 
                    name="password" 
                    type="text" 
                    value="{{ old('password') }}" 
                    id="password"
                />
            </div>
            <div class="input-group mt-2">
                <div class="input-group-prepend">
                    <span class="input-group-text input_addon">Split</span>
                </div>
                <input 
                    type="number" 
                    class="form-control" 
                    name="split" 
                    id="split" 
                    value="{{old('split', $user->split)}}"
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
                <input 
                    class="form-control" 
                    type="number" 
                    name="crossover" 
                    id="crossover" 
                    value="{{old('crossover',$user->crossover)}}" 
                    required 
                />
                <div class="input-group-append">
                    <span class="input-group-text input_addon">$</span>
                </div>
            </div>
            <div class="input-group mt-2">
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
                    value="{{old('crossover_split',$user->crossover_split)}}"
                    required 
                />
                <div class="input-group-append">
                    <span class="input-group-text input_addon">%</span>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xs-12">
            <div class="form-group text-center">
                <img id='img-upload' class="img-fluid" width="300px" height="300px" src="{{ $user->photo }}" />
            </div>
            <div class="form-group custom-file">
                <input type="file" class="custom-file-input" name="photo" id="customFile">
                <label class="custom-file-label" for="customFile">Choose Agent Photo</label>
            </div>
            <div class="form-group row mt-2">
                <div class="col-sm-6"><input class="btn btn-primary form-control" type="submit" value="Save"></div>
                <div class="col-sm-6"><a href="{{ url('admin/agents') }}" class="btn form-control btn-danger">Cancel</a></div>
            </div>
        </div>
        <input type="hidden" name="adminpanel" value="1">
    </div>
</form>

@include('errors.list')

@endsection

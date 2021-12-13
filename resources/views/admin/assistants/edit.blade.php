@extends('layouts.admin')

@section('content')

<h1>Edit Assistant</h1>

<form method="POST" action="{{ route('assistants.update', ['agent'=>$user->id]) }}"
    accept-charset="UTF-8">
    <input name="_method" type="hidden" value="PATCH">
    <input name="user_id" type="hidden" value="{{ $user->id }}">

    {{ csrf_field() }}
    <div class="form-group">
        <label for="user-name">Name:</label>
        <input class="form-control" name="name" type="text" value="{{ old('name', $user->name) }}" id="user-name">
    </div>
    <div class="form-group">
        <label for="email">Email:</label>
        <input class="form-control" name="email" type="text" value="{{ old('email', $user->email) }}" id="email">
    </div>

    <div class="form-group row">
        <div class="col-sm-6"><input class="btn btn-property form-control" type="submit" value="Save"></div>

    <div class="col-sm-6"><a href="{{ url('admin/assistants') }}" class="btn form-control btn-light">Cancel</a></div>
    </div>
</form>

@include('errors.list')

@endsection

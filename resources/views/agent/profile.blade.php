@extends('layouts.app')

@section('content')
<div class="container">

    <div class="jumbotron bg-white">


        <h1>Edit Profile</h1>

        <form method="POST"
              action="{{ route('agents.update', ['agent'=>$user->id]) }}"
              enctype="multipart/form-data"
              accept-charset="UTF-8">
            <input name="_method" type="hidden" value="PATCH">
            <input name="user_id" type="hidden" value="{{ $user->id }}"> {{ csrf_field() }}
            <div class="row py-2">
                <div class="col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label for="user-name">Name:</label>
                        <input class="form-control" name="name" type="text" value="{{ old('name', $user->name) }}" id="user-name">
                    </div>
                    <div class="form-group">
                        <label for="cam-account">CAM Account:</label>
                        <input class="form-control" name="link"
                               type="text"
                               value="{{ old('cam_account', $camAccount) }}"
                               id="link">
                    </div>
                    <div class="form-group">
                        <label for="referrer-link">Referrer Link:</label>
                        <input class="form-control"
                               type="text"
                               value="{{ route('agent.show', ['hash' => $user->getEncodeId()]) }}"
                               id="referrer-link" readonly>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input class="form-control" name="email" type="text" value="{{ old('email', $user->email) }}" id="email">
                    </div>
                    <div class="form-group">
                        <label for="secondary_email">Secondary Email:</label>
                        <input class="form-control" name="secondary_email" type="text" value="{{ old('secondary_email', $user->secondary_email) }}" id="secondary_email">
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone:</label>
                        <input class="form-control" name="phone" type="text" value="{{ old('phone', $user->phone) }}" id="phone">
                    </div>
                    <div class="form-group">
                        <label for="password">New Password:</label>
                        <input class="form-control" name="password" type="text" value="{{ old('password') }}" id="password">
                    </div>

                </div>
                <div class="col-sm-6 col-xs-12">
                    <div class="form-group text-center">
                        <img id='img-upload' class="img-fluid" src="{{ $user->photo ? \Storage::url($user->photo) : '/img/no-photo.png'}}" />
                    </div>
                    <div class="form-group custom-file">
                        <input type="file" class="custom-file-input" name="photo" id="customFile">
                        <label class="custom-file-label" for="customFile">Choose Agent Photo</label>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-6"><input class="btn btn-property form-control" type="submit" value="Save"></div>

                <div class="col-sm-6"><a href="{{ url('admin/agents') }}" class="btn form-control btn-light">Cancel</a></div>
            </div>

        </form>

        @include('errors.list')
    </div>
</div>
@endsection

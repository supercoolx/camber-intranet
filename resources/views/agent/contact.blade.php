@extends('layouts.app')

@section('content')
<div class="container pt-4">
    <div class="row">
        <div class="col-12">
            <h1>Refer A Friend</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
    <form method="POST" action="{{ route('agent.refer') }}" enctype="multipart/form-data" accept-charset="UTF-8">
        {{ csrf_field() }}
        <input type="hidden" name="agent" value="{{ $agent->id }}" />
        <div class="row py-2">
            <div class="col-sm-6 col-xs-12">
                <div class="form-group text-center">
                    <img class="img-fluid"
                        src="{{ $agent->photo ? \Storage::url($agent->photo) : '/img/refer-friend.png'}}" />
                </div>
                <div class="form-group">
                    <label class="">{{ $agent->name }}</label>
                </div>
                <div class="form-group">
                    <label class="">Phone: {{ $agent->phone ? $agent->phone : '' }}</label>
                </div>
                <div class="form-group">
                    <label class="">Please provide your friend's information and we'll reach out to them as soon as possible.</label>
                </div>
            </div>
            <div class="col-sm-6 col-xs-12">
                <div class="col-12">
                    <h2>Your Information</h2>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="user-name">Name</label>
                        <input class="form-control" name="name" type="text" value="{{ old('name') }}" id="user-name">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="user-last-name">Last Name</label>
                        <input class="form-control" name="last_name"
                            type="text" value="{{ old('last_name') }}" id="user-last-name">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-7">
                        <label for="email">Email:</label>
                        <input class="form-control" name="email"
                            placeholder="your@email-here.com"
                            type="text"
                            value="{{ old('email') }}"
                            id="email">
                    </div>
                    <div class="form-group col-md-5">
                        <label for="phone">Phone:</label>
                        <input class="form-control" name="phone" type="text" value="{{ old('phone') }}" id="phone">
                    </div>
                </div>

                <div class="col-12">
                    <h2>About Your Friend</h2>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="friend-name">Name</label>
                        <input class="form-control" name="friend_name" type="text" value="{{ old('friend_name') }}" id="friend-name">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="friend-last-name">Last Name</label>
                        <input class="form-control" name="friend_last_name"
                            type="text" value="{{ old('friend_last_name') }}" id="friend-last-name">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-7">
                        <label for="friend-email">Email:</label>
                        <input class="form-control" name="friend_email"
                            placeholder="their@email-here.com"
                            type="text"
                            value="{{ old('friend_email') }}"
                            id="friend-email">
                    </div>
                    <div class="form-group col-md-5">
                        <label for="friend-phone">Phone:</label>
                        <input class="form-control" name="friend_phone"
                            type="text" value="{{ old('friend_phone') }}" id="friend-phone">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-12">
                        <label for="notes">Notes</label>
                        <textarea class="form-control" name="notes" id="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-6">
                        <input class="btn btn-property form-control" type="submit" value="Send Now">
                    </div>
                </div>
            </div>
        </div>
    </form>
        </div>
    </div>
    @include('errors.list')
</div>
@endsection

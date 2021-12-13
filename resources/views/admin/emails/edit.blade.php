@extends('layouts.admin')

@section('content')
    <h1 class="mb-5">Edit Email Pattern for event <b>{{$email->event}}</b></h1>

    <div class="row">
        <div class="col-md-4">
            <div class="card mb-2">
                <div class="card-header">
                    <h4 class="mb-0">Global variables</h4>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        @foreach($global_vars as $global_var_key => $global_var_val)
                            <li class="list-group-itempx-3"><var><strong>{{$global_var_key}}</strong> {{$global_var_val ? '('.$global_var_val.')' : ''}}</var></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Specific variables for this event</h4>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        @foreach($specific_vars as $specific_var_key => $specific_var_val)
                            <li class="list-group-itempx-3"><var><strong>{{$specific_var_key}}</strong> {{$specific_var_val ? '('.$specific_var_val.')' : ''}}</var></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            @include('errors.list')
            <form method="POST" action="{{ route('emails.update', ['email' => $email->id]) }}"
                  accept-charset="UTF-8">
                <input name="_method" type="hidden" value="PATCH">
                <input name="user_id" type="hidden" value="{{ $email->id }}">

                {{ csrf_field() }}


                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input name="is_active" type="checkbox" value="1" class="custom-control-input" id="status" {{old('is_active', $email->is_active) == 1 ? 'checked' : ''}}>
                        <label class="custom-control-label" for="status">Enable</label>
                    </div>
                </div>

                <h3>Description</h3>
                <div class="form-group">
                    <textarea class="form-control" name="description" rows="2" id="description">{{ old('description', $email->description) }}</textarea>
                </div>

                <h3>Agent template</h3>
                <div class="form-group">
                    <label for="subject-agent">Subject:</label>
                    <input class="form-control" name="subject_agent" type="text" value="{{ old('subject_agent', $email->subject_agent) }}" id="subject-agent">
                </div>
                <div class="form-group">
                    <label for="body-agent">Body:</label>
                    <textarea class="form-control" name="body_agent" rows="6" id="body-agent">{{ old('body_agent', $email->body_agent) }}</textarea>
                </div>

                <hr class="my-4">

                <h3>Admin template</h3>
                <div class="form-group">
                    <label for="subject-agent">Subject:</label>
                    <input class="form-control" name="subject_admin" type="text" value="{{ old('subject_admin', $email->subject_admin) }}" id="subject-admin">
                </div>
                <div class="form-group">
                    <label for="body-agent">Body:</label>
                    <textarea class="form-control" name="body_admin" rows="6" id="body-admin">{{ old('body_admin', $email->body_admin) }}</textarea>
                </div>
                <div class="form-group row">
                    <div class="col-sm-6"><input class="btn btn-property form-control" type="submit" value="Save"></div>
                    <div class="col-sm-6"><a href="{{ url('admin/emails') }}" class="btn form-control btn-light">Cancel</a></div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
//        jQuery(document).ready(function ($) {
//
//        });
    </script>
@endpush
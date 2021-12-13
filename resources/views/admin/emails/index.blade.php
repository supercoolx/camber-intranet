@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 pb-1">
                <h2>Email Patterns</h2>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <table class="table" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th class="th-sm">Event</th>
                        <th class="th-sm">Patterns</th>
                        <th class="th-sm">Status</th>
                        <th class="th-sm w-25 text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($emails as $email)
                        <tr>
                            <td>
                                <b>{{$email->event}}</b>
                                <p><small>{{$email->description}}</small></p>
                            </td>
                            <td>
                                {!! $email->subject_agent ? '<h6 class="mb-0"><strong>subject_agent</strong></h6>'.'<small>'.$email->subject_agent.'</small>': '' !!}
                                {!! $email->body_agent ? '<h6 class="mb-0"><strong>body_agent</strong></h6>'.'<small>'.$email->body_agent.'</small>': '' !!}
                                {!! $email->subject_admin ? '<h6 class="mb-0"><strong>subject_admin</strong></h6>'.'<small>'.$email->subject_admin.'</small>': '' !!}
                                {!! $email->body_admin ? '<h6 class="mb-0"><strong>body_admin</strong></h6>'.'<small>'.$email->body_admin.'</small>': '' !!}
                            </td>
                            <td>
                                @if ($email->is_active)
                                    <span class="text-success text-nowrap"><i class="fas fa-power-off"></i> Enabled</span>
                                @else
                                    <span class="text-secondary text-nowrap"><i class="fas fa-power-off"></i> Disabled</span>
                                @endif
                            </td>
                            <td class="d-flex justify-content-center">
                                <a href="{{ route('emails.edit', ['id' => $email->id]) }}" class="btn btn-xs btn-property"><i class="fa fa-btn fa-edit"></i>Edit</a>&nbsp;
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection

@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12 pb-1">
            <h2>Agents</h2>
        </div>
    </div>
    @if (session('agent-added'))
        <div class="alert alert-success">
            {{ session('agent-added') }}
        </div>
    @endif

    <div class="row my-2 pb-2">
        <div class="col-12">
            <a href="{{ url('/admin/agents/create') }}" class="btn btn-success">Add New</a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <table id="dtBasicExample" class="table" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                        <th class="th-sm">Name</th>
                        <th class="th-sm">Email</th>
                        <th class="th-sm">Secondary Email</th>
                        <th class="th-sm">Created date</th>
                        <th class="th-sm w-25 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr style="vertical-align: middle;">
                                <td>{{$user->name}}</td>
                                <td>{{$user->email}}</td>
                                <td>{{$user->secondary_email}}</td>
                                <td>{{$user->created_at}}</td>
                                <td class="d-flex justify-content-center">
                                    <a href="{{ route('agents.edit', ['id' => $user->id]) }}" class="btn btn-xs btn-property"><i class="fa fa-btn fa-edit"></i> Edit</a>&nbsp;
                                    <form action="{{ route('agents.destroy', ['agent'=> $user->id ]) }}" method="post" class="form-inline">
                                            {{ csrf_field() }}
                                        <input name="_method" type="hidden" value="DELETE">
                                        <button type="submit" class="btn btn-xs btn-danger remove" onclick="return confirm('Are you sure you want to delete?');">
                                            <i class="fa fa-btn fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    </table>
        </div>
    </div>

</div>
@endsection

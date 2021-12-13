@extends('layouts.app')

@section('content')
    <div class="container pt-3 d-grid" style="grid-template-rows:1fr;">
        <div class="row">
            <div class="col-md-6 offset-md-1 mb-5">
                <form id="editForm" method="POST" action="{{ route('orders.update', ['order' => $order->id]) }}">
                    @method('PATCH')
                    @CSRF

                    <div class="form-group row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="pl-1">Agent: <b>{{$order->agent->name}}</b></label>

                                {{--<select name="agent" class="form-control" disabled>--}}
                                    {{--<option value="{{ $order->agent->id }}"> {{$order->agent->name}} </option>--}}
                                {{--</select>--}}
                                <label class="checked-header pl-1 pt-2 ml-4">
                                    <input style="display:none;" {{$checked == 'checked' ? 'checked' : ''}} type="checkbox"
                                           class="form-check-input" name="email_update_at" value="checked">
                                    <span class="check"></span>Email updates to me (agent)
                                </label>
                            </div>
                        </div>
                        <div class="col-6" style="visibility:hidden">
                            <div class="form-group">
                                <label class="pl-1">Choose Assistant</label>
                                <select name="assistant" class="form-control">
                                    @foreach ($assistants as $assistant)
                                        <option value="{{ $assistant->id }}"
                                                {{ $assistant->id == $order->assistant_id ? 'selected' : '' }}>
                                            {{ $assistant->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr>

                    @foreach ($orderData as $section => $subsections)
                        <div class="form-group form-check row mb-0 pl-1 mt-4">
                            <div class="col-9">
                                <a id="{{ str_replace(' ','_',$section) }}" href=""></a>
                                <h2>{{ $section }}</h2>
                            </div>
                        </div>
                        @foreach ($subsections as $subsectionId => $info)
                            <div class="form-group form-check row mb-0 pl-1">
                                <div class="col-9">
                                    <label class="checked-header{{$info['section_touched'] ? ' subsection-green' : ''}}">
                                        <input style="display:none;"
                                            type="checkbox"
                                            class="form-check-input"
                                            name="subsection[{{ $subsectionId }}]">
                                        <span class="check"></span>
                                        {{ $info['name'] }}
                                        @if($info['status'])
                                        {{ '- ' . $info['status'] }}
                                        @endif
                                    </label>
                                </div>
                            </div>
                        <div class="form-group form-check row mb-0 pl-5">
                            @if(isset($info['subheader']))
                                        <div class="d-block" style="margin-left: -2px;margin-top: -12px;">
                                        {{ $info['subheader'] }}
                                        </div>
                            @endif
                            @foreach ($info['fields'] as $field)
                                @switch($field->type)
                                    @case('text')
                                        @include ('orders.fields._input', ['field' => $field])
                                        @break
                                    @case('textarea')
                                        @include ('orders.fields._textarea', ['field' => $field])
                                        @break
                                    @case('radio')
                                        @include ('orders.fields._radio', ['field' => $field])
                                        @break
                                    @case('button')
                                        @include ('orders.fields._button', ['field' => $field])
                                        @break
                                    @default
                                        @include ('orders.fields._input', ['field' => $field])
                                @endswitch
                            @endforeach

                            <hr>
                        </div>
                        @endforeach
                    @endforeach

                    {{-- end of loop --}}

                    @include('errors.list')
                </form>
            </div>
            <div class="col-md-5 pl-5">
                <div class="sticky-top" style="top:77px;">
                    <form action="{{ route('orders.destroy', ['order'=> $order->id ]) }}" method="post" class="">
                        @CSRF
                        @method('DELETE')
                        <div class="form-group row pb-0 mb-0">
                            <div class="w-75" style="top:-25px;">
                                @foreach ($orderData as $section => $subsections)
                                @if (!$loop->first)
                                <a class="btn btn-link py-0" href="#{{ str_replace(' ','_',$section) }}">{{ $section }}</a>
                                @endif
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group row pb-0 mb-0">
                            <div class="alert alert-dark w-75" role="alert">
                                {{ $order->name }}
                            </div>
                        </div>
                        <div class="form-group row pt-0 mt-0">
                            <button type="submit" class="btn btn-xs btn-danger remove" onclick="return confirm('Are you sure you want to delete this File?');">
                                <i class="fa fa-btn fa-trash"></i> Delete File
                            </button>
                            &nbsp;
                            <button form="editForm" type="submit" class="btn btn-property w-12">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include ('orders.help')
@endsection

@extends('layouts.admin')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>
    <div id="app2">
     <div class="row">
         <div class="col-md-12">
             <sp-activity></sp-activity>
         </div>
     </div>

    </div>
<div class="container">
    <div class="row">
        <div class="col-12 pb-1">
            <h2>Requests</h2>
        </div>
    </div>
    <div class="row my-2 pb-2">
        <div class="col-12 text-right">

        <style>
            .nfc-hide:focus ~ .nfc-content {
                display: none;
                outline: none;
            }
            .nfc-show:focus ~ .nfc-content {
                display: block;
                outline: none;
            }

            .nfc-content{
                display: none;
            }
            a:active, a:focus {
                outline: 0;
                border: none;
                -moz-outline-style: none;
            }
            .pagination {
                margin-bottom: 0;
            }
            .page-item .page-link {
                padding: .5rem .5rem;
            }
            .btn-add-info {
                padding: 1px 10px;
            }
            .dropdown-menu__add-info {
                border: 2px solid #2176bd;
                background-color: #fffbf1;
                padding: 0;
            }
            .dropdown-menu__add-info b {
                color: #2176bd;
            }
        </style>

        <a href="#development" data-toggle="collapse">Features Development Status</a>
        <p id="development" class="collapse" >
            <iframe style="width: 700px; height: 400px;" src="https://magic.new.treng.net/export_property"></iframe>
        </p>

        </div>
    </div>
    <div class="row">
        <div class="col-12 text-right">

            <a data-toggle="modal"  href="#" data-target="#ModalAddRequest"  style="font-size: 21px;" >Create Request</a>
            {{--<a data-toggle="modal"  href="#" data-toggle="modal" data-target="#ModalListings" style="font-size: 21px;" >Create Request</a>--}}

            <div class="text-left mt-3">
                <div class="row align-items-center justify-content-center mb-3">
                    <div class="col-auto">
                        {{ $requests->appends(Illuminate\Support\Facades\Input::except('page'))->links() }}
                    </div>

                    <div class="col-auto">
                        <div class="filter-to-show">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="showAll" name="status" class="custom-control-input" value="all" {{request()->status == 'all' ? 'checked' : ''}}>
                                <label class="custom-control-label" for="showAll">All</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="showInProcess" name="status" class="custom-control-input" value="in_process" {{(request()->status == 'in_process' || !isset(request()->status)) ? 'checked' : ''}}>
                                <label class="custom-control-label" for="showInProcess">In process</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="showCompleted" name="status" class="custom-control-input" value="completed" {{request()->status == 'completed' ? 'checked' : ''}}>
                                <label class="custom-control-label" for="showCompleted">Completed</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="input-group filter-agent">
                            <div class="input-group-prepend">
                                <label class="input-group-text" for="inputAgent">Agent</label>
                            </div>
                            <select class="custom-select" id="inputAgent">
                                <option value="-1" selected>-- all agents --</option>
                                @foreach($agents as $agent)
                                    <option value="{{$agent->id}}" {{$agent->id == request()->agent ? 'selected' : ''}}>{{$agent->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-auto">
                        <a href="{{route('dashboard.index')}}" class="btn btn-primary">Reset</a>
                    </div>
                </div>

                <table id="requestsTable" class="table" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            @foreach(['address' => 'Property', 'request' => 'Request', 'agent_name' => 'Agent', 'date_modified' => 'Date', 'status' => 'Status'] as $key => $name)
                                <th class="th-sm {{in_array($key, ['address', 'status']) ? 'w-15 text-center' : ''}}" data-breakpoints="xs" >
                                    @if (!in_array($key, ['request', 'status']))
                                        <a href="{{ route('dashboard.index', $table_parameters[$key]) }}"
                                           class="sorting-link {{ (request()->orderby == $key) ? 'active' : ''}}">{{$name}}</a>
                                        <i class="sorting-indicator fas fa-sort-{{request()->order == 'asc' ? 'up up' : 'down down'}}"></i>
                                    @else
                                        {{$name}}
                                    @endif
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($requests) > 0)
                            @foreach ($requests as $request)
                            @php $id = $request->req_id ? $request->req_id : $request->id; @endphp
                            <tr id="request-{{$id}}">
                                <td>
                                    <p class="mb-1">{!! $request->address ?? '<span class="badge badge-secondary py-1 px-2 font-weight-normal">unassigned</span>' !!}</p>
                                    @if ( $r = App\OrderRequest::find($request->id) )
                                        <div class="dropdown">
                                            <button class="btn btn-primary btn-add-info dropdown-toggle " type="button" id="dropdownAddInfo" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Seller/Price Info
                                            </button>
                                            <div class="dropdown-menu dropdown-menu__add-info" aria-labelledby="dropdownAddInfo">
                                                <table class="additional-info-price-seller">
                                                    @if(is_object($r->order))
                                                        @foreach ($r->order->fields->where('subsection_id', 1) as $f)
                                                            <tr>
                                                                <td><b>{{$f->name}}</b></td>
                                                                <td>{{$f->pivot->value}}</td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </table>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                                <td>{!! $request->name !!}
                                    @if (\App\OrderRequest::isSpecialSubsection($request->subsection_id))
                                    <div class="hover-widget-wrapper">
                                        <a class="control" class="" href='#'>Change status</a>
                                        <div class="controlled">
                                            @foreach ($request->subsection as $key => $subsection)
                                                <a js-feature="with-loader"
                                                   js-click="Admin.updateSubsectionStatus({{ $id }}, {{ $key }})"
                                                   data-sub="{{ $subsection }}"
                                                   class="" id="subsection-{{ $key }}" href='#'>{{ $subsection }}</a>
                                            @endforeach
                                        </div>
                                    </div>
                                    @else
                                    <div class="hover-widget-wrapper">
                                        <a class="control" class="" href='#'>Change status</a>
                                        <div class="controlled">
                                            <a js-feature="with-loader"
                                               js-click="Admin.updateRequestStatus('In Process', {{ $id }})"
                                               class="" href='#'>In Process</a>
                                            <a js-feature="with-loader"
                                               js-click="Admin.updateRequestStatus('Completed', {{ $id }})"
                                               class="" href='#'>Completed</a>

                                        </div>
                                    </div>
                                    @endif
                                </td>
                                <td>{{$request->agent_name}}</td>
                                <td class="text-nowrap">
                                    @if($request->date_modified!='')
                                    {{ \Carbon\Carbon::parse($request->date_modified)->format('M d, Y')}}
                                    @endif
                                </td>
                                <td class="d-flex1 justify-content-center1 status-column" style="text-align:center; {{$request->status=='Completed' ? 'background-color: #cef9ce;' : ''}}">
                                    <span class="status-column-text">{{$request->status}}</span> &nbsp;&nbsp;&nbsp;
                                    <a  role="button" data-toggle="collapse" class="" href='#extended-{{$id}}'>
                                        <b><span style="font-size:1.1rem;"><i class="fas fa-angle-double-down"></i></span></b>
                                    </a>
                                </td>
                            </tr>

                            <tr id="extended-{{$id}}" class='extended-info collapse'>
                                <td colspan="5">
                                    <div>
                                        <label>Date</label>
                                        <input class="form-control" type='date' name="date_{{$id}}" value='{{ \Carbon\Carbon::parse($request->date_modified)->format("Y-m-d")}}'>
                                    </div>
                                    <div>
                                        @foreach ($request->details as $detail)
                                            <div>{!! $detail !!}</div>
                                        @endforeach
                                    </div>

                                    <div style="display:flex;margin-top:1rem;"><label style="display:inline-block;min-width:100px;align-self:center">Public Notes</label> <textarea style="flex-grow: 2;margin-left: 15px;" cols=60 name="public_notes_{{$id}}" > {{$request->public_notes}}</textarea>     </div>
                                    <div style="display:flex;margin-top:1rem"><label style="display:inline-block;min-width:100px;align-self:center">Private Notes</label> <textarea style="flex-grow: 2;margin-left: 15px;" cols=60 name="private_notes_{{$id}}" > {{$request->private_notes}}</textarea>   </div>
                                    <input type="hidden" name="request_id_{{$id}}" value="{{$id}}" />
                                    <button js-feature="with-loader"
                                            js-click="Admin.updateRequest(this)"
                                            type="button"
                                            class="btn btn-success mt-3 w-25"  >
                                        Update
                                    </button>
                                </td>
                                <td style="padding: 0"></td>
                                <td style="padding: 0"></td>
                                <td style="padding: 0"></td>
                                <td style="padding: 0"></td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>

                {{ $requests->appends(Illuminate\Support\Facades\Input::except('page'))->links() }}

            </div>
        </div>
    </div>

</div>
@include('forms.listings')
@include('admin.requests.addRequest')
@endsection
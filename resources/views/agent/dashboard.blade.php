@extends('layouts.app')

@push('stylesheets')
    <link href="{{ asset('css/footable.bootstrap.min.css') }}" rel="stylesheet">
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
@endpush

@section('content')

    <div class="container my-5">
        <div class="row">
            <div class="col-12 pb-1">
                <h2>Dashboard</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-right">

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
                            <a href="{{route('agent.dashboard')}}" class="btn btn-primary">Reset</a>
                        </div>
                    </div>

                    <table id="dtBasicExample" class="table table-responsive" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                @foreach(['address' => 'Property', 'request' => 'Request', 'date_modified' => 'Date', 'status' => 'Status'] as $key => $name)
                                    <th class="th-sm {{in_array($key, ['address', 'status']) ? 'w-15 text-center' : ''}}" data-breakpoints="xs" >
                                        @if (!in_array($key, ['request', 'status']))
                                            <a href="{{ route('agent.dashboard', $table_parameters[$key]) }}"
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
                                                        @foreach ($r->order->fields->where('subsection_id', 1) as $f)
                                                            <tr>
                                                                <td><b>{{$f->name}}</b></td>
                                                                <td>{{$f->pivot->value}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                    <td>{!! $request->name !!}</td>
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
                                            <dl>
                                                <dt>Date</dt>
                                                <dd>{{ \Carbon\Carbon::parse($request->date_modified)->format("Y-m-d")}}</dd>
                                                @if ($request->details)
                                                    <dt>Details</dt>
                                                    @foreach ($request->details as $detail)
                                                        <dd>{!! $detail !!}</dd>
                                                    @endforeach
                                                @endif
                                                @if ($request->public_notes)
                                                <dt>Public Notes</dt>
                                                <dd>{!! nl2br($request->public_notes) !!}</dd>
                                                @endif
                                            </dl>
                                        </div>

                                        {{--<div style="display:flex;margin-top:1rem;"><label style="display:inline-block;min-width:100px;align-self:center">Public Notes</label> <div style="flex-grow: 2;margin-left: 15px;" name="public_notes_{{$id}}" > {{$request->public_notes}}</div>     </div>--}}
                                        {{--<input type="hidden" name="request_id_{{$id}}" value="{{$id}}" />--}}
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

@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"  crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>
    <script
            src="https://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
            crossorigin="anonymous"></script>
    <script
            src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
            integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="
            crossorigin="anonymous"></script>
    <script
            src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"
            crossorigin="anonymous"></script>

    <script src="{{ asset('js/footable.min.js') }}"></script>
    <script src="{{ asset('js/admin.js') }}"></script>

@endpush
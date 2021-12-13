<div class="modal"  id="ModalAddRequest" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalCenterTitle"><b>Add New Request</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('orders_request.store')}}" method="post">
                    @CSRF
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="adHocForm" name="request_type" value="ad_hoc_form" class="custom-control-input" checked>
                        <label class="custom-control-label" for="adHocForm">Ad Hoc</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="newAddressForm" name="request_type" value="with_address_form" class="custom-control-input">
                        <label class="custom-control-label" for="newAddressForm">Listings</label>
                    </div>
                    <div class="row">
                        <div class="col-12" id="fieldCustomName">
                            <div class="mt-3">
                                <label>Request</label>
                                <input type="text" name="custom_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-12" id="fieldAddress">
                            <div class="mt-3">
                                <label>Address <span class="if-applicable">(if applicable)</span></label>
                                {{--<div class="input-group mb-3">--}}
                                    {{--<div class="input-group-prepend">--}}
                                        {{--<div class="input-group-text">--}}
                                            {{--<input type="checkbox">--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<input type="text" class="form-control" name="address" value="" >--}}
                                {{--</div>--}}

                                {{--<input class="form-control" name="address" value="" />--}}
                                <input class="form-control" list="listAddresses" name="address" value="" autocomplete="off"/>
                                <datalist id="listAddresses">
                                    @foreach ($orders as $order)
                                        <option value="{{ $order->name }}">{{ $order->name }}</option>
                                    @endforeach
                                </datalist>
                            </div>
                        </div>
                        <div class="col-12" id="fieldDate">
                            <div class="mt-3">
                                <label>Date</label>
                                <input  type='date' type="text" name="date" class="form-control" value='{{ date("Y-m-d") }}'>
                            </div>
                        </div>
                        <div class="col-12" id="fieldAgent">
                            <div class="mt-3">
                                <label>Agent</label>
                                <select name="agent_id" class="form-control">
                                    @foreach ($agents as $agent)
                                        <option value="{{ $agent->id }}">
                                            {{ $agent->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12" id="fieldPublicNotes">
                            <div class="mt-3">
                                <label>Public Notes</label>
                                <textarea cols=60 name="public_notes" class="form-control"> </textarea>
                            </div>
                        </div>
                        <div class="col-12" id="fieldPrivateNotes">
                            <div class="mt-3">
                                <label>Private Notes</label>
                                <textarea cols=60 name="private_notes" class="form-control"> </textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mt-3">
                                <button style="width:100%" class="btn btn-success" type="submit">Create</button>
                                {{--<button style="width:100%" class="btn btn-success" js-click="Admin.createRequest(this)" type="button"  >Create</button>--}}
                            </div>
                        </div>
                    </div>
                </form>
                @include('errors.list')
            </div>
        </div>
    </div>
</div>
<div class="modal"  id="ModalStartContract" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalCenterTitle"><b>Start Buy/Sell Contract</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="/start-contract">
                    @CSRF
                    <div class="row">
                        <div class="col-12">
                            {{--<div>--}}
                                {{--<label>Choose Assistant</label>--}}
                                {{--<select name="assistant" class="form-control">--}}
                                    {{--@foreach ($assistants as $assistant)--}}
                                    {{--<option value="{{ $assistant->id }}">--}}
                                        {{--{{ $assistant->name }}--}}
                                    {{--</option>--}}
                                    {{--@endforeach--}}
                                {{--</select>--}}
                            {{--</div>--}}
                            <div class="mt-3">
                                <label>Client(s) Name</label>
                                <input style="width:464px;"  name="client_name" value="" autocomplete="off"/>
                            </div>
                            <div class="mt-3">
                                <label>Address</label>
                                <input style="width:464px;"  name="address" value="" autocomplete="off"/>
                            </div>
                            <div class="mt-3">
                                <label>Price</label>
                                <input style="width:464px;"  name="price" value="" autocomplete="off"/>
                            </div>
                            <div class='d-flex flex-column mt-3'>
                                <label>Any Additional Information</label>
                                <textarea rows='5' name="comments"></textarea>
                            </div>

                        </div>
                        <div class="col-12 text-right my-3">
                            <button js-feature="with-loader" type="submit" class="btn btn-property w-100">Submit</button>
                        </div>
                    </div>
                </form>
                @include('errors.list')
            </div>
        </div>
    </div>
</div>
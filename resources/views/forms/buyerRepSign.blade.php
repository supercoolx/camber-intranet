<div class="modal"  id="ModalBuyerRepSign" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title" id="ModalCenterTitle"><b>Buyer Rep Sign</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="/buyer-rep-sign">
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
                        <label>Property Address</label>
                        <input class="form-control" style="width:464px;" list="listAddresses" name="address" value="" autocomplete="off"/>
                        <datalist id="listAddresses">
                                @foreach ($orders as $order)
                                  <option value="{{ $order->name }}">{{ $order->name }}</option>
                                @endforeach
                        </datalist>
                    </div>
                    <div class='mt-3'>
                        <label>Date to Install</label>
                        <input class="form-control" type='date' name="date" value='{{ date('Y-m-d') }}'>
                    </div>
                    <div class='mt-3'>
                        <label>Date to Uninstall</label>
                        <input class="form-control" type='date' name="date_uninstall" value='{{ date('Y-m-d') }}'>
                    </div>
                    <div class='d-flex flex-column mt-3'>
                        <label>Additional comments</label>
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
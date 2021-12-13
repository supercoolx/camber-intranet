<div class="modal"  id="ModalOrderClientGift" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title" id="ModalCenterTitle"><b>Order Client Gift</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="/order-client-gift">
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
                        <label>Client Name</label>
                        <input type="text" name="name" class="form-control">
                    </div>
                    <div class="mt-3">
                        <label>Client Address</label>
                        <input class="form-control" style="width:464px;" list="listAddresses" name="address" value="" autocomplete="off"/>
                        <datalist id="listAddresses">
                                @foreach ($orders as $order)
                                  <option value="{{ $order->name }}">{{ $order->name }}</option>
                                @endforeach
                        </datalist>
                    </div>
                    <div class="mt-3">
                        <label>Suggested Gift</label>
                        <input type="text" name="gift" class="form-control">
                    </div>
                    <div class='mt-3'>
                        <label>Delivery Date</label>
                        <input class="form-control" type='date' name="date" value='{{ date('Y-m-d') }}'>
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
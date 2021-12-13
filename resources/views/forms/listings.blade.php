<div class="modal"  id="ModalListings" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title" id="ModalCenterTitle"><b>Enter Address:</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="{{ route('orders.store') }}">
                      @CSRF
            <div class="row">
                <div class="col-12">
                    <input style="width:464px;" list="listAddresses" name="address" value="" autocomplete="off"/>
                    <datalist id="listAddresses">
                            @foreach ($orders as $order)
                              <option value="{{ $order->name }}">{{ $order->name }}</option>
                            @endforeach
                    </datalist>
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
<div class="modal"  id="ModalVendorList" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title" id="ModalCenterTitle"><b>Vendor List</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="/vendor-list">
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
                    <div class='mt-3'>
                        <label>Service Category</label>
                        <input class="form-control" type='text' name="serviceCategory" value=''>
                    </div>
                    <div class='mt-3'>
                        <label>Company Name</label>
                        <input class="form-control" type='text' name="companyName" value=''>
                    </div>
                    <div class='mt-3'>
                        <label>Phone Number</label>
                        <input class="form-control" type='text' name="phone" value=''>
                    </div>
                    <div class='mt-3'>
                        <label>Email Address</label>
                        <input class="form-control" type='email' name="email" value=''>
                    </div>
                    <div class='mt-3'>
                        <label>Website Address</label>
                        <input class="form-control" type='text' name="url" value=''>
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
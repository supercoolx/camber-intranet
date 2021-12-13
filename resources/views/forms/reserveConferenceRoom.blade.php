<div class="modal"  id="ModalReserveConferenceRoom" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title" id="ModalCenterTitle"><b>Reserve Conference Room</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="/reserve-conference-room">
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
                    <div class="form-check mt-3">
                      <input type="checkbox" name="parking" class="form-check-input" id="parking-space">
                      <label class="form-check-label" for="parking-space">Reserve a ramp parking space</label>
                    </div>
                    <div class='mt-3'>
                        <label>Date</label>
                        <input class="form-control" type='date' name="date" value='{{ date("Y-m-d") }}'>
                    </div>
                    <div class='mt-3'>
                        <label>Begin time</label><input class="form-control" type='text' name="beginTime" value=''>
                        <label>End time</label><input class="form-control" type='text' name="endTime" value=''>
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
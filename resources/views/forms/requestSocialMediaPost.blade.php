<div class="modal"  id="ModalRequestSocialMediaPost" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title" id="ModalCenterTitle"><b>Request Social Media Post</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="/request-social-post">
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
                        <label>Preferred date for post</label>
                        <input type='date' name="post_date" class="form-control">
                    </div>
<!--                    <div class="mt-3">
                        <label>Post Content</label>
                        <input type="text" name="post_content" class="form-control">
                    </div>-->
                    <div class='d-flex flex-column mt-3'>
                        <label>Post Content</label>
                        <textarea rows='5' name="post_content"></textarea>
                    </div>

                    <div class="mt-3">
                        <label>Link to photo</label>
                        <input type="text" name="post_photo" class="form-control">
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
<div class="form-group row pl-1">
    <div class="col-9">
        <a href="{{$field->placeholder}}" class="btn btn-property w-100" target="_blank">{{$field->name}}</a></p>
        @if ($field->bottom_text)
            <small class="form-text text-muted">
                {{ $field->bottom_text }}
            </small>
        @endif
    </div>
</div>
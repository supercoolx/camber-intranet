<div class="form-group row pl-1">
    <div class="col-9">
            <label>{{$field->name}}{{ $field->required ? '*' : '' }}</label>
            <input type="text" class="form-control"
            name="field[{{ $field->id }}][value]"
            value="{{ old("field[$field->id][value]", isset($field->pivot->value) ? $field->pivot->value : '') }}"
            maxlength="{{$field->length}}">
        @if ($field->bottom_text)
            <small class="form-text text-muted">
                {{ $field->bottom_text }}
            </small>
        @endif
    </div>
</div>
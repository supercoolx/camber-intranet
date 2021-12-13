<div class="form-group row pl-1">
    <div class="col-9">
            <label>{{ $field->name }}{{ $field->required ? ' *' : '' }}</label>
            <textarea class="form-control"
                name="field[{{ $field->id }}][value]"
                maxlength="{{$field->length}}"
                rows="{{ $field->length > 400 ? '6' : '3' }}">{{ old("field[$field->id][value]", isset($field->pivot->value) ? $field->pivot->value : '') }}</textarea>
        @if ($field->bottom_text)
            <small class="form-text text-muted">
                {{ $field->bottom_text }}
            </small>
        @endif
    </div>
</div>
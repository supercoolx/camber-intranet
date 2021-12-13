<div class="form-group row pl-3  d-flex flex-column">
    <label>{{ $field->name }}{{ $field->required ? 'Required*' : '' }}</label>
    <div class="form-check d-flex flex-column ml-3">
        @foreach ($field->options as $option)
            <label class="form-check-label">
            <input class="form-check-input" type="radio"
                name="field[{{ $field->id }}][value]"
                {{ $option['checked'] }}
                value="{{ $option['value'] }}">

                    {!! $option['value_with_hints'] !!}
                </label>
        @endforeach
    </div>
</div>

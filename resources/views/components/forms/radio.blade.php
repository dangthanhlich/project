<div class="form-group">
    <x-forms.label name="{{ $label }}" isRequired="{{ $isRequired }}" />
    <div class="check" data-default="{{ $dataDefault }}">
        @foreach ($options as $key => $value)
            <label>
                <input 
                    type="radio" 
                    class="minimal-blue i-radio {{ $attributes['class'] }}" 
                    name="{{ $name }}"
                    id="{{ $id . '-' . $key }}"
                    value={{ $key }}
                    {{ $key == $keySelected ? 'checked' : '' }}
                    {{ $isLabel ? 'disabled' : '' }}
                />
                    <span>{{ $value }}</span>
            </label>
        @endforeach
    </div>
    @if ($isHidden)
        <input 
            type="hidden" 
            name="{{ $name }}" 
            value="{{ $keySelected }}"
        />
    @endif
</div>
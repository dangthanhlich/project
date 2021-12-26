<div class="form-group">
    <x-forms.label name="{{ $label }}" isRequired="{{ $isRequired }}" />

    <div class="check">
        @foreach($options as $key => $value)
            <label>
                <input 
                    type="checkbox" 
                    name="{{ $name }}[]" 
                    data-label="{{ $label }}" 
                    value="{{ $key }}"
                    class="minimal-blue i-checkbox" 
                    {{ in_array($key, $valueChecked) ? 'checked' : '' }}
                    {{ $isLabel ? 'disabled' : '' }}
                >
                <span>{{ $value }}</span>
            </label>
        @endforeach
    </div>
</div>
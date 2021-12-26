<div class="form-group">
    <x-forms.label name="{{ $label }}" isRequired="{{ $isRequired }}" />

    <div class="{{ $divClass }}">
        <select
            data-label="{{ $label }}" 
            name="{{ $name }}" 
            class="form-control {{ $isSearch ? 'chosen-select' : '' }} {{ $attributes['class'] }}"
            id="{{ $id }}"
            {{ $isLabel ? 'disabled' : '' }}
            {{ !empty($onChange) ? 'onchange='.$onChange : '' }}
        >
            @if(!$noDefault)
                <option value="">{{ $isSearch ? '-' : '---' }}</option>
            @endif

            @foreach($options as $key => $value)
                <option value="{{ $key }}" {{ $key == $keySelected ? 'selected' : '' }}>{{ $value }}</option>
            @endforeach

        </select>
        @if ($isHidden)
            <input 
                type="hidden" 
                name="{{ $name }}" 
                value="{{ $keySelected }}"
            />
        @endif
    </div>
</div>

<div class="form-group {{ $attributes['class'] }}">
    @if (isset($label))
        <x-forms.label name="{{ $label }}" isRequired="{{ $isRequired }}" class="{{ $attributes['classLabel'] }}" />
    @endif

    <input 
        type="{{ $type }}" 
        name="{{ $name }}"
        value="{!! $value !!}"
        data-label="{{ $label }}"
        class="form-control {{ $attributes['classInput'] }}"
        id="{{ $id }}"
        {{ $isLabel ? 'disabled' : '' }}
        {{ !empty($attributes['autocomplete']) ? "autocomplete=".$attributes['autocomplete'] : '' }}
    >
    @if ($isHidden)
        <input 
            type="hidden" 
            name="{{ $name }}" 
            value="{{ !empty($valueHidden) ? $valueHidden : $value }}"
        />
    @endif
</div>

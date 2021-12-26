<div class="form-group {{ $attributes['class'] }}">
    @if (isset($label))
        <x-forms.label name="{{ $label }}" isRequired="{{ $isRequired }}" class="{{ $attributes['classLabel'] }}" />
    @endif

    <input 
        type="text"
        placeholder="yyyy/mm/dd"
        name="{{ $name }}"
        value="{{ $value }}"
        data-label="{{ $label }}"
        class="form-control datepicker {{ $attributes['classInput'] }}"
        id="{{ $id }}"
        {{ $isLabel ? 'disabled' : '' }}
        data-default={{ $dataDefault }}
        {{ !empty($dataDefault) ? 'data-is-default=true' : 'data-is-default=false' }}
    >
    @if ($isHidden)
        <input 
            type="hidden" 
            name="{{ $name }}" 
            value="{{ $value }}"
        />
    @endif
</div>

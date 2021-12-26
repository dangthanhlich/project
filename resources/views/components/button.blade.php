@if(!empty($href))
    <a href="{{ $href }}" class="{{ $disabled ? 'disabled' : '' }}">
@endif
    <button 
        type="{{ $type }}" 
        {{ empty($id) ? '' : "id=$id" }} 
        class="btn btn-round {{ $attributes['class'] }} {{ $type === 'submit' ? 'btn-submit' : '' }}"
        {{ $disabled ? 'disabled' : '' }}
        {{ !empty($attributes['data-id']) ? "data-id=".$attributes['data-id'] : '' }}
        {{ !empty($name) ? "name=".$name : '' }}
        {{ !empty($value) ? "value=".$value : '' }}
    >
        {{ $label }}
    </button>
@if(!empty($href))
    </a>
@endif

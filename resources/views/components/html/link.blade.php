<a href="{{ $to }}" class="{{ $isBtn ? 'btn btn-round' : '' }} {{ $attributes['class'] }}">
    {{ $icon }}  {{-- use x-slot with name="icon" --}}
    {{ $label }}
</a>
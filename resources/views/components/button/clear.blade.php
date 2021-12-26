<button 
    type="button" 
    class="btn btn-warning btn-round btn-clear-search"
    data-url="{{ route('public.resetSearch') }}"
    data-screen="{{ $screen }}"
>
    {{ !empty($label) ? $label : 'クリア' }}
</button>
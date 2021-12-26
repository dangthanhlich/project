<div class="alert alert-{{ $type }}" role="alert">
    @if(is_array($messages))
        @foreach($messages as $message)
            {!! $message !!} <br>
        @endforeach
    @else
        {!! $messages !!}
    @endif
</div>
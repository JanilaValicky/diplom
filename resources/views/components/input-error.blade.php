@props(['messages', 'class'])

@if ($messages)
    <div class="{{ $class }}">
        @foreach ((array) $messages as $message)
            <span>{{ $message }}</span>
        @endforeach
    </div>
@endif

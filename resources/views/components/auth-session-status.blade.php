@props(['status'])

@if ($status)
    <div role="status" {{ $attributes->merge(['class' => 'font-medium text-sm text-ink']) }}>
        {{ $status }}
    </div>
@endif

@props(['status' => null])

@if ($status)
    <x-alert variant="success" {{ $attributes }}>{{ $status }}</x-alert>
@endif

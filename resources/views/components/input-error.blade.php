@props(['messages' => null])

@if ($messages)
    <ul role="alert" {{ $attributes->merge(['class' => 'space-y-1 text-sm font-medium text-danger']) }}>
        @foreach ((array) $messages as $message)
            <li class="flex items-start gap-1.5 break-words">
                <x-icon name="exclamation-circle" size="size-5" class="mt-0.5 shrink-0" aria-hidden="true" />
                <span class="min-w-0">{{ $message }}</span>
            </li>
        @endforeach
    </ul>
@endif

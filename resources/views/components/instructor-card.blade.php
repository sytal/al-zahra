@props(['instructor'])

<x-card class="flex items-center gap-4">
    <img
        src="{{ $instructor->getFirstMediaUrl('avatar') ?: asset('images/avatar-placeholder.svg') }}"
        alt="{{ $instructor->name }}"
        class="size-14 rounded-full object-cover"
    >
    <div>
        <p class="font-semibold text-ink">{{ $instructor->name }}</p>
        <p class="text-sm text-ink/60">{{ __('common.instructor') }}</p>
    </div>
</x-card>

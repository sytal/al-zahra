@props(['icon', 'label', 'value'])

<x-card class="flex items-center gap-4">
    <span class="flex size-12 items-center justify-center rounded-lg bg-brand-primary/10 text-brand-primary">
        <x-icon :name="$icon" class="size-6" />
    </span>
    <div>
        <p class="text-2xl font-semibold text-ink">{{ $value }}</p>
        <p class="text-sm text-ink/60">{{ $label }}</p>
    </div>
</x-card>

@props(['percent' => 0, 'label' => null])

<div>
    @if ($label)
        <div class="mb-1 flex justify-between text-xs text-ink/60">
            <span>{{ $label }}</span>
            <span>{{ $percent }}%</span>
        </div>
    @endif
    <div class="h-2 w-full rounded-full bg-ink/10">
        <div class="h-2 rounded-full bg-brand-primary transition-all duration-200" style="width: {{ $percent }}%"></div>
    </div>
</div>

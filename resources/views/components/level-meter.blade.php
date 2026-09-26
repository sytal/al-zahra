{{-- Props: level beginner|intermediate|advanced (string or BackedEnum) or 1..3, showLabel bool. --}}
@props(['level' => 'beginner', 'showLabel' => true])

@php
$value = $level instanceof \BackedEnum ? $level->value : $level;
$step = match (true) {
    is_numeric($value) => max(1, min(3, (int) $value)),
    $value === 'advanced' => 3,
    $value === 'intermediate' => 2,
    default => 1,
};
$key = [1 => 'beginner', 2 => 'intermediate', 3 => 'advanced'][$step];
$label = __('enums.course_level.' . $key);
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-2 text-xs font-semibold text-body']) }} role="img" aria-label="{{ __('kit_sections.level_label') }}: {{ $label }}">
    <span class="flex items-end gap-0.5" aria-hidden="true">
        @foreach ([0.6, 0.95, 1.3] as $i => $h)
            <span class="w-1.5 rounded-sm transition-colors duration-base {{ $i < $step ? 'bg-brand-primary' : 'bg-[var(--border-strong)]' }}" style="height: {{ $h }}rem"></span>
        @endforeach
    </span>
    @if ($showLabel)<span class="min-w-0 truncate">{{ $label }}</span>@endif
</span>

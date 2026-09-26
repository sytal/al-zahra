@props([
    'name',
    'label' => null,
    'hint' => null,
    'id' => null,
    'required' => false,
    'optional' => false,
    'error' => null,
    'success' => false,
    'disabled' => false,
    'readonly' => false,
    'size' => 'md',
    'hideLabel' => false,
    'prefixIcon' => null,
    'counter' => null,
    'multiline' => false,
    'suffix' => null,
])

@php
$fid = $id ?? str_replace(['.', '[', ']'], '-', (string) $name);
$message = $error ?? ($name && $errors->has($name) ? $errors->first($name) : null);
$hasError = filled($message);
$heights = ['sm' => 'min-h-10', 'md' => 'min-h-11', 'lg' => 'min-h-12'];

$frame = 'group/field relative flex w-full rounded-xl border bg-surface-raised text-strong shadow-[inset_0_1px_2px_rgb(var(--c-text-strong)/0.05)] transition-[border-color,box-shadow,background-color] duration-fast ease-enter '
    . ($multiline ? 'items-stretch' : 'items-center ' . ($heights[$size] ?? $heights['md'])) . ' ';

if ($hasError) {
    $frame .= 'border-danger focus-within:shadow-[0_0_0_4px_rgb(var(--c-danger)/0.22)] ';
} elseif ($success) {
    $frame .= 'border-success focus-within:shadow-[0_0_0_4px_rgb(var(--c-success)/0.22)] ';
} else {
    $frame .= 'border-strong [@media(hover:hover)]:hover:border-brand/60 focus-within:border-brand focus-within:shadow-[0_0_0_4px_rgb(var(--c-ring)/0.22),0_8px_20px_-10px_rgb(var(--c-ring)/0.5)] ';
}
if ($disabled) {
    $frame .= 'cursor-not-allowed bg-surface-sunken opacity-60 ';
} elseif ($readonly) {
    $frame .= 'bg-surface-sunken ';
}
@endphp

<div x-data="{ n: 0, show: false }"
     x-init="const c = $el.querySelector('[data-control]'); if (c && c.value !== undefined) n = c.value.length"
     {{ $attributes->only('class')->merge(['class' => 'min-w-0']) }}>
    @if ($label)
        <label for="{{ $fid }}" class="{{ $hideLabel ? 'sr-only' : 'mb-1.5 flex flex-wrap items-baseline gap-x-2 text-sm font-semibold text-strong' }}">
            <span class="break-words">{{ $label }}</span>
            @if ($required)
                <span aria-hidden="true" class="text-danger">*</span>
            @elseif ($optional)
                <span class="text-xs font-normal text-muted">({{ __('kit_forms.optional') }})</span>
            @endif
        </label>
    @endif

    <div class="{{ $frame }}" x-on:input="if ($event.target.hasAttribute('data-control')) n = $event.target.value.length">
        @if ($prefixIcon)
            <x-icon :name="$prefixIcon" size="size-5" class="pointer-events-none ms-3.5 shrink-0 text-muted transition-colors duration-fast group-focus-within/field:text-link {{ $multiline ? 'mt-3' : '' }}" aria-hidden="true" />
        @endif

        {{ $slot }}

        @if ($suffix && trim((string) $suffix) !== '')
            {{ $suffix }}
        @endif
    </div>

    @if ($hint || $hasError || $counter)
        <div class="mt-1.5 flex flex-wrap items-start justify-between gap-x-3 gap-y-1 text-sm">
            <div class="min-w-0 flex-1 space-y-1">
                @if ($hasError)
                    <p id="{{ $fid }}-error" role="alert" class="flex items-start gap-1.5 break-words font-medium text-danger">
                        <x-icon name="exclamation-circle" size="size-5" class="mt-0.5 shrink-0" aria-hidden="true" />
                        <span class="min-w-0">{{ $message }}</span>
                    </p>
                @endif
                @if ($hint)
                    <p id="{{ $fid }}-hint" class="break-words text-muted">{{ $hint }}</p>
                @endif
            </div>
            @if ($counter)
                <p class="shrink-0 text-xs tabular-nums text-muted" x-bind:class="n >= {{ (int) $counter }} && 'font-semibold text-danger'">
                    <span x-text="n">0</span>/{{ (int) $counter }}
                </p>
            @endif
        </div>
    @endif
</div>

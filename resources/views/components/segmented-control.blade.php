@props(['name', 'options' => [], 'value' => null, 'label' => null, 'icons' => [], 'size' => 'md', 'block' => false, 'hint' => null])

@php
$fid = str_replace(['.', '[', ']'], '-', (string) $name);
$sizes = ['sm' => 'min-h-9 px-3 text-sm [@media(pointer:coarse)]:min-h-11', 'md' => 'min-h-11 px-4 text-sm', 'lg' => 'min-h-12 px-5 text-base'];
$wire = $attributes->whereStartsWith('wire:model');
@endphp

<fieldset {{ $attributes->only('class')->merge(['class' => 'min-w-0']) }}>
    @if ($label)<legend class="mb-1.5 text-sm font-semibold text-strong">{{ $label }}</legend>@endif
    <div class="no-scrollbar {{ $block ? 'flex w-full' : 'inline-flex max-w-full' }} gap-1 overflow-x-auto rounded-2xl border border-subtle bg-surface-sunken p-1 shadow-[inset_0_1px_2px_rgb(var(--c-text-strong)/0.06)]">
        @foreach ($options as $optionValue => $optionLabel)
            <label class="relative min-w-0 {{ $block ? 'flex-1' : 'shrink-0' }}">
                <input type="radio" name="{{ $name }}" id="{{ $fid }}-{{ $optionValue }}" value="{{ $optionValue }}" class="peer sr-only" @checked($value !== null && (string) $value === (string) $optionValue) {{ $wire }} />
                <span class="flex w-full cursor-pointer items-center justify-center gap-2 rounded-xl text-center font-semibold leading-snug text-muted transition-[background-color,color,box-shadow,transform] duration-fast ease-enter peer-checked:bg-surface-raised peer-checked:text-strong peer-checked:shadow-soft peer-focus-visible:ring-4 peer-focus-visible:ring-brand/30 peer-active:scale-[0.98] peer-disabled:cursor-not-allowed peer-disabled:opacity-50 [@media(hover:hover)]:hover:text-strong {{ $sizes[$size] ?? $sizes['md'] }}">
                    @if (! empty($icons[$optionValue]))<x-icon :name="$icons[$optionValue]" size="size-4" class="shrink-0" aria-hidden="true" />@endif
                    <span class="min-w-0 break-words">{{ $optionLabel }}</span>
                </span>
            </label>
        @endforeach
    </div>
    @if ($hint)<p class="mt-1.5 text-sm text-muted">{{ $hint }}</p>@endif
</fieldset>

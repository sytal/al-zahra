@props(['name', 'label' => null, 'value' => null, 'hint' => null, 'id' => null])

@php
$fid = $id ?? str_replace(['.', '[', ']'], '-', $name . '-' . $value);
@endphp

<label for="{{ $fid }}" class="group/radio flex min-h-11 cursor-pointer items-start gap-3 py-2.5 has-[:disabled]:cursor-not-allowed has-[:disabled]:opacity-60">
    <input
        type="radio"
        id="{{ $fid }}"
        name="{{ $name }}"
        value="{{ $value }}"
        {{ $attributes->class([
            'mt-px size-5 shrink-0 cursor-pointer border-2 border-strong bg-surface-raised text-brand shadow-soft transition-[background-color,border-color,box-shadow,transform] duration-fast ease-enter',
            'focus:ring-0 focus:ring-offset-0 focus-visible:ring-4 focus-visible:ring-brand/30 active:scale-90 checked:border-brand [@media(hover:hover)]:hover:border-brand',
        ]) }}
    />
    <span class="min-w-0 flex-1">
        <span class="block break-words text-sm font-medium leading-snug text-strong">{{ $label ?? $slot }}</span>
        @if ($hint)<span class="mt-0.5 block break-words text-sm text-muted">{{ $hint }}</span>@endif
    </span>
</label>

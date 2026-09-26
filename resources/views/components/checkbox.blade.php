@props(['name', 'label' => null, 'hint' => null, 'id' => null, 'error' => null])

@php
$fid = $id ?? str_replace(['.', '[', ']'], '-', (string) $name);
$message = $error ?? ($errors->has($name) ? $errors->first($name) : null);
$describedBy = trim(($hint ? $fid . '-hint ' : '') . ($message ? $fid . '-error' : ''));
@endphp

<div class="min-w-0">
    <label for="{{ $fid }}" class="group/check flex min-h-11 cursor-pointer items-start gap-3 py-2.5 has-[:disabled]:cursor-not-allowed has-[:disabled]:opacity-60">
        <input
            type="checkbox"
            id="{{ $fid }}"
            name="{{ $name }}"
            @if ($message) aria-invalid="true" @endif
            @if ($describedBy !== '') aria-describedby="{{ $describedBy }}" @endif
            {{ $attributes->class([
                'mt-px size-5 shrink-0 cursor-pointer rounded-md border-2 bg-surface-raised text-brand shadow-soft transition-[background-color,border-color,box-shadow,transform] duration-fast ease-enter',
                'focus:ring-0 focus:ring-offset-0 focus-visible:ring-4 focus-visible:ring-brand/30 active:scale-90 checked:border-brand',
                $message ? 'border-danger' : 'border-strong [@media(hover:hover)]:hover:border-brand',
            ]) }}
        />
        <span class="min-w-0 flex-1">
            <span class="block break-words text-sm font-medium leading-snug text-strong">{{ $label ?? $slot }}</span>
            @if ($hint)<span id="{{ $fid }}-hint" class="mt-0.5 block break-words text-sm text-muted">{{ $hint }}</span>@endif
        </span>
    </label>
    @if ($message)
        <p id="{{ $fid }}-error" role="alert" class="flex items-start gap-1.5 break-words text-sm font-medium text-danger">
            <x-icon name="exclamation-circle" size="size-5" class="mt-0.5 shrink-0" aria-hidden="true" />
            <span class="min-w-0">{{ $message }}</span>
        </p>
    @endif
</div>

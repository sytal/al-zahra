@props(['name', 'label' => null, 'hint' => null, 'id' => null, 'error' => null])

@php
$fid = $id ?? str_replace(['.', '[', ']'], '-', (string) $name);
$message = $error ?? ($errors->has($name) ? $errors->first($name) : null);
@endphp

<div class="min-w-0">
    <label for="{{ $fid }}" class="flex min-h-11 cursor-pointer items-center justify-between gap-4 py-2 has-[:disabled]:cursor-not-allowed has-[:disabled]:opacity-60">
        <span class="min-w-0 flex-1">
            <span class="block break-words text-sm font-medium leading-snug text-strong">{{ $label ?? $slot }}</span>
            @if ($hint)<span class="mt-0.5 block break-words text-sm text-muted">{{ $hint }}</span>@endif
        </span>
        <input type="checkbox" role="switch" id="{{ $fid }}" name="{{ $name }}" @if ($message) aria-invalid="true" aria-describedby="{{ $fid }}-error" @endif {{ $attributes->class('peer sr-only') }} />
        <span aria-hidden="true" class="relative h-7 w-12 shrink-0 rounded-full border border-strong bg-surface-sunken shadow-[inset_0_1px_3px_rgb(var(--c-text-strong)/0.12)] transition-[background-color,border-color,box-shadow] duration-base ease-enter peer-checked:border-brand peer-checked:bg-brand peer-focus-visible:ring-4 peer-focus-visible:ring-brand/30 peer-active:scale-95 peer-checked:[&>span]:translate-x-5 rtl:peer-checked:[&>span]:-translate-x-5 peer-checked:[&_.star-mark]:opacity-100">
            <span class="absolute start-0.5 top-0.5 grid size-[1.375rem] place-items-center rounded-full bg-surface-raised shadow-soft transition-[transform,background-color] duration-base ease-spring">
                <span class="star-mark text-[0.7rem] opacity-0 transition-opacity duration-base"></span>
            </span>
        </span>
    </label>
    @if ($message)
        <p id="{{ $fid }}-error" role="alert" class="break-words text-sm font-medium text-danger">{{ $message }}</p>
    @endif
</div>

@props(['name', 'label' => null, 'hint' => null, 'id' => null, 'accept' => null, 'multiple' => false, 'error' => null, 'required' => false, 'optional' => false, 'icon' => 'arrow-up-tray'])

@php
$fid = $id ?? str_replace(['.', '[', ']'], '-', (string) $name);
$message = $error ?? ($errors->has($name) ? $errors->first($name) : null);
$describedBy = trim(($hint ? $fid . '-hint ' : '') . ($message ? $fid . '-error' : ''));
@endphp

<div
    x-data="{
        files: [],
        drag: false,
        sync() { this.files = Array.from($refs.input.files || []) },
        clear() { $refs.input.value = ''; $refs.input.dispatchEvent(new Event('change', { bubbles: true })); this.files = [] },
        size(f) { return f.size >= 1048576 ? (f.size / 1048576).toFixed(1) + ' MB' : Math.max(1, Math.round(f.size / 1024)) + ' KB' },
    }"
    {{ $attributes->only('class')->merge(['class' => 'min-w-0']) }}
>
    @if ($label)
        <label for="{{ $fid }}" class="mb-1.5 flex flex-wrap items-baseline gap-x-2 text-sm font-semibold text-strong">
            <span class="break-words">{{ $label }}</span>
            @if ($required)<span aria-hidden="true" class="text-danger">*</span>@elseif ($optional)<span class="text-xs font-normal text-muted">({{ __('kit_forms.optional') }})</span>@endif
        </label>
    @endif

    <div class="relative">
        <input
            type="file"
            id="{{ $fid }}"
            name="{{ $name }}"
            x-ref="input"
            x-on:change="sync()"
            class="peer absolute inset-0 z-[1] size-full cursor-pointer opacity-0 disabled:cursor-not-allowed"
            @if ($accept) accept="{{ $accept }}" @endif
            @if ($multiple) multiple @endif
            @if ($message) aria-invalid="true" @endif
            @if ($describedBy !== '') aria-describedby="{{ $describedBy }}" @endif
            @if ($required) aria-required="true" @endif
            x-on:dragenter="drag = true" x-on:dragover.prevent="drag = true" x-on:dragleave="drag = false" x-on:drop="drag = false"
            {{ $attributes->except('class') }}
        />
        <div
            class="flex min-h-28 flex-col items-center justify-center gap-2 rounded-2xl border-2 border-dashed bg-surface-raised px-4 py-6 text-center transition-[border-color,background-color,box-shadow] duration-fast ease-enter peer-focus-visible:border-brand peer-focus-visible:ring-4 peer-focus-visible:ring-brand/30 peer-disabled:opacity-60 [@media(hover:hover)]:peer-hover:border-brand/70 [@media(hover:hover)]:peer-hover:bg-tint {{ $message ? 'border-danger' : 'border-strong' }}"
            x-bind:class="drag && 'border-brand bg-tint shadow-glow'"
        >
            <span class="grid size-11 place-items-center rounded-full bg-brand/10 text-link transition-transform duration-base ease-spring" x-bind:class="drag && 'scale-110'">
                <x-icon :name="$icon" size="size-6" aria-hidden="true" />
            </span>
            <span class="text-sm font-semibold text-strong">{{ $multiple ? __('kit_forms.choose_files') : __('kit_forms.choose_file') }}</span>
            <span class="text-sm text-muted">{{ __('kit_forms.drop_hint') }}</span>
        </div>
    </div>

    <ul class="mt-2 space-y-1.5" x-show="files.length" x-cloak>
        <template x-for="f in files" :key="f.name + f.size">
            <li class="flex min-h-11 items-center gap-3 rounded-xl border border-subtle bg-surface-sunken px-3 text-sm">
                <x-icon name="document" size="size-5" class="shrink-0 text-link" aria-hidden="true" />
                <span class="min-w-0 flex-1 truncate font-medium text-strong" x-text="f.name"></span>
                <span class="shrink-0 tabular-nums text-muted" x-text="size(f)"></span>
            </li>
        </template>
        <li>
            <button type="button" x-on:click="clear()" class="tap-target rounded-lg px-2 text-sm font-semibold text-danger outline-none focus-visible:ring-2 focus-visible:ring-danger">{{ __('kit_forms.clear_files') }}</button>
        </li>
    </ul>

    @if ($hint)<p id="{{ $fid }}-hint" class="mt-1.5 break-words text-sm text-muted">{{ $hint }}</p>@endif
    @if ($message)
        <p id="{{ $fid }}-error" role="alert" class="mt-1.5 flex items-start gap-1.5 break-words text-sm font-medium text-danger">
            <x-icon name="exclamation-circle" size="size-5" class="mt-0.5 shrink-0" aria-hidden="true" />
            <span class="min-w-0">{{ $message }}</span>
        </p>
    @endif
</div>

@props(['name', 'label' => null, 'rows' => 4])

<x-forms._field-wrapper :name="$name" :label="$label">
    <textarea
        id="{{ $name }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        @if ($errors->has($name)) aria-invalid="true" aria-describedby="{{ $name }}-error" @endif
        {{ $attributes->merge(['class' => 'block w-full rounded-lg bg-white dark:bg-surface text-ink placeholder:text-ink/40 shadow-sm transition duration-200 ease-in-out ' . ($errors->has($name) ? 'border-danger focus:border-danger focus:ring-danger' : 'border-ink/20 focus:border-brand-primary focus:ring-brand-primary')]) }}
    >{{ $slot }}</textarea>
</x-forms._field-wrapper>

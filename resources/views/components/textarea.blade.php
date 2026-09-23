@props(['name', 'label' => null, 'rows' => 4])

<x-forms._field-wrapper :name="$name" :label="$label">
    <textarea
        id="{{ $name }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        {{ $attributes->merge(['class' => 'block w-full rounded-lg border-ink/20 bg-white dark:bg-surface text-ink shadow-sm transition duration-200 ease-in-out focus:border-brand-primary focus:ring-brand-primary ' . ($errors->has($name) ? 'border-danger focus:border-danger focus:ring-danger' : '')]) }}
    >{{ $slot }}</textarea>
</x-forms._field-wrapper>

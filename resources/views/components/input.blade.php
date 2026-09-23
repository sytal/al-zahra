@props(['name', 'label' => null, 'type' => 'text'])

<x-forms._field-wrapper :name="$name" :label="$label">
    <input
        type="{{ $type }}"
        id="{{ $name }}"
        name="{{ $name }}"
        {{ $attributes->merge(['class' => 'block w-full rounded-lg border-ink/20 bg-white dark:bg-surface text-ink shadow-sm transition duration-200 ease-in-out focus:border-brand-primary focus:ring-brand-primary ' . ($errors->has($name) ? 'border-danger focus:border-danger focus:ring-danger' : '')]) }}
    />
</x-forms._field-wrapper>

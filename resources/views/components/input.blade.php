@props(['name', 'label' => null, 'type' => 'text'])

<x-forms._field-wrapper :name="$name" :label="$label">
    <input
        type="{{ $type }}"
        id="{{ $name }}"
        name="{{ $name }}"
        @if ($errors->has($name)) aria-invalid="true" aria-describedby="{{ $name }}-error" @endif
        {{ $attributes->merge(['class' => 'block w-full rounded-lg bg-white dark:bg-surface text-ink placeholder:text-ink/70 shadow-sm transition duration-200 ease-in-out ' . ($errors->has($name) ? 'border-danger focus:border-danger focus:ring-danger' : 'border-ink/20 focus:border-brand-primary focus:ring-brand-primary')]) }}
    />
</x-forms._field-wrapper>

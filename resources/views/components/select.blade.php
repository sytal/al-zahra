@props(['name', 'label' => null, 'options' => []])

<x-forms._field-wrapper :name="$name" :label="$label">
    <select
        id="{{ $name }}"
        name="{{ $name }}"
        {{ $attributes->merge(['class' => 'block w-full rounded-lg border-ink/20 bg-white dark:bg-surface text-ink shadow-sm transition duration-200 ease-in-out focus:border-brand-primary focus:ring-brand-primary ' . ($errors->has($name) ? 'border-danger focus:border-danger focus:ring-danger' : '')]) }}
    >
        @foreach ($options as $value => $label)
            <option value="{{ $value }}">{{ $label }}</option>
        @endforeach
        {{ $slot }}
    </select>
</x-forms._field-wrapper>

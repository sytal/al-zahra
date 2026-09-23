@props(['name', 'label' => null, 'value'])

<label class="flex items-center gap-2 text-sm text-ink">
    <input
        type="radio"
        name="{{ $name }}"
        value="{{ $value }}"
        {{ $attributes->merge(['class' => 'border-ink/20 text-brand-primary shadow-sm transition duration-200 ease-in-out focus:ring-brand-primary']) }}
    />
    {{ $label }}
</label>

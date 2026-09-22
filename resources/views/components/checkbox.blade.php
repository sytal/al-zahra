@props(['name', 'label' => null])

<label for="{{ $name }}" class="flex items-center gap-2 text-sm text-ink">
    <input
        type="checkbox"
        id="{{ $name }}"
        name="{{ $name }}"
        {{ $attributes->merge(['class' => 'rounded border-ink/20 text-brand-primary shadow-sm transition duration-200 ease-in-out focus:ring-brand-primary']) }}
    />
    {{ $label }}
</label>
@error($name)
    <p class="mt-1 text-sm text-danger">{{ $message }}</p>
@enderror

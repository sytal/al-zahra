@props(['name', 'label' => null])

<label for="{{ $name }}" class="flex cursor-pointer items-center gap-2 text-sm text-ink">
    <input
        type="checkbox"
        id="{{ $name }}"
        name="{{ $name }}"
        {{ $attributes->merge(['class' => 'size-4 rounded border-ink/20 text-brand-primary shadow-sm transition duration-200 ease-in-out focus:ring-brand-primary']) }}
    />
    {{ $label }}
</label>
@error($name)
    <p id="{{ $name }}-error" role="alert" class="mt-1.5 text-sm text-danger">{{ $message }}</p>
@enderror

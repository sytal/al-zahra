@props(['value' => null, 'required' => false])

<label {{ $attributes->merge(['class' => 'mb-1.5 block text-sm font-semibold text-strong']) }}>
    {{ $value ?? $slot }}@if ($required)<span aria-hidden="true" class="ms-1 text-danger">*</span>@endif
</label>

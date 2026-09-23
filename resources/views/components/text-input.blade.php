@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-ink/20 focus:border-brand-primary focus:ring-brand-primary rounded-md shadow-sm']) }}>
